<div class="sidebar capsule--rounded bg_img overlay--dark"
     data-background="{{asset('assets/staff/images/sidebar/2.jpg')}}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{route('assistant.dashboard')}}" class="sidebar__main-logo"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('image')"></a>
            <a href="{{route('assistant.dashboard')}}" class="sidebar__logo-shape"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" alt="@lang('image')"></a>
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">

                <li class="sidebar-menu-item {{menuActive('staff.dashboard')}}">
                    <a href="{{route('staff.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('staff.appointment.create')}}">
                    <a href="{{route('staff.appointment.create')}}" class="nav-link ">
                        <i class="menu-icon far fa-handshake"></i>
                        <span class="menu-title">@lang('Create Appointment')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('staff.appointments.all')}}">
                    <a href="{{route('staff.appointments.all')}}" class="nav-link ">
                        <i class="menu-icon fas fa-handshake"></i>
                        <span class="menu-title">@lang('New Appointment')</span>
                        @if($staff_new_appointments_count)
                            <span
                                class="menu-badge pill bg--primary ml-auto">{{$staff_new_appointments_count}}</span>
                        @endif
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('staff.done.appointment')}}">
                    <a href="{{route('staff.done.appointment')}}" class="nav-link ">
                        <i class="menu-icon fas fa-check-square"></i>
                        <span class="menu-title">@lang('Appointment Done')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('staff.appointments.trashed')}}">
                    <a href="{{route('staff.appointments.trashed')}}" class="nav-link ">
                        <i class="menu-icon la la-trash"></i>
                        <span class="menu-title">@lang('Trashed')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('staff.profile')}}">
                    <a href="{{route('staff.profile')}}" class="nav-link ">
                        <i class="menu-icon las la-user-circle"></i>
                        <span class="menu-title">@lang('Profile')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('staff.twofactor*')}}">
                    <a href="{{ route('staff.twofactor') }}" class="nav-link ">
                        <i class="menu-icon fas fa-shield-alt"></i>
                        <span class="menu-title">@lang('2FA Security')</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
