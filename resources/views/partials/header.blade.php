<header class="app-header navbar">
    <nav class="navbar navbar-expand-md bg-secondary fixed-top navbar-dark">
        <div class="container">  
            <a class="navbar-brand" href="/" title="{{ Config::get('app.name') }}">
                <i class="icon-screen-desktop"></i>
                <b>{{ Config::get('app.name') }}</b>
            </a>
            @if (Auth::check())
                @if (Auth::user()->hasPermissionTo('access admin cp', 'web'))
                    @if (Request::is('admin-cp'))
                        <slideout-menu class="ml-2"></slideout-menu>
                    @else
                        <a href="{{ route('admin-cp') }}" class="admin-trigger ml-2"><i class="icon-home"></i></a>
                    @endif
                @endif
            @endif
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar-collapse-content" aria-controls="navbar-collapse-content" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span> 
            </button>
            <div class="collapse navbar-collapse text-center justify-content-end" id="navbar-collapse-content">
                <ul class="navbar-nav ml-auto white">
                    @guest
                        <li><a class="nav-link" href="{{ route('login') }}">{{ Lang::get('auth.login') }}</a></li>
                        <li><a class="nav-link" href="{{ route('register') }}">{{ Lang::get('auth.register') }}</a></li>
                    @else
                        <li class="nav-item dropdown">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if (Auth::user()->avatar)
                                    <img id="small-profile-image" src="/storage/uploads/avatars/{{ Auth::user()->avatar }}" class="img-avatar small" alt="{{ Auth::user()->name }}">
                                @else
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                @endif
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="icon icon-user"></i> {{ Lang::get('general.profile') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                    <i class="icon icon-logout"></i> {{ Lang::get('auth.logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>   
            </div>
        </div>
    </nav>
</header>