<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Shop Hologram') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('themes/back/css/pim.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/back/css/main.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">
</head>

<body>
    <div class="login_page_wrapper">
        <div class="login_page">
            <div class="login_inner">
                <div class="login_header text_center">
                    <h2>Hologram</h2>
                    <p>Admin</p>
                </div>
                <div class="has-danger">
                    <div class="form-control-feedback">{{ session('message') }}</div>
                </div>
                <form method="post" action="{{ route('login_admin_post') }}">
                    @csrf
                    <div class="login_form">
                        <div class="login_form_field mb_15">
                            <input class="form_global" type="text" placeholder="Username" name="username" value="{{ old('username') }}" required>
                        </div>
                        <div class="login_form_field mb_15">
                            <input class="form_global" type="password" placeholder="Password" name="password" required>
                        </div>
                        <div class="login_form_field mb_15">
                            <div class="custom_checkbox">

                                <input type="checkbox" id="remember_me" name="remember_me" checked>
                                <label for="remember_me">Remember me</label>
                            </div>
                        </div>

                        <div class="login_btn">
                            <button class="ly_btn btn_blue">Secure Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
