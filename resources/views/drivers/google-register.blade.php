<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                min-height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
                width: 80%;
                max-width: 600px;
                margin: auto;
            }

            .content ul,
            .content ol {
                list-style-type: none;
                margin: 0;
                padding: 0;
            }

            .error {
                color: #cc0000;
                font-weight: bold;
            }

            #laravel-2fa-cancel-form {
                margin: 1em 0;
            }
        </style>
    </head>
    <body id="laravel-2fa-google-register">
        <div class="flex-center position-ref full-height">
            <div class="content">
                <h1>{{ __('laravel-2fa::google.register.heading') }}</h1>

                @lang('laravel-2fa::google.register.instructions-before')

                <div class="qr-code">
                    {!! $qrCode !!}
                </div>

                <div class="secret">
                    {!! $secret !!}
                </div>

                @lang('laravel-2fa::google.register.instructions-after')

                <form method="POST" action="{{ route('laravel-2fa.' . $driverName . '.confirm') }}">
                    @csrf

                    @if ($errors->has('code'))
                        <p class="error">
                            {{ $errors->first('code') }}
                        </p>
                    @endif

                    <label for="google-code">{{ __('laravel-2fa::google.register.code') }}</label>
                    <input id="google-code" type="number" name="code" required autofocus>
                    <button type="submit">
                        {{ __('laravel-2fa::messages.confirm') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('laravel-2fa.logout') }}" id="laravel-2fa-cancel-form">
                    @csrf
                    <button type="submit">
                        {{ __('laravel-2fa::messages.cancel') }}
                    </button>
                </form>
            </div>
        </div>
    </body>
</html>
