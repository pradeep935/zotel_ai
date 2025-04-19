@if(Auth::check())
    <div class="top-menu">
        <div class="row">
            <div class="col-sm-8 ">
                <ul class="main-menu">

                </ul>
            </div>
            <div class="col-sm-4" style="text-align: right;">
                <div class="welcome-nav">
                    <span class="name">
                        {{ Auth::user()->name }}
                    </span>
                    <div class="menu">
                        <ul>
                            <li>
                                <a href="{{url('/update-password')}}"><i class="icons icon-lock-open"></i> <span>Change Password</span></a>
                            </li>

                            <li>
                                <a href="{{url('/logout')}}"><i class="icons icon-logout"></i> <span>Logout</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif