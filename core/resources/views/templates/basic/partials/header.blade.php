<!-- header-section start -->
<header class="header-section header-section-two">
    <div class="header">
        <div class="header-bottom-area">
            <div class="container-fluid">
                <div class="header-menu-content">
                    <nav class="navbar navbar-expand-lg p-0" >
                        <a class="site-logo site-title" href="{{ route('home') }}"><img
                                src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}"
                                alt="@lang('site-logo')"></a>
                        <div class="language-select d-block d-lg-none ml-auto">
                            <select class="nice-select langSel language-select">
                                @foreach($language as $item)
                                    <option value="{{ __($item->code) }}"
                                            @if(session('lang') == $item->code) selected @endif>{{ __($item->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse"
                                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fas fa-bars"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav main-menu ml-auto mr-auto">
                                <li class="{{menuActive('home')}}"><a href="{{ route('home') }}">@lang('Home')</a></li>
                                <li class="{{menuActive('doctors.all')}}"><a
                                        href="{{ route('doctors.all') }}">@lang('Mentors')</a></li>
                                @foreach($pages as $k => $data)
                                    <li class=" @if(url()->current() == route('pages',[$data->slug])) active @endif">
                                        <a href="{{route('pages',[$data->slug])}}">{{trans($data->name)}}</a>
                                    </li>
                                @endforeach
                                <li class="{{menuActive('blog')}}"><a href="{{ route('blog') }}">@lang('Blog')</a></li>
                                <li class="{{menuActive('contact')}}"><a
                                        href="{{ route('contact') }}">@lang('Contact')</a></li>
                            </ul>
                            <!--<div class="language-select d-none d-lg-block">-->
                            <!--    <select class="nice-select langSel language-select">-->
                            <!--        @foreach($language as $item)-->
                            <!--            <option value="{{ __($item->code) }}"-->
                            <!--                    @if(session('lang') == $item->code) selected @endif>{{ __($item->name) }}</option>-->
                            <!--        @endforeach-->
                            <!--    </select>-->
                            <!--</div>-->
                            <div class="header-bottom-action">
                                @auth('user')
                                    <a href="{{ route('doctors.all') }}" class="cmn-btn">@lang('Book Now')</a>
                                    <a href="{{ route('user.logout') }}" class="cmn-btn">@lang('Logout')</a>
                                    <a href="{{ route('user.dashboard') }}" class="cmn-btn">@lang('Dashboard')</a>
                                    
                                @else
                                    <a href="{{ route('register') }}" class="cmn-btn">@lang('Register')</a>
                                    <a href="{{ route('login') }}" class="cmn-btn">@lang('Login Now')</a>
                                @endauth
                            </div>
{{--                            <div class="header-bottom-action">--}}
{{--                                <a href="{{ route('login') }}" class="cmn-btn">@lang('Login Now')</a>--}}
{{--                            </div>--}}
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header-section end -->
