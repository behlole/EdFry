@php
    $search_content = getContent('search.content',true);
    $locations = \App\Location::latest()->get(['id','name']);
    $sectors = \App\Sector::latest()->get(['id','name']);
    $doctors = \App\Doctor::latest()->get(['id','name']);
@endphp
<!-- appoint-section start -->
<section class="appoint-section ptb-80 bg-overlay-white bg_img" data-background="{{ getImage('assets/images/frontend/search/'. @$search_content->data_values->image) }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="appoint-content">
                    <h2 class="title">{{ __($search_content->data_values->heading) }}</h2>
                    <p>{{ __($search_content->data_values->details) }}</p>
                    <form class="appoint-form" action="{{ route('search.doctors') }}" method="get">
                        @csrf
                        <div class="search-location form-group">
                            <div class="appoint-select">
                                <select class="chosen-select locations" name="location">
                                    <option value="">@lang('Location*')</option>
                                    @foreach ($locations as $item)
                                        <option value="{{ $item->id }}">{{ __($item->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="search-location form-group">
                            <div class="appoint-select">
                                <select class="chosen-select locations" name="sector">
                                    <option value="">@lang('Expertise*')</option>
                                    @foreach ($sectors as $item)
                                        <option value="{{ $item->id }}">{{ __($item->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="search-info form-group">
                            <div class="appoint-select">
                                <select class="chosen-select locations" name="doctor">
                                    <option value="">@lang('Mentors*')</option>
                                    @foreach ($doctors as $item)
                                        <option value="{{ $item->id }}">{{ __($item->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="search-btn cmn-btn"><i class="icon-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- appoint-section end -->
