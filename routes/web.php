<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Главная страница (редирект на home)
Route::get('/', function () {
    return redirect()->route('home');
});

// Гостевые маршруты
Route::get('/home', function () {
    $selectedDate = request('date', \Carbon\Carbon::today()->format('Y-m-d'));
    return view('home', compact('selectedDate'));
})->name('home');

Route::get('/movies', function () {
    $movies = \App\Models\Movie::latest()->get();
    return view('movies.index', compact('movies'));
})->name('movies.index');

Route::get('/movies/{id}', function ($id) {
    $movie = \App\Models\Movie::findOrFail($id);
    return view('movies.show', compact('movie'));
})->name('movies.show');

Route::get('/schedule', function () {
    $date = request('date', \Carbon\Carbon::today()->format('Y-m-d'));
    $movieId = request('movie');
    
    $query = \App\Models\Session::with(['movie', 'hall'])
        ->whereDate('start_time', $date)
        ->orderBy('start_time');
    
    if ($movieId) {
        $query->where('movie_id', $movieId);
    }
    
    $sessions = $query->get();
    return view('schedule.index', compact('sessions', 'date'));
})->name('schedule.index');

Route::middleware('auth')->group(function () {
    // Важно: конкретные маршруты должны быть ДО параметрических
    Route::get('/booking/payment', function () {
        $sessionId = session('booking_session_id');
        $selectedSeats = session('booking_selected_seats');
        $totalPrice = session('booking_total_price');

        if (!$sessionId || !$selectedSeats) {
            return redirect()->route('home')->withErrors(['error' => 'Сессия бронирования истекла']);
        }

        $session = \App\Models\Session::with(['movie', 'hall'])->findOrFail($sessionId);
        $selectedSeatsData = $selectedSeats;
        
        return view('booking.payment', compact('session', 'selectedSeatsData', 'totalPrice'));
    })->name('booking.payment');

    Route::post('/booking', function () {
        $validated = request()->validate([
            'session_id' => 'required|exists:cinema_sessions,id',
            'selected_seats' => 'required|json',
        ]);

        $session = \App\Models\Session::with(['movie', 'hall'])->findOrFail($validated['session_id']);
        $selectedSeats = json_decode($validated['selected_seats'], true);

        if (empty($selectedSeats) || !is_array($selectedSeats)) {
            return redirect()->back()->withErrors(['seats' => 'Выберите хотя бы одно место']);
        }

        // Проверяем, что выбранные места не заблокированы и не заняты
        $seatIds = array_column($selectedSeats, 'seat_id');
        $seats = \App\Models\Seat::whereIn('id', $seatIds)->get();
        
        foreach ($seats as $seat) {
            // Проверка на заблокированные места
            if ($seat->type === 'disabled') {
                return redirect()->back()->withErrors(['seats' => "Место Ряд {$seat->row}, Место {$seat->number} заблокировано и не может быть забронировано"]);
            }
            
            // Проверка на уже занятые места
            if ($session->tickets->contains('seat_id', $seat->id)) {
                return redirect()->back()->withErrors(['seats' => "Место Ряд {$seat->row}, Место {$seat->number} уже занято"]);
            }
        }

        // Сохраняем данные в сессию для страницы оплаты
        session([
            'booking_session_id' => $session->id,
            'booking_selected_seats' => $selectedSeats,
            'booking_total_price' => array_sum(array_column($selectedSeats, 'price')),
        ]);

        return redirect()->route('booking.payment');
    })->name('booking.store');

    Route::get('/booking/{id}', function ($id) {
        $session = \App\Models\Session::with(['movie', 'hall.seats', 'tickets'])->findOrFail($id);
        
        if (!$session->hall) {
            abort(404, 'Зал не найден для этого сеанса');
        }
        
        $seats = $session->hall->seats ?? collect();
        
        // Если мест нет, создаем их автоматически
        if ($seats->isEmpty() && $session->hall->rows && $session->hall->seats_per_row) {
            for ($row = 1; $row <= $session->hall->rows; $row++) {
                for ($seat = 1; $seat <= $session->hall->seats_per_row; $seat++) {
                    \App\Models\Seat::create([
                        'hall_id' => $session->hall->id,
                        'row' => $row,
                        'number' => $seat,
                        'type' => 'regular',
                    ]);
                }
            }
            $seats = $session->hall->fresh()->seats;
        }
        
        $regularPrice = (int) \App\Models\Setting::getValue('regular_price', config('cinema.default_regular_price'));
        $vipPrice = (int) \App\Models\Setting::getValue('vip_price', config('cinema.default_vip_price'));
        return view('booking.create', compact('session', 'seats', 'regularPrice', 'vipPrice'));
    })->name('booking.create');

    Route::post('/booking/confirm', function () {
        $validated = request()->validate([
            'session_id' => 'required|exists:cinema_sessions,id',
            'selected_seats' => 'required|json',
        ]);

        $session = \App\Models\Session::with(['movie', 'hall', 'tickets'])->findOrFail($validated['session_id']);
        $selectedSeats = json_decode($validated['selected_seats'], true);

        if (empty($selectedSeats) || !is_array($selectedSeats)) {
            return redirect()->back()->withErrors(['seats' => 'Выберите хотя бы одно место']);
        }

        // Проверяем, что выбранные места не заблокированы и не заняты
        $seatIds = array_column($selectedSeats, 'seat_id');
        $seats = \App\Models\Seat::whereIn('id', $seatIds)->get();
        
        foreach ($seats as $seat) {
            // Проверка на заблокированные места
            if ($seat->type === 'disabled') {
                return redirect()->back()->withErrors(['seats' => "Место Ряд {$seat->row}, Место {$seat->number} заблокировано и не может быть забронировано"]);
            }
            
            // Проверка на уже занятые места
            if ($session->tickets->contains('seat_id', $seat->id)) {
                return redirect()->back()->withErrors(['seats' => "Место Ряд {$seat->row}, Место {$seat->number} уже занято"]);
            }
        }

        // Генерируем уникальный код бронирования
        $bookingCode = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        $totalPrice = 0;
        $ticketIds = [];

        // Создаем билеты
        foreach ($selectedSeats as $seatData) {
            $seat = \App\Models\Seat::findOrFail($seatData['seat_id']);
            
            // Проверяем, не заблокировано ли место
            if ($seat->type === 'disabled') {
                return redirect()->back()->withErrors(['error' => "Место Ряд {$seat->row}, Место {$seat->number} заблокировано и не может быть забронировано"]);
            }
            
            // Проверяем, не занято ли место
            $existingTicket = \App\Models\Ticket::where('session_id', $session->id)
                ->where('seat_id', $seat->id)
                ->first();
            
            if ($existingTicket) {
                return redirect()->back()->withErrors(['error' => 'Место уже забронировано']);
            }

            // Генерируем QR-код (можно использовать API для генерации QR)
            $qrData = json_encode([
                'booking_code' => $bookingCode,
                'session_id' => $session->id,
                'seat_id' => $seat->id,
                'row' => $seat->row,
                'number' => $seat->number,
            ]);
            $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrData);

            $ticket = \App\Models\Ticket::create([
                'session_id' => $session->id,
                'seat_id' => $seat->id,
                'qr_code' => $qrCodeUrl,
                'price' => $seatData['price'],
            ]);

            $ticketIds[] = $ticket->id;
            $totalPrice += $seatData['price'];
        }

        // Загружаем связи для билетов
        $tickets = \App\Models\Ticket::with(['seat'])
            ->whereIn('id', $ticketIds)
            ->get();

        // Очищаем сессию
        session()->forget(['booking_session_id', 'booking_selected_seats', 'booking_total_price']);

        // Используем QR-код первого билета (все билеты имеют одинаковый код бронирования)
        $qrCodeUrl = $tickets->first()->qr_code ?? $qrCodeUrl;

        return view('booking.confirm', compact('session', 'tickets', 'bookingCode', 'totalPrice', 'qrCodeUrl'));
    })->name('booking.confirm');
});

