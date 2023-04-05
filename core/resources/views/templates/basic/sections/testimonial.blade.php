@php
    $testimonial_content = getContent('testimonial.content',true);
    $testimonial_element = getContent('testimonial.element',false);
@endphp
<!-- client-section start -->
<div class="client-section ptb-80">
    <div class="client-element-one">
        <img src="{{ getImage('assets/images/shape.png') }}" alt="@lang('shape')">
    </div>
    <div class="client-element-two">
        <img src="{{ getImage('assets/images/shape.png') }}" alt="@lang('shape')">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="section-header-left">
                        <h2 class="section-title">{{ __($testimonial_content->data_values->heading) }}</h2>
                        <p class="m-0">{{ __($testimonial_content->data_values->details) }} </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center ml-b-20">
            <div class="col-lg-12 text-center">
                <div class="client-slider">
                    <div class="swiper-wrapper">
                        @foreach($testimonial_element as $item)
                            <div class="swiper-slide">
                                <div class="client-item">
                                    <div class="client-content">
                                        <p>{{ __($item->data_values->quote) }}</p>
                                        <div class="client-icon">
                                            <i class="icon-quote-left"></i>
                                        </div>
                                    </div>
                                    <div class="client-thumb">
                                        <img src="{{ getImage('assets/images/frontend/testimonial/'. @$item->data_values->image) }}" alt="@lang('client')">
                                    </div>
                                    <div class="client-footer">
                                        <h4 class="title">{{ __($item->data_values->name) }}</h4>
                                        <span class="sub-title">{{ __($item->data_values->designation) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- client-section end -->
