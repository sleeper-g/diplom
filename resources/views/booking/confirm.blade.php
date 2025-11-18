<x-cinema-layout>
    <main>
        <section class="ticket">
            <header class="tichet__check">
                <h2 class="ticket__check-title">Электронный билет</h2>
            </header>
            
            <div class="ticket__info-wrapper">
                <p class="ticket__info">На фильм: <span class="ticket__details ticket__title">{{ $session->movie->title ?? 'Фильм' }}</span></p>
                <p class="ticket__info">Места: <span class="ticket__details ticket__chairs">
                    @foreach($tickets ?? [] as $ticket)
                        Ряд {{ $ticket->seat->row }}, Место {{ $ticket->seat->number }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </span></p>
                <p class="ticket__info">В зале: <span class="ticket__details ticket__hall">{{ $session->hall->name ?? 'Не указан' }}</span></p>
                <p class="ticket__info">Начало сеанса: <span class="ticket__details ticket__start">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}</span></p>
                <p class="ticket__info">Дата: <span class="ticket__details">{{ \Carbon\Carbon::parse($session->start_time)->format('d.m.Y') }}</span></p>
                <p class="ticket__info">Код бронирования: <span class="ticket__details">{{ $bookingCode ?? 'N/A' }}</span></p>
                <p class="ticket__info">Стоимость: <span class="ticket__details">{{ $totalPrice ?? 0 }} ₽</span></p>

                @if(isset($qrCodeUrl) && !empty($qrCodeUrl))
                    <img class="ticket__info-qr" src="{{ $qrCodeUrl }}" alt="QR Code">
                @elseif(isset($qrCode) && !empty($qrCode))
                    <img class="ticket__info-qr" src="{{ $qrCode }}" alt="QR Code">
                @elseif(isset($tickets) && count($tickets) > 0 && !empty($tickets[0]->qr_code))
                    <img class="ticket__info-qr" src="{{ $tickets[0]->qr_code }}" alt="QR Code">
                @else
                    <img class="ticket__info-qr" src="{{ asset('i/qr-code.png') }}" alt="QR Code">
                @endif

                <p class="ticket__hint">Покажите QR-код нашему контроллеру для подтверждения бронирования.</p>
                <p class="ticket__hint">Приятного просмотра!</p>
            </div>
        </section>

        <div style="text-align: center; padding: 2rem;">
            <a href="{{ route('home') }}" class="acceptin-button" style="display: inline-block; text-decoration: none; margin-right: 1rem;">На главную</a>
            <a href="{{ route('schedule.index') }}" class="acceptin-button" style="display: inline-block; text-decoration: none;">Расписание</a>
        </div>
    </main>
</x-cinema-layout>
