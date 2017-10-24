<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a href="{{ url('/') }}" class="navbar-brand">
                {{ config('app.name') }}
            </a>
        </div>

        <div class="collapse navbar-collaspse" id="app-navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/') }}">{{ lang('Articles') }}</a></li>
                <li><a href="{{ url('discussion') }}">{{ lang('Disscussions') }}</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <form action="{{ url('search') }}" class="navbar-form navbar-right search" role="search" method="get">
                        <input type="text" name="q" class="form-control" placeholder="{{ lang('Search') }}" required>
                    </form>
                </li>

                @if (Auth::guest())
                    <li><a href="{{ url('login') }}">{{ lang('Login') }}</a></li>
                    <li><a href="{{ url('register') }}">{{ lang('Register') }}</a></li>
                @else
                    <li class="notification">
                        <a href="{{ url('user/notification') }}"><i class="ion-android-notifications">
                            <span class="new"
                            @if(Auth::user()->unreadNotification->count() > 0)
                            style="display: block;"
                            @endif
                            >
                            </span>
                        </i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ Auth::user()->nickname ?: Auth::user()->name }}
                            <b class="caret"></b>
                            <img src="{{ Auth::user()->avatar }}" class="avatar img-circle">
                        </a>

                        <ul class="dropdown-menu text-center" role="menu">
                            <li><a href="{{ url('user', ['name' => Auth::user()->name]) }}"><i class="ion-person"></i>{{ lang('Persiona Center') }}</a></li>
                            <li><a href="{{ url('setting') }}"><i class="ion-gear-b"></i>{{ lang('Settings') }}</a></li>
                            @if(Auth::user()->is_admin)
                                <li><a href="{{ url('dashboard') }}"><i class="ion-ios-speedometer"></i>{{ lang('Dashboard') }}</a></li>
                            @endif
                            <li class="divider"></li>
                            <li>
                                <a href="{{ url('logout') }}"
                                   onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit()">
                                    <i class="ion-log-out"></i>
                                    {{ lang('Logout') }}
                                </a>
                                <form action="{{ url('logout') }}" method="post" id="logout-form" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>