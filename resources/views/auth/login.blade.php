<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Авторизация | ИдёмВКино</title>
  <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
</head>

<body>

  <header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
    <span class="page-header__subtitle">Администраторррская</span>
  </header>
  
  <main>
    <section class="login">
      <header class="login__header">
        <h2 class="login__title">Авторизация</h2>
      </header>
      <div class="login__wrapper">
        @if ($errors->any())
          <div class="conf-step__alert conf-step__alert_error">
            <div class="conf-step__alert-title">Ошибка</div>
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if (session('status'))
          <div class="conf-step__alert conf-step__alert_success">
            <div class="conf-step__alert-title">Успешно</div>
            <div>{{ session('status') }}</div>
          </div>
        @endif

        <form class="login__form" action="{{ route('login') }}" method="POST" accept-charset="utf-8">
          @csrf
          <label class="login__label" for="email">
            E-mail
            <input class="login__input" type="email" placeholder="example@domain.xyz" name="email" id="email" value="{{ old('email') }}" required autofocus>
          </label>
          <label class="login__label" for="password">
            Пароль
            <input class="login__input" type="password" placeholder="" name="password" id="password" required>
          </label>
          <div class="text-center">
            <input value="Авторизоваться" type="submit" class="login__button">
          </div>
        </form>
      </div>
    </section>
  </main>

</body>
</html>
