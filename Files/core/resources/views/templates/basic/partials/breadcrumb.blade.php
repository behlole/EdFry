@php
    $breadcrumb_content = getContent('breadcrumb.content',true);
@endphp
<!-- banner-section start -->
<section class="inner-banner-section bg-overlay-white banner-section bg_img" data-background="{{ getImage('assets/images/frontend/breadcrumb/'. @$breadcrumb_content->data_values->image) }}">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="banner-content">
                    <h2 class="title">{{ __($page_title) }}</h2>
                    <div class="breadcrumb-area">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('Home')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __($page_title) }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- banner-section end -->
