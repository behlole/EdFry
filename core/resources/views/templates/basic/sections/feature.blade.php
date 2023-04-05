@php
    $feature_content = getContent('feature.content',true);
    $doctors = \App\Doctor::where('status',1)->where('featured',1)->with('sector:id,name')->with('location:id,name')->with(['socialIcons' => function($query) {
        $query->select(['id','url','icon','doctor_id']);
    }])->latest()->take(6)->get(['id','name','about','qualification','sector_id','location_id','image','mobile']);
@endphp
<!-- featured-doctor-section start -->
<section class="team-section ptb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-header">
                    <h2 class="section-title">{{ __($feature_content->data_values->heading) }}</h2>
                    <p class="m-0">{{ __($feature_content->data_values->details) }} </p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center ml-b-30">
            @foreach($doctors as $item)
                <div class="col-xl-6 col-lg-4 col-md-6 mrb-30">
                    <div class="team-item d-flex flex-wrap align-items-center justify-content-between">
                        <div class="team-thumb">
                            <img src="{{getImage(imagePath()['doctor']['path'].'/'.$item->image,imagePath()['doctor']['size'])}}" alt="@lang('doctor-image')">
                            <div class="team-thumb-overlay">
                                <ul class="social-icon">
                                    @foreach ($item->socialIcons as $data)
                                        <li><a href="{{ $data->url }}" target="_blank">@php echo $data->icon @endphp</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="team-content">
                            <h5 class="title">{{ __($item->name) }}</h5>
                            <p>{{ Str::limit(__($item->about),70) }}</p>
                            <h6 class="title">@lang('Qualification')</h6>
                            <p>{{ Str::limit(__($item->qualification),30)}}</p>

                            <div class="booking-btn">
                                <a href="{{ route('booking',$item->id) }}" class="cmn-btn">@lang('Book Now')</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row justify-content-center mrt-60">
            <div class="col-lg-12 text-center">
                <div class="team-btn">
                    <a href="{{ route('featured.doctors.all') }}" class="cmn-btn-active">@lang('View More')</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- featured-doctor-section end -->
