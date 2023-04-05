<!-- navbar-wrapper start -->
<nav class="navbar-wrapper">
    <form class="navbar-search" onsubmit="return false;">
        <button type="submit" class="navbar-search__btn">
            <i class="las la-search"></i>
        </button>
        <input type="search" name="navbar-search__field" id="navbar-search__field"
               placeholder="@lang('Search...')">
        <button type="button" class="navbar-search__close"><i class="las la-times"></i></button>

        <div id="navbar_search_result_area">
            <ul class="navbar_search_result"></ul>
        </div>
    </form>

    <div class="navbar__left">
        <button class="res-sidebar-open-btn"><i class="las la-bars"></i></button>
        <!--<button type="button" class="fullscreen-btn">-->
        <!--    <i class="fullscreen-open las la-compress" onclick="openFullscreen();"></i>-->
        <!--    <i class="fullscreen-close las la-compress-arrows-alt" onclick="closeFullscreen();"></i>-->
        <!--</button>-->
        <a class="fullscreen-btn" style="margin:auto;" target="_blank" href="/" title="Website"><i class="las la-home"></i></button>

    </div>

    <div class="navbar__right">
        <ul class="navbar__action-list">
            <li>
                <button type="button" class="navbar-search__btn-open">
                    <i class="las la-search"></i>
                </button>
            </li>


            <li class="dropdown"  data-toggle="dropdown">
                <button type="button" class="" data-display="static" aria-haspopup="true"
                        aria-expanded="false">


	                 <span class="navbar-user">
	            <span class="navbar-user__thumb"><img
	                    src="{{ getImage(imagePath()['assistant']['path'].'/'. auth()->guard('user')->user()->image,imagePath()['assistant']['size']) }}"
	                    alt="@lang('image')"></span>
	
	            <span class="navbar-user__info">
	              <span class="navbar-user__name">{{auth()->guard('user')->user()->username}}</span>
	            </span>
	            <span class="icon"><i class="las la-chevron-circle-down"></i></span>
	          </span>
                </button>
                <div class="dropdown-menu dropdown-menu--sm p-0 border-0 box--shadow1 dropdown-menu-right">
                    @if(Route::has('user.profile'))
                        <a  href="{{ route('user.profile') }}"
                            onclick='window.open("{{route('user.profile')}}","_self")'
                           class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                            <i class="dropdown-menu__icon las la-user-circle"></i>
                            <span class="dropdown-menu__caption">@lang('Profile')</span>
                        </a>
                    @endif

                    @if(Route::has('user.password'))
                        <a href="{{route('user.password')}}"
                          onclick='window.open("{{route('user.password')}}","_self")'
                           class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                            <i class="dropdown-menu__icon las la-key"></i>
                            <span  class="dropdown-menu__caption" >@lang('Password')</span>
                        </a>
                    @endif


                    @if(Route::has('user.logout'))
                        <a href="{{ route('user.logout') }}"
                          onclick='window.open("{{route('user.logout')}}","_self")'
                           class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                            <i class="dropdown-menu__icon las la-sign-out-alt"></i>
                            <span class="dropdown-menu__caption">@lang('Logout')</span>
                        </a>
                    @endif
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- navbar-wrapper end -->
