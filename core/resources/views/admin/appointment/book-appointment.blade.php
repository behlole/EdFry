@extends('admin.layouts.app')

@section('panel')

<div class="row mb-none-30">
    <div class="col-xl-3 col-lg-5 col-md-5 mb-30">
        <div class="widget-two box--shadow2 b-radius--5 bg--white">
            <div class="card-body p-0">
                <div class="p-3 bg--white">
                    <div>
                        <img src="{{ getImage(imagePath()['doctor']['path'].'/'. $doctor->image,imagePath()['doctor']['size'])}}" alt="@lang('profile-image')"
                             class="b-radius--10 w-100">
                    </div>
                    <div class="mt-15">
                        <h4>{{$doctor->name}}</h4>
                        <span class="text--small">@lang('Joined At') <strong>{{date('d M, Y h:i A',strtotime($doctor->created_at))}}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Doctor information')</h5>
                <ul class="list-group">

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Username')
                        <a href="{{ route('admin.doctors.detail',$doctor->id) }}"><span class="font-weight-bold">{{$doctor->username}}</span></a>
                    </li>


                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Status')
                        @switch($doctor->status)
                            @case(1)
                            <span class="badge badge-pill bg--success">@lang('Active')</span>
                            @break
                            @case(0)
                            <span class="badge badge-pill bg--danger">@lang('Banned')</span>
                            @break
                        @endswitch
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-9 col-lg-7 col-md-7 mb-30">
        <form action="{{ route('admin.appointments.store',$doctor->id) }}" method="post">
            @csrf
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body p-0">
                    <div class="p-3 bg--white">
                        <div class="widget-two box--shadow2 b-radius--5 bg--white mb-4">
                            <i class="far fa-clock overlay-icon text--primary"></i>
                            <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="far fa-clock"></i>
                            </div>
                            <div class="widget-two__content">
                                @if( ($doctor->start_time == null || $doctor->end_time == null) && $doctor->max_serial)
                                    <h3 class="">{{ $doctor->max_serial }}</h3>
                                    <p>@lang('Serial')</p>
                                @elseif($doctor->start_time && $doctor->end_time)
                                    <h3 class="">{{ $doctor->start_time  }} - {{ $doctor->end_time  }}</h3>
                                    <p>@lang('Time')</p>
                                @else

                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h4 class="mb-2">@lang('Select Date')</h4>
                            <select name="booking_date" class="form-control" required>
                                <option selected disabled>@lang('Select One')</option>
                                @foreach ($available_date as $item)
                                    <option value="{{ $item }}">{{ __($item) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <h4>@lang('Available Schedule')</h4>
                        <div class="time-serial-parent mt-3">
                            @foreach ($doctor->serial_or_slot as $item)
                                <a href="javascript:void(0)" class="btn btn--primary mr-2 mb-2 available-time item{{str_slug($item)}}" data-value="{{ $item }}" >{{ __($item) }}</a>
                            @endforeach
                        </div>
                        <input type="hidden" name="time_serial" class="time" required>
                    </div>
                </div>
            </div>

            <div class="card b-radius--10 overflow-hidden box--shadow1 mt-4">
                <div class="card-body p-0">
                    <div class="p-3 bg--white">
                        <h4>@lang('Patient Information')</h4>
                        <div class="form-group">
                            <label class="text-muted">@lang('Name') <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder="@lang('Example : Mr. Demo')" required>
                        </div>
                        <div class="form-group">
                            <label class="text-muted">@lang('Age') <span class="text-danger">*</span></label>
                            <input type="number" name="age" class="form-control" value="{{old('age')}}" placeholder="@lang('Example : 25')" required>
                        </div>
                        <div class="form-group">
                            <label class="text-muted">@lang('E-mail') <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{old('email')}}" placeholder="@lang('Example : demo@demo.com')" required>
                        </div>
                        <div class="form-group">
                            <label class="text-muted">@lang('Contact No') <span class="text-danger">*</span></label>
                            <input type="text" name="mobile" class="form-control" value="{{old('mobile')}}" placeholder="@lang('Example : 00000000')" required>
                        </div>
                        <div class="form-group">
                            <label class="text-muted">@lang('Disease Details')</label>
                            <textarea name="disease" class="form-control"  rows="2" placeholder="@lang('Enter Disease Details')"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Submit')</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@push('script')
<script>
    (function($){

        "use strict";

        $(document).on('click', '.available-time', function(){

            var cls = $(this).parent('.time-serial-parent').find('.btn--success').removeClass('btn--success disabled').addClass('btn--primary');

            $('.time').val($(this).data('value'));
            $(this).removeClass('btn--primary');
            $(this).addClass('btn--success disabled');

        });

        $(document).on('change','select[name=booking_date]',function(){
            $('.available-time').removeClass('btn--success disabled').addClass('btn--primary');

            var url = "{{route('admin.appointment.booked.date')}}";
            var date = $(this).val();
            var id = '<?php echo $doctor->id; ?>';
            var data = {date:date,doctor_id:id}

            $.get(url, data,function(response){
                $('.time').val('');
                if(response.length == 0){
                    $('.available-time').removeClass('btn--danger disabled');
                }else{
                    $('.available-time').removeClass('btn--danger disabled');
                    $.each(response, function(key, value) {
                        var item = $(`.item${value}`);
                        item.addClass('btn--danger disabled');
                    });
                }
            });
        });
    })(jQuery);
</script>
@endpush
