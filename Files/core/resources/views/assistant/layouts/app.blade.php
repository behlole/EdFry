@extends('assistant.layouts.master')

@section('content')
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        @include('assistant.partials.sidenav')
        @include('assistant.partials.topnav')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                @include('assistant.partials.breadcrumb')

                @yield('panel')

            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>

@endsection
