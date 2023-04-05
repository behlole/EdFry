<!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $general->sitename(__($page_title) ?? '') }}</title>
    <!-- site favicon -->
    <link rel="shortcut icon" type="image/png" href="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}">
    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- fontawesome css link -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/fontawesome-all.min.css')}}">
    <!-- line-awesome webfont -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/line-awesome.min.css')}}">
    <!-- nice-select css -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/nice-select.css')}}">
    <!-- bootstrap css link -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/bootstrap.min.css')}}">
    <!-- swipper css link -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/swiper.min.css')}}">
    <!-- chosen css -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/chosen.css')}}">
    <!-- icon css -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/themify.css')}}">
    <!-- animate.css -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/animate.css')}}">
    <!-- main style css link -->
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/style.css')}}">
    <!-- site color -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue."css/color.php?color1=$general->base_color") }}">

    @include('partials.seo')
    @stack('style-lib')

    @stack('style')
</head>
<body>

<div class="loader">
    <div class="heartbeatloader">
        <svg class="svgdraw" width="100%" height="100%" viewBox="0 0 150 400">
            <path class="path" d="M 0 200 l 40 0 l 5 -40 l 5 40 l 10 0 l 5 15 l 10 -140 l 10 220 l 5 -95 l 10 0 l 5 20 l 5 -20 l 30 0" fill="transparent" stroke-width="4" stroke="black"/>
        </svg>
        <div class="innercircle"></div>
        <div class="outercircle"></div>
    </div>
</div>

@include($activeTemplate.'partials.header')
<a href="#" class="scrollToTop"><i class="fa fa-angle-up"></i></a>
    <div class="all-sections">
        @yield('content')
    </div>
@include($activeTemplate.'partials.footer')


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->

<!-- jquery -->
<script src="{{asset($activeTemplateTrue.'/js/jquery-3.3.1.min.js')}}"></script>
<!-- migarate-jquery -->
<script src="{{asset($activeTemplateTrue.'/js/jquery-migrate-3.0.0.js')}}"></script>
<!-- bootstrap js -->
<script src="{{asset($activeTemplateTrue.'/js/bootstrap.min.js')}}"></script>
<!-- nice-select js-->
<script src="{{asset($activeTemplateTrue.'/js/jquery.nice-select.js')}}"></script>
<!-- chosen js -->
<script src="{{asset($activeTemplateTrue.'/js/chosen.jquery.js')}}"></script>
<!-- swipper js -->
<script src="{{asset($activeTemplateTrue.'/js/swiper.min.js')}}"></script>
<!-- wow js file -->
<script src="{{asset($activeTemplateTrue.'/js/wow.min.js')}}"></script>
<!-- main -->
<script src="{{asset($activeTemplateTrue.'/js/main.js')}}"></script>

@stack('script-lib')

@stack('script')

@include('partials.plugins')

@include(activeTemplate().'partials.notify')


<script>
    (function ($) {
        "use strict";
        $(document).on("change", ".langSel", function() {
            window.location.href = "{{url('/')}}/change/"+$(this).val() ;
        });
    })(jQuery);
</script>

</body>
</html>