// Административные маршруты
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            $date = request('date', \Carbon\Carbon::today()->format('Y-m-d'));
            $halls = \App\Models\Hall::with(['seats' => function ($query) {
                $query->orderBy('row')->orderBy('number');
            }])->orderBy('name')->get();
            $movies = \App\Models\Movie::orderBy('title')->get();
            $sessions = \App\Models\Session::with(['movie', 'hall'])
                ->whereDate('start_time', $date)
                ->orderBy('start_time')
                ->get();
            $selectedHallId = request('hall_id', $halls->first()?->id);
            $selectedHall = $halls->firstWhere('id', (int) $selectedHallId);
            $regularPrice = \App\Models\Setting::getValue('regular_price', config('cinema.default_regular_price'));
            $vipPrice = \App\Models\Setting::getValue('vip_price', config('cinema.default_vip_price'));
            $stats = [
                'activeHalls' => $halls->where('is_active', true)->count(),
                'totalHalls' => $halls->count(),
                'sessionsCount' => $sessions->count(),
                'ticketsToday' => \App\Models\Ticket::whereDate('created_at', $date)->count(),
            ];

            return view('admin.dashboard', compact(
                'halls',
                'selectedHall',
                'selectedHallId',
                'movies',
                'sessions',
                'date',
                'regularPrice',
                'vipPrice',
                'stats'
            ));
        })->name('dashboard');

        Route::get('/halls', function () {
            $halls = \App\Models\Hall::orderBy('name')->get();
            return view('admin.halls.index', compact('halls'));
        })->name('halls.index');

        Route::get('/halls/create', function () {
            return view('admin.halls.create');
        })->name('halls.create');

        Route::post('/halls', function () {
            $validated = request()->validate([
                'name' => 'required|string|max:255',
                'rows' => 'required|integer|min:1|max:20',
                'seats_per_row' => 'required|integer|min:1|max:30',
            ]);

            $hall = \App\Models\Hall::create([
                'name' => $validated['name'],
                'rows' => $validated['rows'],
                'seats_per_row' => $validated['seats_per_row'],
                'is_active' => request()->boolean('is_active'),
            ]);

            for ($row = 1; $row <= $hall->rows; $row++) {
                for ($seat = 1; $seat <= $hall->seats_per_row; $seat++) {
                    \App\Models\Seat::create([
                        'hall_id' => $hall->id,
                        'row' => $row,
                        'number' => $seat,
                        'type' => 'regular',
                    ]);
                }
            }

            return redirect()->route('admin.halls.index')->with('success', 'Зал успешно создан');
        })->name('halls.store');

        Route::post('/halls/{id}/toggle-status', function ($id) {
            $hall = \App\Models\Hall::findOrFail($id);
            $hall->update([
                'is_active' => !$hall->is_active,
            ]);

            $message = $hall->is_active
                ? 'Продажа билетов открыта'
                : 'Продажа билетов приостановлена';

            return back()->with('success', "{$hall->name}: {$message}");
        })->name('halls.toggle');

        Route::get('/halls/{id}/edit', function ($id) {
            $hall = \App\Models\Hall::findOrFail($id);
            return view('admin.halls.edit', compact('hall'));
        })->name('halls.edit');

        Route::put('/halls/{id}', function ($id) {
            $hall = \App\Models\Hall::findOrFail($id);
            $validated = request()->validate([
                'name' => 'required|string|max:255',
                'rows' => 'required|integer|min:1|max:20',
                'seats_per_row' => 'required|integer|min:1|max:30',
            ]);

            $hall->update([
                'name' => $validated['name'],
                'rows' => $validated['rows'],
                'seats_per_row' => $validated['seats_per_row'],
                'is_active' => request()->boolean('is_active', $hall->is_active),
            ]);

            // Обновляем типы мест, если они были изменены
            if (request()->has('seat_types')) {
                $seatTypesJson = request()->input('seat_types');
                if (!empty($seatTypesJson)) {
                    $seatTypes = json_decode($seatTypesJson, true);
                    if (is_array($seatTypes)) {
                        foreach ($seatTypes as $seatId => $type) {
                            $seat = \App\Models\Seat::find($seatId);
                            if ($seat && $seat->hall_id == $hall->id) {
                                // Преобразуем тип: regular -> regular, vip -> VIP, disabled -> disabled
                                $dbType = match($type) {
                                    'vip' => 'VIP',
                                    'disabled' => 'disabled',
                                    default => 'regular',
                                };
                                $seat->update(['type' => $dbType]);
                            }
                        }
                    }
                }
            }

            // Генерируем места, если их еще нет или изменились размеры зала
            $existingSeats = $hall->seats()->count();
            $expectedSeats = $hall->rows * $hall->seats_per_row;
            
            if ($existingSeats != $expectedSeats) {
                // Удаляем старые места
                $hall->seats()->delete();
                
                // Создаем новые места
                for ($row = 1; $row <= $hall->rows; $row++) {
                    for ($number = 1; $number <= $hall->seats_per_row; $number++) {
                        \App\Models\Seat::create([
                            'hall_id' => $hall->id,
                            'row' => $row,
                            'number' => $number,
                            'type' => 'regular',
                        ]);
                    }
                }
            }

            return redirect()->route('admin.dashboard', ['hall_id' => $hall->id])->with('success', 'Зал успешно обновлен');
        })->name('halls.update');

        Route::delete('/halls/{id}', function ($id) {
            $hall = \App\Models\Hall::findOrFail($id);
            $hall->delete();
            return redirect()->route('admin.halls.index')->with('success', 'Зал успешно удален');
        })->name('halls.destroy');

        Route::get('/movies', function () {
            $movies = \App\Models\Movie::orderByDesc('created_at')->get();
            return view('admin.movies.index', compact('movies'));
        })->name('movies.index');

        Route::get('/movies/create', function () {
            return view('admin.movies.create');
        })->name('movies.create');

        Route::post('/movies', function () {
            $validated = request()->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'duration' => 'required|integer|min:1|max:300',
                'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $validated;
            
            if (request()->hasFile('poster')) {
                $poster = request()->file('poster');
                $posterName = time() . '_' . $poster->getClientOriginalName();
                $poster->move(public_path('storage/posters'), $posterName);
                $data['poster'] = 'storage/posters/' . $posterName;
            }

            \App\Models\Movie::create($data);
            return redirect()->route('admin.movies.index')->with('success', 'Фильм успешно создан');
        })->name('movies.store');

        Route::get('/movies/{id}/edit', function ($id) {
            $movie = \App\Models\Movie::findOrFail($id);
            return view('admin.movies.edit', compact('movie'));
        })->name('movies.edit');

        Route::put('/movies/{id}', function ($id) {
            $movie = \App\Models\Movie::findOrFail($id);
            $validated = request()->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'duration' => 'required|integer|min:1|max:300',
                'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $validated;
            
            if (request()->hasFile('poster')) {
                // Удаляем старый постер, если он существует
                if ($movie->poster && file_exists(public_path($movie->poster))) {
                    unlink(public_path($movie->poster));
                }
                
                $poster = request()->file('poster');
                $posterName = time() . '_' . $poster->getClientOriginalName();
                $poster->move(public_path('storage/posters'), $posterName);
                $data['poster'] = 'storage/posters/' . $posterName;
            } else {
                // Если новый постер не загружен, сохраняем старый
                unset($data['poster']);
            }

            $movie->update($data);
            return redirect()->route('admin.movies.index')->with('success', 'Фильм успешно обновлен');
        })->name('movies.update');

        Route::delete('/movies/{id}', function ($id) {
            $movie = \App\Models\Movie::findOrFail($id);
            $movie->delete();
            return redirect()->route('admin.movies.index')->with('success', 'Фильм успешно удален');
        })->name('movies.destroy');

        Route::get('/sessions', function () {
            $date = request('date', \Carbon\Carbon::today()->format('Y-m-d'));
            $sessions = \App\Models\Session::with(['movie', 'hall'])
                ->whereDate('start_time', $date)
                ->orderBy('start_time')
                ->get();
            return view('admin.sessions.index', compact('sessions', 'date'));
        })->name('sessions.index');

        Route::get('/sessions/create', function () {
            $movies = \App\Models\Movie::all();
            $halls = \App\Models\Hall::orderBy('name')->get();
            return view('admin.sessions.create', compact('movies', 'halls'));
        })->name('sessions.create');

        Route::post('/sessions', function () {
            $validated = request()->validate([
                'movie_id' => 'required|exists:movies,id',
                'hall_id' => 'required|exists:halls,id',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]);

            \App\Models\Session::create($validated);
            return redirect()->route('admin.sessions.index')->with('success', 'Сеанс успешно создан');
        })->name('sessions.store');

        Route::get('/sessions/{id}/edit', function ($id) {
            $session = \App\Models\Session::findOrFail($id);
            $movies = \App\Models\Movie::all();
            $halls = \App\Models\Hall::orderBy('name')->get();
            return view('admin.sessions.edit', compact('session', 'movies', 'halls'));
        })->name('sessions.edit');

        Route::put('/sessions/{id}', function ($id) {
            $session = \App\Models\Session::findOrFail($id);
            $validated = request()->validate([
                'movie_id' => 'required|exists:movies,id',
                'hall_id' => 'required|exists:halls,id',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]);

            $session->update($validated);
            return redirect()->route('admin.sessions.index')->with('success', 'Сеанс успешно обновлен');
        })->name('sessions.update');

        Route::delete('/sessions/{id}', function ($id) {
            $session = \App\Models\Session::findOrFail($id);
            $session->delete();
            return redirect()->route('admin.sessions.index')->with('success', 'Сеанс успешно удален');
        })->name('sessions.destroy');

        Route::get('/prices', function () {
            $regularPrice = \App\Models\Setting::getValue('regular_price', config('cinema.default_regular_price'));
            $vipPrice = \App\Models\Setting::getValue('vip_price', config('cinema.default_vip_price'));
            return view('admin.prices.index', compact('regularPrice', 'vipPrice'));
        })->name('prices.index');

        Route::put('/prices', function () {
            $validated = request()->validate([
                'regular_price' => 'required|integer|min:0|max:10000',
                'vip_price' => 'required|integer|min:0|max:15000',
            ]);

            \App\Models\Setting::setValue('regular_price', $validated['regular_price']);
            \App\Models\Setting::setValue('vip_price', $validated['vip_price']);

            return back()->with('success', 'Цены успешно обновлены');
        })->name('prices.update');
});

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
