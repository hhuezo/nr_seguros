<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ingresar</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('dentco-html/vendor/slick/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('dentco-html/vendor/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dentco-html/icons/style.css') }}" rel="stylesheet">
    <link href="{{ asset('dentco-html/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="{{ asset('dentco-html/css/style.css') }}" rel="stylesheet">
    <!--Favicon-->
    <link rel="icon" href="{{ asset('dentco-html/images/LOGO.png') }}" type="image/x-icon">
    <!-- Font Awesome -->
    <link href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <link href='https://css.gg/awards.css' rel='stylesheet'>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <!-- Google map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCiFdr5Z0WRIXKUOqoRRvzRQ5SkzhkUVjk"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>












    <!-- Scripts
        <script src="{{ asset('js/app.js') }}" defer></script>-->


</head>

<body style="background-image:url('dentco-html/images/LOGO_app.png'); background-repeat: no-repeat; background-size: 30% ; background-position-x:right ; background-position-y:bottom ;">
    <div id="app">
        <div class="container">
            <div class="row align-items-lg-center">
                <button class="navbar-toggler collapsed" data-toggle="collapse" data-target="#navbarNavDropdown">
                    <span class="icon-menu"></span>
                </button>
                <div class="col-lg-auto col-lg-2 d-flex align-items-lg-center">
                    <a href="{{ url('/') }}" class="header-logo"><img src="{{ asset('dentco-html/images/LOGOnr.png') }}" alt="" class="img-fluid" width="75%"></a>
                </div>
                <div class="col-lg ml-auto header-nav-wrap">
                    <div class="header-nav js-header-nav">
                        <nav class="navbar navbar-expand-lg btco-hover-menu">
                            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                                <ul class="navbar-nav">

                                    <!-- Authentication Links -->
                                    @guest
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li> -->
                                    @if (Route::has('register'))
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" href="{{ url('seguridad/user/create') }}">{{ __('Register') }}</a>
                                    </li> -->
                                    @endif
                                    @else
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            {{ Auth::user()->name }}
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </li>
                                    @endguest
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>


            <p style="background-color: #2F609E; height: 4px;"> &nbsp;</p>


            <main class="py-4">

                @yield('content')
            </main>
        </div>
</body>

</html>