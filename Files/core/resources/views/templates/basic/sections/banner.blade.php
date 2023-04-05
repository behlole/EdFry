@php
    $banner_element = getContent('banner.element',false);
    $social_icon_element = getContent('social_icon.element',false);
@endphp
<!-- banner-section start -->
<section class="banner">
    <div class="banner-social-area">
        <span>@lang('Follow Us')</span>
        <ul class="banner-social">
            @foreach ($social_icon_element as $item)
                <li><a href="{{ $item->data_values->url}}" target="_blank">@php echo $item->data_values->social_icon @endphp</a></li>
            @endforeach
        </ul>
    </div>
    <div class="banner-slider">
        <div class="swiper-wrapper">
            @foreach($banner_element as $item)
                <div class="swiper-slide">
                    <div class="banner-section">
                        <div class="banner-thumb bg_img" data-background="{{ getImage('assets/images/frontend/banner/'. $item->data_values->image) }}"></div>
                        <div class="custom-container">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="banner-content">
                                        <h2 class="title">{{ __($item->data_values->heading) }}</h2>
                                        <p>{{ __($item->data_values->details) }}</p>
                                        <div class="banner-btn">
                                            <a href="{{ route('doctors.all') }}" class="cmn-btn">@lang('Make Appointment')</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="banner-pagination"></div>
    </div>
</section>
<!-- banner-section end -->
