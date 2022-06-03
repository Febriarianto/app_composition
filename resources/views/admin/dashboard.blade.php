<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="{{ asset ('asset/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset ('plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset ('plugins/toastr/toastr.min.css')}}">
    @yield('css')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm py-3 mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-layer-group"></i> App-Composition
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                    @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @endif

                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                    @endif
                    @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid pb-3 flex-grow-1 d-flex flex-column flex-xm-row overflow-auto">
        <div class="row flex-grow-sm-1 flex-grow-0">
            <aside class="col-sm-2 flex-grow-sm-1 flex-shrink-1 flex-grow-0 sticky-top pb-sm-0 pb-3">
                <div class="list-group navbarSupportedContent">
                    <a href="/feature" class="list-group-item list-group-item-action"><i class="fas fa-cubes"></i> Features</a>
                    <a href="/module" class="list-group-item list-group-item-action"> <i class="fas fa-box-open"></i> Modules</a>
                    <a href="/app" class="list-group-item list-group-item-action"><i class="fas fa-box"></i> Application</a>
                </div>
            </aside>
            <main class="col overflow-auto h-100">

                @yield('content')

            </main>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset ('asset/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset ('plugins/toastr/toastr.min.js')}}"></script>
    @yield('script')
    <script>
        $(document).ready(function() {
            toastr.options.timeOut = 10000;
            @if(Session::has('warning'))
            toastr.info("{{ Session::get('warning') }}");
            @elseif(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
            @endif
        });
    </script>
</body>

</html>