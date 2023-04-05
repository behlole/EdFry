<div class="sidebar capsule--rounded bg_img overlay--dark"
     data-background="{{asset('assets/doctor/images/sidebar/2.jpg')}}">
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
                <li class="sidebar-menu-item {{menuActive('doctor.dashboard')}}">
                    <a href="{{route('doctor.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('doctor.appointments*')}}">
                    <a href="{{route('doctor.appointments.create')}}" class="nav-link ">
                        <i class="menu-icon far fa-handshake"></i>
                        <span class="menu-title">@lang('Create Appointment')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('doctor.appointment*',3)}}">
                        <i class="menu-icon fas fa-handshake"></i>
                        <span class="menu-title">@lang('Appointments') </span>
                        @if(0 < $doctor_new_appointments_count)
                            <span class="menu-badge pill bg--primary ml-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{menuActive('doctor.appointment*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('doctor.appointment')}} ">
                                <a href="{{route('doctor.appointment')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('New')</span>
                                    @if($doctor_new_appointments_count)
                                        <span
                                            class="menu-badge pill bg--primary ml-auto">{{$doctor_new_appointments_count}}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('doctor.appointment.done')}} ">
                                <a href="{{route('doctor.appointment.done')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Done')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('doctor.appointment.trashed')}} ">
                                <a href="{{route('doctor.appointment.trashed')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Trashed')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item {{menuActive('doctor.speciality*')}}">
                    <a href="{{route('doctor.speciality')}}" class="nav-link ">
                        <i class="menu-icon fas fa-user-md"></i><i class=""></i>
                        <span class="menu-title">@lang('Speciality')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('doctor.schedule*')}}">
                    <a href="{{route('doctor.schedule')}}" class="nav-link ">
                        <i class="menu-icon far fa-clock"></i>
                        <span class="menu-title">@lang('Manage Schedule')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('doctor.profile')}}">
                    <a href="{{route('doctor.profile')}}" class="nav-link ">
                        <i class="menu-icon las la-user-circle"></i>
                        <span class="menu-title">@lang('Profile')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('doctor.about*')}}">
                    <a href="{{route('doctor.about')}}" class="nav-link ">
                        <i class="menu-icon fas fa-info-circle"></i>
                        <span class="menu-title">@lang('About You')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('doctor.education*')}}">
                    <a href="{{route('doctor.education')}}" class="nav-link ">
                        <i class="menu-icon fas fa-school"></i>
                        <span class="menu-title">@lang('Education')</span>
                    </a>
                </li>
                </li>

                <li class="sidebar-menu-item {{menuActive('doctor.experience*')}}">
                    <a href="{{route('doctor.experience')}}" class="nav-link ">
                        <i class="menu-icon fas fa-stethoscope"></i>
                        <span class="menu-title">@lang('Experiences')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('doctor.twofactor*')}}">
                    <a href="{{ route('doctor.twofactor') }}" class="nav-link ">
                        <i class="menu-icon fas fa-shield-alt"></i>
                        <span class="menu-title">@lang('2FA Security')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('doctor.social.icon*')}}">
                    <a href="{{route('doctor.social.icon')}}" class="nav-link ">
                        <i class="menu-icon fas fa-people-arrows"></i>
                        <span class="menu-title">@lang('Social Icon')</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
