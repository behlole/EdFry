@extends($activeTemplate.'layouts.frontend')
@section('content')

@php echo fbcomment() @endphp

    @include($activeTemplate.'partials.breadcrumb')
    <!-- blog-section start -->
    <section class="blog-section blog-details-section ptb-80">
        <div class="container">
            <div class="row justify-content-center ml-b-20">
                <div class="col-lg-8 mrb-60">
                    <div class="blog-item">
                        <div class="blog-thumb">
                            <img src="{{ getImage('assets/images/frontend/blog/'. @$blog->data_values->image) }}" alt="@lang('blog-image')">
                        </div>
                        <div class="blog-content">
                            <div class="blog-details-content-header d-flex flex-wrap align-items-center justify-content-between">
                                <h3 class="title">{{ __($blog->data_values->title) }}</h3>
                                <span class="blog-details-date"><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($blog->created_at)->diffForHumans() }}</span>
                                <span class="blog-details-date"><i class="fas fa-eye"></i> {{ $blog->view_count }}</span>
                            </div>
                            <p>@php echo trans($blog->data_values->description) @endphp</p>
                        </div>
                    </div>
                    <div class="comments-section">
                        <div class="fb-comments" data-href="{{ url()->current() }}" data-numposts="5"></div>
                    </div>
                </div>
                <div class="col-lg-4 mrb-60">
                    <div class="sidebar">
                        <div class="widget-box mrb-30">
                            <h5 class="widget-title">@lang('Latest Blogs')</h5>
                            <div class="popular-widget-box">
                                @foreach($latest_blogs as $item)
                                    <div class="single-popular-item d-flex flex-wrap">
                                        <div class="popular-item-thumb">
                                            <img src="{{ getImage('assets/images/frontend/blog/'. @$item->data_values->image) }}" alt="@lang('blog-image')">
                                        </div>
                                        <div class="popular-item-content">
                                            <h5 class="title">
                                                <a href="{{ route('blog.details',[$item->id,\Str::slug(__($item->data_values->title))]) }}">{{ Str::limit(strip_tags(__($item->data_values->title)),20) }}</a>
                                            </h5>
                                            <span class="blog-date"><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- blog-section end -->
@endsection
