@php
    $doctor_content = getContent('doctor.content',true);
    $doctor_data = \App\Doctor::where('status',1)->with('sector:id,name')->with('location:id,name')->latest()->get(['id','name','qualification','rating','mobile','image','sector_id','location_id']);
@endphp
<!-- our doctor section start -->
<section class="booking-section ptb-80">
    <div class="container-fluid">
        <div class="row ml-b-20">
            <div class="booking-right-area">
                <div class="col-lg-12">
                    <div class="section-header">
                        <h2 class="section-title">{{ __($doctor_content->data_values->heading) }}</h2>
                        <p class="m-0">{{ __($doctor_content->data_values->details) }}</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="booking-slider">
                        <div class="swiper-wrapper">
                            @foreach ($doctor_data as $item)
                                <div class="swiper-slide">
                                    <div class="booking-item">
                                        <div class="booking-thumb">
                                            <img src="{{getImage(imagePath()['doctor']['path'].'/'.$item->image,imagePath()['doctor']['size'])}}" alt="@lang('doctor')">
                                            <div class="doc-deg">{{ __($item->sector->name) }}</div>
                                            <a href="#0" class="fav-btn"><i class="far fa-bookmark"></i></a>
                                        </div>
                                        <div class="booking-content">
                                            <span class="sub-title"><a href="{{ route('sector.doctors.all',$item->sector->id) }}">{{ __($item->sector->name) }}</a></span>
                                            <h5 class="title">{{ __($item->name) }} <i class="fas fa-check-circle"></i></h5>
                                            <p>{{ Str::limit(__($item->qualification),50) }}</p>
                                            <div class="booking-ratings">
                                                @for ($i = 0; $i < $item->rating; $i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor
                                            </div>
                                            <ul class="booking-list">
                                                <li><i class="icon-direction-alt"></i><a href="{{ route('location.doctors.all',$item->location->id) }}">{{ __($item->location->name) }}</a></li>
                                                <li><i class="las la-phone"></i> {{ __($item->mobile) }}</li>
                                            </ul>
                                            <div class="booking-btn">
                                                <a href="{{ route('booking',$item->id) }}" class="cmn-btn">@lang('Book Now')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="ruddra-next">
                            <i class="fas fa-long-arrow-alt-right"></i>
                        </div>
                        <div class="ruddra-prev">
                            <i class="fas fa-long-arrow-alt-left"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- booking-section end -->
