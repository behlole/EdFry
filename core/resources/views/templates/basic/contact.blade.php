@extends($activeTemplate.'layouts.frontend')

@php
    $contact_us_content = getContent('contact_us.content',true);
    $contact_us_element = getContent('contact_us.element',false);
@endphp

@section('content')
@include($activeTemplate.'partials.breadcrumb')

    <!-- contact-item-section start -->
    <section class="contact-item-section pd-t-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="contact-form-area">
                        <div class="row ml-b-30">
                            @foreach($contact_us_element as $item)
                                <div class="col-lg-4 col-md-6 col-sm-8 mrb-30">
                                    <div class="contact-item d-flex flex-wrap align-items-center">
                                        <div class="contact-item-icon">
                                            @php echo @$item->data_values->icon @endphp
                                        </div>
                                        <div class="contact-item-details">
                                            <h5 class="title">{{ __(@$item->data_values->heading) }}</h5>
                                            <ul class="contact-item-list">
                                                <li>{{ __(@$item->data_values->details) }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                           @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- contact-item-section end -->


    <!-- map-section start -->
    <section class="map-section pd-t-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="map-area">
                        <div class="row justify-content-center ml-b-30">
                            <div class="col-lg-12 mrb-30">
                                <div class="maps"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- map-section end -->


<!-- contact-section start -->
<section class="contact-section ptb-80">
    <div class="container">
        <div class="row justify-content-center mrb-40">
            <div class="col-lg-12">
                <div class="contact-form-area">
                    <div class="section-header">
                        <h2 class="section-title">{{ __($contact_us_content->data_values->heading) }}</h2>
                        <p class="m-0">{{ __($contact_us_content->data_values->details) }}</p>
                    </div>
                    <form class="contact-form" action="" method="POST">
                        @csrf
                        <div class="row justify-content-center ml-b-20">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="name" placeholder="@lang('Your Name')" value="{{old('name')}}" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" placeholder="@lang('Your Email')" value="{{old('email')}}" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="subject" placeholder="@lang('Subject')" value="{{old('subject')}}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <textarea placeholder="@lang('Your Message')" name="message">{{old('message')}}</textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="submit-btn">@lang('Send Message')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- contact-section end -->
@endsection

@push('script')
    <!-- main -->
    <script src="https://maps.google.com/maps/api/js?key={{$contact_us_content->data_values->google_map_key}}"></script>
    <script src="{{asset($activeTemplateTrue.'/js/map.js')}}"></script>
    <script>

    (function ($) {
        'use strict';
        var mapOptions = {
        center: new google.maps.LatLng({{$contact_us_content->data_values->latitude}}, {{$contact_us_content->data_values->longitude}}),
        zoom: 12,
        scrollwheel: true,
        backgroundColor: 'transparent',
        mapTypeControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
        var map = new google.maps.Map(document.getElementsByClassName("maps")[0],
            mapOptions);
        var myLatlng = new google.maps.LatLng({{$contact_us_content->data_values->latitude}}, {{$contact_us_content->data_values->longitude}});
        var focusplace = {lat: {{$contact_us_content->data_values->latitude}} , lng: {{$contact_us_content->data_values->longitude}} };
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
        })
    })(jQuery);
    </script>
@endpush
