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

            #laravel-2fa-cancel-form {
                margin-top: 1em;
            }
        </style>
    </head>
    <body id="laravel-2fa-authenticate">
        <div class="flex-center position-ref full-height">
            <div class="content">
                <form method="POST" action="{{ route('laravel-2fa.do-authentication') }}" id="laravel-2fa-auth-form">
                    @csrf
                    <h1>{{ __('laravel-2fa::messages.authenticate.heading') }}</h1>
                    <p><strong>{{ __('laravel-2fa::messages.authenticate.heading') }}</strong></p>
                    @if ($errors->has('laravel_2fa_code'))
                        <p class="text-center font-semibold text-danger my-3">
                            {{ $errors->first('laravel_2fa_code') }}
                        </p>
                    @endif
                    <label for="laravel_2fa_code">{{ __('laravel-2fa::messages.authenticate.code') }}</label>
                    <input id="laravel_2fa_code" type="text" name="laravel_2fa_code" autofocus>
                    <button type="submit">
                        {{ __('laravel-2fa::messages.authenticate.submit') }}
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
