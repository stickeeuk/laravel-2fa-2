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
    <body id="laravel-2fa-register">
        <div class="flex-center position-ref full-height">
            <div class="content">
                <h1>{{ __('laravel-2fa::messages.register.heading') }}</h1>
                <p>{{ __('laravel-2fa::messages.register.intro') }}</p>

                @if ($recoveryCodes)
                    <p>{{ __('laravel-2fa::messages.register.recovery') }}</p>
                    <ul>
                        @foreach ($recoveryCodes as $recoveryCode)
                        <li>{{ $recoveryCode }}</li>
                        @endforeach
                    </ul>
                    <p>{{ __('laravel-2fa::messages.register.recovery-after') }}</p>
                @endif

                @if (count($drivers) > 1)
                    <p>{{ __('laravel-2fa::messages.register.choose') }}</p>
                    <ul>
                        @foreach ($drivers as $driverName => $driver)
                            <li>
                                <a href="{{ route('laravel-2fa.' . $driver . '.register') }}">
                                    {{ __('laravel-2fa::' . $driver . '.name') }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    @foreach ($drivers as $driverName => $driver)
                        <a href="{{ route('laravel-2fa.' . $driver . '.register') }}">
                            {{ __('laravel-2fa::messages.register.register-with', ['name' => __('laravel-2fa::' . $driver . '.name')]) }}
                        </a>
                    @endforeach
                @endif

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
