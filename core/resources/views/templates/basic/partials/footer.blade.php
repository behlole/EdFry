@php
    $footer_content = getContent('footer.content',true);
    $footer_contact_element = getContent('footer.element',false);
    $social_icon_element = getContent('social_icon.element',false);
    $sectors = \App\Sector::latest()->get()->take(6);
    $locations = \App\Location::latest()->get()->take(6);
@endphp
<!-- call-to-action section start -->
<section class="call-to-action-section">
    <div class="container">
        <div class="row justify-content-center align-self-center">
            <div class="col-lg-8 text-center">
                <div class="call-to-action-area">
                    <div class="call-info">
                        <div class="call-info-thumb">
                            <img src="{{ getImage('assets/images/frontend/footer/'. @$footer_content->data_values->emergency_contact_image) }}" alt="@lang('call')">
                        </div>
                        <div class="call-info-content">
                            <h4 class="title">
                                <span>@lang('Customer Helpline')</span>
                                <a href="#">{{ __($footer_content->data_values->emergency_contact) }}</a>
                            </h4>
                        </div>
                    </div>
                    <div class="mail-info">
                        <div class="mail-info-thumb">
                            <img src="{{ getImage('assets/images/frontend/footer/'. @$footer_content->data_values->support_email_image) }}" alt="@lang('email')">
                        </div>
                        <div class="mail-info-content">
                            <h4 class="title">
                                <span>@lang('24/7 Email Support')</span>
                                <a href="#">{{ __($footer_content->data_values->support_email) }}</a>
                            </h4>
                        </div>
                    </div>
                    <span class="dc-or-text">- @lang('OR') -</span>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- call-to-action section end -->


<!-- footer-section start -->
<footer class="footer-section ptb-80">
    <div class="custom-container">
        <div class="footer-area">
            <div class="row ml-b-30">
                <div class="col-lg-4 col-sm-6 mrb-30">
                    <div class="footer-widget">
                        <div class="footer-logo">
                            <a href="{{ route('home') }}" class="site-logo"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('site-logo')"></a>
                        </div>
                        <p>{{ __($footer_content->data_values->footer_details) }}</p>
                        <ul>
                            @foreach ($footer_contact_element as $item)
                                <li>@php echo $item->data_values->footer_contact_icon @endphp {{ __($item->data_values->footer_contact_details) }}</li>
                            @endforeach
                        </ul>

                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 mrb-30">
                    <div class="footer-widget">
                        <h3 class="widget-title">@lang('Expertise Areas')</h3>
                        <ul>
                            @foreach ($sectors as $item)
                                <li><a href="{{ route('sector.doctors.all',$item->id) }}"><i class="fas fa-long-arrow-alt-right"></i>{{ __($item->name) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 mrb-30">
                    <div class="footer-widget">
                        <h3 class="widget-title">@lang('Locations')</h3>
                        <ul>
                            @foreach ($locations as $item)
                                <li><a href="{{ route('location.doctors.all',$item->id) }}"><i class="fas fa-long-arrow-alt-right"></i>{{ __($item->name) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mrb-30">
                    <div class="footer-widget">
                        <h3 class="widget-title">{{ __($footer_content->data_values->subscribe_heading) }}</h3>
                        <p>{{ __($footer_content->data_values->subscribe_details) }}</p>

                        <form class="footer-form">
                            <input type="email" name="email" id="subscriber" placeholder="@lang('Enter Your Email')" required>
                            <button type="button"  class="submit-btn subs"><i class="icon-arrow-right"></i></button>
                        </form>
                        <div class="social-area">
                            <ul class="footer-social">
                                @foreach ($social_icon_element as $item)
                                    <li><a href="{{ $item->data_values->url }}" target="_blank">@php echo $item->data_values->social_icon @endphp</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="privacy-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="copyright-area d-flex flex-wrap align-items-center justify-content-center">
                    <div class="copyright">
                        <p>{{ $footer_content->data_values->copyright_details }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- footer-section end -->

@push('script')
<script>
"use strict";
$('.subs').on('click',function () {

    var email = $('#subscriber').val();
    var csrf = '{{csrf_token()}}'

    var url = "{{ route('subscriber.store') }}";
    var data = {email:email, _token:csrf};

    $.post(url, data,function(response){

        if(response.email){
            $.each(response.email, function (i, val) {
            iziToast.error({
            message: val,
            position: "topRight"
            });
         });
        } else{
            iziToast.success({
            message: response.success,
            position: "topRight"
            });
        }
    });

});


</script>

@endpush
