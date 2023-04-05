@php
    $faq_content = getContent('faq.content',true);
    $faq_element = getContent('faq.element',false);
@endphp
<!-- faq-section start -->
<section class="faq-section pd-t-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-header">
                    <h2 class="section-title">{{ __($faq_content->data_values->heading) }}</h2>
                    <p class="m-0">{{ __($faq_content->data_values->details) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center ml-b-30">
            <div class="col-lg-6 mrb-30">
                <div class="faq-wrapper">
                    @foreach($faq_element as $item)
                        @if($loop->index % 2 == 0)
                            <div class="faq-item">
                                <h3 class="faq-title"><span class="title">{{ __($item->data_values->question) }} </span><span class="right-icon"></span></h3>
                                <div class="faq-content">
                                    <p>{{ __($item->data_values->answer) }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6 mrb-30">
                <div class="faq-wrapper">
                    @foreach($faq_element as $item)
                        @if($loop->index % 2 != 0)
                            <div class="faq-item">
                                <h3 class="faq-title"><span class="title">{{ __($item->data_values->question) }} </span><span class="right-icon"></span></h3>
                                <div class="faq-content">
                                    <p>{{ __($item->data_values->answer) }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- faq-section end -->
