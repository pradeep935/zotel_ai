<header>
    <div class="container-fluid">

        <nav class="navbar navbar-expand-lg px-5">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ url('/assets/images/nav_logo.png') }}" alt="logo" style="height: 80px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ $sidebar == 'home' ? 'active' : '' }}"
                                href="{{ url('/home') }}">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ $sidebar == 'projects' ? 'active' : '' }}"
                                href="{{ url('/projects') }}">Projects</a>
                        </li>
                        

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ $sidebar == 'Speaker' ? 'active' : '' }}"
                                href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Speaker Opportunities
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ url('contacts/3') }}">Event And awards</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ url('contacts/5') }}">Podcast</a></li>
                            </ul>
                        </li>
                            
                        <li class="nav-item">
                            <a class="nav-link {{ $sidebar == 'resource' ? 'active' : '' }}"
                                href="{{ url('/resources') }}" role="button">
                                Resources
                            </a>
                        </li>
                        @if (App\Models\User::checkPermission(4))
                            <li class="nav-item dropdown">
                                <a href="#"
                                    class="nav-link dropdown-toggle {{ $sidebar == 'campaign' ? 'active' : '' }}"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">Campaigns</a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/campaign/1') }}" class="dropdown-item">Active Campaigns</a>
                                    </li>
                                    <li><a href="{{ url('/campaign/0') }}" class="dropdown-item">Archive Campaigns</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                    <div class="d-flex text-end">

                        <div class="autocomplete">
                            <i class="fa fa-search"></i>
                            <input class="form-control" placeholder="Search contact....." aria-label="Search"
                                id="autocomplete-input" autocomplete="off">
                            <ul class="autocomplete-list">
                            </ul>
                        </div>

                        <div class="user-name">
                            <b>{{ Auth::user()->name }}</b>
                        </div>

                        <div class="dropdown">

                            <a class="dropdown-toggle user-image" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                @if (Auth::user()->profile_pic)
                                    <img src="{{ url(Auth::user()->profile_pic) }}" />
                                @else
                                    <img src="{{ url('assets/images/userIcon.png') }}" />
                                @endif
                            </a>

                            <ul class="dropdown-menu dropdown-menu-lg-end">

                                @if (App\Models\User::checkPermission(2))
                                    <li><a class="dropdown-item" href="{{ url('/updates') }}">Updates</a></li>
                                @endif

                                @if (App\Models\User::checkPermission(1))
                                    <li><a class="dropdown-item" href="{{ url('/users-Management') }}">Users
                                            Management</a></li>

                                    <li><a class="dropdown-item" href="{{ url('/permission') }}">Permissions</a></li>
                                @endif

                                <li><a class="dropdown-item" href="{{ url('/resources/my-resources') }}">My Resources</a></li>

                                <li><a class="dropdown-item" href="{{ url('/manage-profile') }}">My Profile</a></li>
                                <li><a class="dropdown-item" href="{{ url('/logout') }}">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
