@php
    $blog_content = getContent('blog.content',true);
    $blog_element = \App\Frontend::where('data_keys', 'blog.element')->latest()->limit(3)->get();
@endphp
<!-- blog-section start -->
<section class="blog-section ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="section-header">
                    <h2 class="section-title">{{ __($blog_content->data_values->heading) }}</h2>
                    <p>{{ __($blog_content->data_values->details) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($blog_element as $item)
            <div class="col-lg-4 col-md-6 col-sm-8 mrb-30">
                <div class="blog-item">
                    <div class="blog-thumb">
                        <img src="{{ getImage('assets/images/frontend/blog/'. @$item->data_values->image) }}" alt="@lang('blog-image')">
                        <span class="blog-cat">{{ __($item->data_values->category) }}</span>
                    </div>
                    <div class="blog-content">
                        <h4 class="title"><a href="{{ route('blog.details',[$item->id,\Str::slug(__($item->data_values->title))]) }}">{{ Str::limit(strip_tags(__($item->data_values->title)),50) }} </a></h4>
                        <p>{{ Str::limit(strip_tags(__($item->data_values->description)),100) }}</p>
                        <div class="blog-btn">
                            <a href="{{ route('blog.details',[$item->id,\Str::slug(__($item->data_values->title))]) }}" class="custom-btn">@lang('Continue Reading')</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- blog-section end -->

