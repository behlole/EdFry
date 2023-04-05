<div class="sidebar capsule--rounded bg_img overlay--dark"
     data-background="{{asset('assets/assistant/images/sidebar/2.jpg')}}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="/" class="sidebar__main-logo"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('image')"></a>
            <a href="/" class="sidebar__logo-shape"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" alt="@lang('image')"></a>
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{menuActive('user.dashboard')}}">
                    <a href="{{route('user.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>

                <!--<li class="sidebar-menu-item {{menuActive('user.doctors')}}">-->
                <!--    <a href="{{route('user.doctors')}}" class="nav-link ">-->
                <!--        <i class="menu-icon fas fa-user-md"></i>-->
                <!--        <span class="menu-title">@lang('All Doctors')</span>-->
                <!--    </a>-->
                <!--</li>-->
                 <li class="sidebar-menu-item {{menuActive('user.appointments*')}}">
                    <a href="{{route('user.appointments.all')}}" class="nav-link ">
                        <i class="menu-icon far fa-handshake"></i>
                        <span class="menu-title">@lang('All Appointment')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{menuActive('user.profile')}}">
                    <a href="{{route('user.profile')}}" class="nav-link ">
                        <i class="menu-icon las la-user-circle"></i>
                        <span class="menu-title">@lang('Profile')</span>
                    </a>
                </li>

                <!--<li class="sidebar-menu-item {{menuActive('user.twofactor*')}}">-->
                <!--    <a href="{{ route('user.twofactor') }}" class="nav-link ">-->
                <!--        <i class="menu-icon fas fa-shield-alt"></i>-->
                <!--        <span class="menu-title">@lang('2FA Security')</span>-->
                <!--    </a>-->
                <!--</li>-->
            </ul>
        </div>
    </div>
</div>
