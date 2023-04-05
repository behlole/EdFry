@extends($activeTemplate.'layouts.frontend')
@section('content')

@php echo fbcomment() @endphp
<!-- booking-section start -->
@include($activeTemplate.'partials.breadcrumb')
<section class="booking-section booking-section-two pd-t-80 pd-b-40 ">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="booking-item d-flex flex-wrap align-items-center justify-content-between mb-5">
                    <div class="booking-left d-flex align-items-center">
                        <div class="booking-thumb">
                            <img src="{{getImage(imagePath()['doctor']['path'].'/'.$doctor->image,imagePath()['doctor']['size'])}}" alt="@lang('doctor')">
                            <a href="#0" class="fav-btn"><i class="far fa-bookmark"></i></a>
                        </div>
                        <div class="booking-content">
                            <span class="sub-title"><a href="#0">{{ __($doctor->sector->name) }}</a></span>
                            <h5 class="title">{{ __($doctor->name) }} <i class="fas fa-check-circle"></i></h5>
                            <p>{{ __($doctor->qualification) }}</p>
                            <div class="booking-ratings">
                                @for ($i = 0; $i < $doctor->rating; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                            </div>
                            <ul class="booking-list">
                                <li><i class="icon-direction-alt"></i>{{ __($doctor->location->name) }}</li>
                                <li><i class="las la-phone"></i> {{ __($doctor->mobile) }}</li>
                            </ul>
                            @if ($doctor->speciality != null || !empty($doctor->speciality))
                                <div class="booking-btn">
                                    @foreach ($doctor->speciality as $item)
                                        <a href="#0" class="border-btn">{{ __($item) }}</a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="booking-right">
                        <div class="booking-content">
                            <ul class="booking-list">
                                <li><i class="fas fa-hourglass-start"></i>@lang('Joined us') :</li>
                                <li><i class="fas fa-stethoscope"></i>{{ diffForHumans($doctor->created_at) }}</li>
                                <li><span><i class="fas fa-wallet"></i>@lang('Fees') :  {{ __($doctor->fees) }} {{__($general->cur_sym)}}<span></li>
                            </ul>
                            <ul class="booking-tag">
                                @foreach ($doctor->socialIcons as $item)
                                    <li><a href="{{ $item->url }}" target="_blank">@php echo $item->icon @endphp</a></li>
                                @endforeach
                            </ul>
                            <div class="booking-btn">
                                @if($doctor->serial_day > 0 && $doctor->serial_or_slot != null)
                                    <a href="#0" class="border-btn active">@lang('Appointment Available')</a>
                                @else
                                    <a href="#0" class="border-btn active">@lang('Appointment Unavailable')</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- booking-section end -->

<!-- overview-section start -->
<section class="overview-section pd-b-80">
    <div class="container">
        <div class="overview-area mrb-40">
            <div class="row">
                <div class="col-lg-12">
                    <div class="overview-tab-wrapper">
                        <ul class="tab-menu">
                            <li>@lang('Overview')</li>
                            <li class="active">@lang('Booking')</li>
                            <li>@lang('Review')</li>
                        </ul>
                        <div class="tab-cont">
                            <div class="tab-item">
                                <div class="overview-tab-content ml-b-30">
                                    <div class="overview-content">
                                        <h5 class="title">@lang('About Me')</h5>
                                        <p>{{ __($doctor->about) }}</p>
                                    </div>
                                    <div class="overview-content">
                                        <h5 class="title">@lang('Education')</h5>
                                        <div class="overview-box">
                                            <ul class="overview-list">
                                                @foreach($doctor->educationDetails as $item)
                                                    <li>
                                                        <div class="overview-user">
                                                            <div class="before-circle"></div>
                                                        </div>
                                                        <div class="overview-details">
                                                            <h6 class="title">{{ __($item->institution) }}</h6>
                                                            <div>{{ __($item->discipline) }}</div>
                                                            <span>{{ __($item->period) }}</span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="overview-content">
                                        <h5 class="title">@lang('Work & Experience')</h5>
                                        <div class="overview-box">
                                            <ul class="overview-list">
                                                @foreach($doctor->experienceDetails as $item)
                                                    <li>
                                                        <div class="overview-user">
                                                            <div class="before-circle"></div>
                                                        </div>
                                                        <div class="overview-details">
                                                            <h6 class="title">{{ __($item->institution) }}</h6>
                                                            <div>{{ __($item->discipline) }}</div>
                                                            <span>{{ __($item->period) }}</span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="overview-content">
                                        <h5 class="title">@lang('Specializations')</h5>
                                        <div class="overview-footer-area d-flex flex-wrap justify-content-between">
                                            <ul class="overview-footer-list">
                                                @if($doctor->speciality != null || !empty($doctor->speciality))
                                                    @foreach ($doctor->speciality as $item)
                                                        <li><i class="fas fa-long-arrow-alt-right"></i>{{ __($item) }}</li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-item">
                                <div class="overview-tab-content">
                                    <div class="overview-booking-header d-flex flex-wrap justify-content-between ml-b-10">
                                        <div class="overview-booking-header-left mrb-10">
                                            @if($doctor->serial_day > 0 && $doctor->serial_or_slot != null)
                                                <h4 class="title">@lang('Available Schedule')</h4>
                                                <ul class="overview-booking-list">
                                                    <li class="available">@lang('Available')</li>
                                                    <li class="booked">@lang('Booked')</li>
                                                    <li class="selected">@lang('Selected')</li>
                                                </ul>
                                            @else
                                                <h4 class="title">@lang('No Schedule Available')</h4>
                                            @endif
                                        </div>

                                    </div>
                                    @if($doctor->serial_day > 0 && $doctor->serial_or_slot != null)
                                        <form action="{{ route('appointment.store',$doctor->id) }}" class="appointment-from" method="post">
                                            @csrf
                                            <div class="overview-booking-area">
                                                <div class="overview-booking-header-right mrb-10">
                                                    <div class="overview-date-area d-flex flex-wrap align-items-center justify-content-between">
                                                        <div class="overview-date-header">
                                                            <h5 class="title">@lang('Choose Your Date & Time')</h5>
                                                        </div>
                                                        <div class="overview-date-select">
                                                            <select class="form-control date-select" name="booking_date" required>
                                                                @foreach ($available_date as $item)
                                                                    <option value="{{ $item }}">{{ __($item) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="clearfix">
                                                    @foreach ($doctor->serial_or_slot as $item)
                                                        <li>
                                                            <a href="javascript:void(0)" class="available-time active-time item{{str_slug($item)}}" data-value="{{ $item }}">
                                                                <span>{{ $item }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                    <input type="hidden" name="time_serial" class="time" required>
                                                </ul>
                                            </div>
                                            <div class="booking-appoint-area">
                                                <div class="row justify-content-center ml-b-30">
                                                    <div class="col-lg-6 mrb-30">
                                                        <div class="booking-appoint-form-area">
                                                            <h4 class="title">@lang('Appointment Form')</h4>
                                                            <form class="booking-appoint-form">
                                                                <div class="row">
                                                                    <div class="col-lg-6 form-group">
                                                                        <input type="text" name="name" placeholder="@lang('Patient name')*" required>
                                                                    </div>
                                                                    <div class="col-lg-6 form-group">
                                                                        <input type="number" name="age" placeholder="@lang('Patient age')*" required>
                                                                    </div>
                                                                    <div class="col-lg-12 form-group">
                                                                        <input type="email" name="email" placeholder="@lang('Email')*" required>
                                                                    </div>
                                                                    <div class="col-lg-12 form-group">
                                                                        <input type="text" name="mobile" placeholder="@lang('Phone number')*" required>
                                                                    </div>
                                                                    <div class="col-lg-12 form-group">
                                                                        <textarea name="disease" placeholder="@lang('Disease details')*"></textarea>
                                                                    </div>
                                                                    <div class="col-lg-12 form-group d-flex flex-wrap justify-content-between">
                                                                        <button type="submit" class="cmn-btn payment-system" data-value="2">@lang('Pay In Cash')</button>

                                                                        @if ($general->op)
                                                                            <button type="submit" class="cmn-btn payment-system" data-value="1">@lang('Pay Online')</button>
                                                                        @endif
                                                                        <input type="hidden" name="payment_system" class="payment" required>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 mrb-30">
                                                        <div class="booking-confirm-area">
                                                            <h4 class="title">@lang('Confirm Your Booking')</h4>
                                                            <ul class="booking-confirm-list">
                                                                <li><span>@lang('Patient Name')</span> : <span class="custom-color" id="name"></span></li>
                                                                <li><span>@lang('Age')</span> : <span class="custom-color" id="age"></span></li>
                                                                <li><span>@lang('Email')</span> : <span class="custom-color" id="email"></span></li>
                                                                <li><span>@lang('Phone Number')</span> : <span class="custom-color" id="mobile"></span></li>
                                                                <li><span>@lang('Date & Time')</span> : <span class="custom-color" id="date"></span> , <span class="custom-color" id="book-time"></span></li>
                                                                <li><span>@lang('Fees')</span> : {{ $doctor->fees }} {{ $general->cur_sym }}</li>
                                                            </ul>
                                                            <div class="booking-confirm-btn">
                                                                <a href="javascript:void(0)" class="cmn-btn-active reset">@lang('Reset')</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-item">
                                <div class="fb-comments" data-href="{{ url()->current() }}" data-numposts="5"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- overview-section end -->
@endsection

@push('script')
<script>

    (function ($) {
        'use strict';

        $(document).on('click', '.active-time ', function(){
            $('.time').val($(this).data('value'));
            $('#book-time').text($(this).data('value'));
        });




        var booking = $('select[name=booking_date]').find('option:selected').val();
        $('.available-time').removeClass('active');
        var date = $('#date').text(booking);

        var url = "{{route('bookeddate')}}";
        var date = booking;
        var id = '<?php echo $doctor->id; ?>';
        var data = {date:date,doctor_id:id}

        $.get(url, data,function(response){
            $('.time').val('');
            if(response.length == 0){
                $('.available-time').removeClass('disabled').addClass('active-time');
            }else{
                $('.available-time').removeClass('disabled').addClass('active-time');
                $.each(response, function(key, value) {
                    var item = $(`.item${value}`);
                    item.addClass('disabled').removeClass('active-time');
                });
            }
        });





        $('select[name=booking_date]').on('change',function(){
            $('.available-time').removeClass('active');
            var date = $('#date').text($(this).val());

            var url = "{{route('bookeddate')}}";
            var date = $(this).val();
            var id = '<?php echo $doctor->id; ?>';
            var data = {date:date,doctor_id:id}

            $.get(url, data,function(response){
                $('.time').val('');
                if(response.length == 0){
                    $('.available-time').removeClass('disabled').addClass('active-time');
                }else{
                    $('.available-time').removeClass('disabled').addClass('active-time');
                    $.each(response, function(key, value) {
                        var item = $(`.item${value}`);
                        item.addClass('disabled').removeClass('active-time');
                    });
                }
            });
        });







        $(document).on('click', '.payment-system', function(){
            $('.payment').val($(this).data('value'));
        });

        $(document).on('input','[name=name]',function() {
            $('#name').text($(this).val());
        });
        $(document).on('input','[name=age]',function() {
            $('#age').text($(this).val());
        });
        $(document).on('input','[name=email]',function() {
            $('#email').text($(this).val());
        });
        $(document).on('input','[name=mobile]',function() {
            $('#mobile').text($(this).val());
        });

        $(document).on('click', '.reset ', function(){
            $('#name').text('');
            $('#age').text('');
            $('#email').text('');
            $('#mobile').text('');
            $('#book-time').text('');

            $('.available-time').removeClass('active');
            $('.time').val('');

            $('.appointment-from').trigger("reset");

        });
    })(jQuery);
</script>
@endpush
