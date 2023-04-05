@php
    $sector_content = getContent('sector.content',true);
    $sector_data = \App\Sector::latest()->get();

    if($sector_data->count() >= 4){
        $len = round($sector_data->count() / 4);
    }else{
        $len = $sector_data->count();
    }
    $item = [];
    $skip = 0;
    for($i = 0; $i<$len; $i++) {
        $item[$i] = $sector_data->skip($skip)->take(4);
        $skip += 4;
    }
@endphp

<!-- choose-section start -->
<section class="choose-section ptb-80">
    <div class="container">
        <div class="row justify-content-center align-items-center ml-b-30">
            <div class="col-lg-4 mrb-30">
                <div class="choose-left-content">
                    <h2 class="title">{{ __($sector_content->data_values->heading) }}</h2>
                    <p>{{ __($sector_content->data_values->details) }}</p>
                    <div class="choose-btn">
                        <a href="{{ route('doctors.all') }}" class="cmn-btn">@lang('Book Now')</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mrb-30">
                <div class="choose-slider">
                    <div class="swiper-wrapper">
                        @for($j = 0; $j < count($item); $j++)
                        <div class="swiper-slide">
                            <div class="choose-right-content">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="right-column-one">
                                            @foreach($item[$j]->take(2) as $data)
                                            <div class="choose-item">
                                                <div class="choose-thumb">
                                                    <img src="{{getImage(imagePath()['sector']['path'].'/'.$data->image,imagePath()['sector']['size'])}}" alt="@lang('choose')">
                                                </div>
                                                <div class="choose-content">
                                                <h6 class="title"><a href="{{ route('sector.doctors.all',$data->id) }}">{{ __($data->name) }}</a></h6>
                                                    <p>{{ __($data->details) }}</p>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="right-column-two">
                                            @foreach($item[$j]->skip(2)->take(2) as $data)
                                            <div class="choose-item">
                                                <div class="choose-thumb">
                                                    <img src="{{getImage(imagePath()['sector']['path'].'/'.$data->image,imagePath()['sector']['size'])}}" alt="@lang('choose')">
                                                </div>
                                                <div class="choose-content">
                                                <h6 class="title">{{ __($data->name) }}</h6>
                                                    <p>{{ __($data->details) }}</p>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- choose-section end -->
