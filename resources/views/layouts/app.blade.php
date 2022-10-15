<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="container">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="row">
                <div class="col-12">
                    {{ $header ?? '' }}
                </div>
            </header>

            <!-- Page Content -->
            <main class="row">
                {{ $slot ?? '' }}
            </main>
            <div class="row">
                @yield('content')
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
        @livewireScripts
        @stack('scripts')
        <script type="text/javascript">
            window.addEventListener('closeModal', event => {
                $(".modal").modal('hide');
                $(".modal-backdrop").modal('hide');
            });

            window.addEventListener('openDeleteModal', event => {
                $("#deleteModal").modal('show');
            });

            window.addEventListener('closeDeleteModal', event => {
                $("#deleteModal").modal('hide');
                $(".modal-backdrop").modal('hide');
            });

            window.addEventListener('showFlashMessage', event => {
                if(event.detail.status) {
                    $('.task-success').text(event.detail.message);
                    $('.task-success').stop().fadeIn(400).delay(3000).fadeOut(400); //fade out after 3 seconds
                } else {
                    $('.task-error').text(event.detail.message);
                    $('.task-error').stop().fadeIn(400).delay(3000).fadeOut(400); //fade out after 3 seconds
                }
            });
        </script>
    </body>
</html>
