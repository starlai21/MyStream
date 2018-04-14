<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Stream</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="/css/app.css">
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <style>
            [v-cloak]{display: none}

            .body {
              min-height: calc(100vh - 70px);
              padding: 40px 40px 0 20px;
            }
            .footer {
              height: 20px;
            }
            .fade-enter-active,
            .fade-leave-active {
                transition: opacity .4s
            }
            .fade-enter,
            .fade-leave {
                opacity: 0
            }


        </style>

    </head>
    <body>
        
        @yield('body')
        <script src="/js/app.js"></script>
    </body>
</html>
