<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
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
{{--                            localizataion--}}
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle position-relative noti-dropdown" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-globe fa-fw fa-2x"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <ul class="list-group list">
                                        <li class="list-group-item border-0">
                                            <img src="https://bit.ly/3cCKIAk" alt="" class="rounded-pill w-25 h-25">
                                            <a href="{{route('lang.change','en')}}">English</a>
                                        </li>
                                        <li class="list-group-item border-0">
                                            <img src="https://bit.ly/3OwUSQ2" alt="" class="rounded-pill w-25 h-25">
                                            <a href="{{route('lang.change','mm')}}">Myanmar</a>
                                        </li>
                                        <li class="list-group-item border-0">
                                            <img src="https://bit.ly/3RQoQSd" alt="" class="rounded-pill w-25 h-25 border">
                                            <a href="{{route('lang.change','jpn')}}">Japanese</a>
                                        </li>
{{--                                        <li class="list-group-item border-0">--}}
{{--                                            <a href="{{route('lang.change','fr')}}">French</a>--}}
{{--                                        </li>--}}
{{--                                        <li class="list-group-item border-0">--}}
{{--                                            <a href="{{route('lang.change','sp')}}">Spanish</a>--}}
{{--                                        </li>--}}
                                    </ul>

                                </div>
                            </li>
{{--                        notification--}}
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle position-relative noti-dropdown" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-bell fa-fw fa-2x"></i>
                                    @if(count(auth()->user()->unreadNotifications ) != 0)
                                        <span class="badge bg-primary rounded-circle position-absolute" style="right: 10px;top:3px;z-index: 100;">
                                        {{count(auth()->user()->unreadNotifications)}}
                                        </span>
                                    @endif
                                </a>

                                <div class="dropdown-menu dropdown-menu-end noti-menu py-0" aria-labelledby="navbarDropdown">
                                    <ul class="list-group">
                                        @if(count(auth()->user()->Notifications ) != 0 )
                                        <li class="list-group-item border-0 border-bottom text-end">

                                                <a href="{{route('AllMarkAsRead')}}" class="btn btn-outline-success">
                                                    All MarkAsRead
                                                </a>
                                                <a href="{{route('deleteAllNotification')}}" class="btn btn-danger">
                                                    Delete All Noti
                                                </a>
                                        </li>
                                        @endif

                                        @forelse(auth()->user()->Notifications as $notification)
                                            <li class="list-group-item border-0 border-bottom">
                                                <a href="{{$notification->data['url']}}"  class="{{ $notification->read_at !=  null ? 'text-black-50' : 'text-black' }} text-decoration-none d-flex justify-content-start align-items-center">
                                                    @if($notification->read_at == null)
                                                        <div class="bg-primary rounded-circle p-1 me-3" style="width: 10px;height: 10px;"></div>
                                                    @else
                                                        <div class="bg-light rounded-circle p-1 me-3" style="width: 10px;height: 10px;"></div>
                                                    @endif
                                                    <div class="">
                                                        <strong>
                                                            {{$notification->data['message']}}

                                                        </strong>
                                                        {{strtolower($notification->data['title'])}}
                                                    </div>
{{--                                                        {{$notification->id}}--}}
                                                    <span class="text-nowrap small">
                                                    {{$notification->created_at->shortRelativeDiffForHumans()}}
                                                </span>
                                                </a>
                                                <a href="{{route('markAsRead',$notification->id)}}" class="btn btn-sm btn-outline-success my-2 float-end @if($notification->read_at !=  null) disabled @endif">
                                                    Mark As Read
                                                </a>

                                            </li>
                                        @empty
                                            <li class="list-group-item border-0">
                                                No record Found
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
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
            </div>
        </nav>

        @auth

        <div id="menu-list" class="container py-4" >
           <div class="row">
               <div class="col">
                   <div class="d-flex justify-content-end align-items-center">
{{--                       <!--                             @submit.prevent="search"-->--}}
{{--                       @isset(request()->search)--}}
{{--                           <a href="{{route('contact.index')}}" class="btn btn-outline-primary">--}}
{{--                               All Post--}}
{{--                           </a>--}}
{{--                           <span>Search By : "{{request()->search}}</span>--}}
{{--                       @endisset--}}
                       <form action="{{route('contact.search')}}" method="get">
                           <div class="me-2">
                               <div class="input-group">
                                   <input type="text"  name="search" value="{{request('search')}}" class="form-control border border-primary" placeholder="Search" required>
                                   <button class="btn btn-outline-primary" type="submit">
                                       <i class="fa-solid fa-search"></i>
                                   </button>
                               </div>
                           </div>
                       </form>



                       <a href="{{url('/contact')}}" class="btn btn-outline-primary me-2 {{request()->routeIs('contact.index') ? 'active' : '' }}">
                           <i class="fas fa-list fa-fw"></i>{{__('Contact Lists')}}
                       </a>
                       <a href="{{url('/contact/create')}}" class="btn btn-outline-primary me-2 {{request()->routeIs('contact.create') ? 'active' : '' }}">
                           <i class="fas fa-plus fa-fw"></i>{{__('Create Contact')}}
                       </a>
                       <a href="{{url('/trash/contact')}}" class="btn btn-outline-danger me-2 {{request()->routeIs('trash.contact') ? 'active' : '' }}">
                           <i class="fas fa-trash-can fa-fw"></i>{{__('Go To Trash')}}
                       </a>
                   </div>
               </div>
           </div>
        </div>
        @endauth

        <main class="py-4">
            @yield('content')
        </main>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    @if(session('status'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: '{{session('status')}}'
            })
        </script>
    @endif

@stack('js')
</body>
</html>
