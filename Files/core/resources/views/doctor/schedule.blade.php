@extends('doctor.layouts.app')
@push('style')
<link rel="stylesheet" href="{{ asset('assets/doctor/css/bootstrap-material-datetimepicker-bs4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/doctor/css/Material+Icons.css')}}">
@endpush
@section('panel')
<div class="row justify-content-center">
    <div class="col-md-12">
        <form action="{{ route('doctor.schedule.slot') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-md-3">
                            <label class="form-control-label font-weight-bold">@lang('Select Schedule Slot Type')</label>
                            <select name="slot_type" id="slot-type" required>
                                <option value="" selected disabled>@lang('Select One')</option>
                                <option value="1">@lang('Serial')</option>
                                <option value="2">@lang('Time')</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-control-label font-weight-bold">@lang('Serial Available For Next How Many
                                Days')</label>
                            <input class="form-control" type="number" name="serial_day"
                                value="{{ $doctor->serial_day }}" placeholder="@lang('Example: 7')" required>
                        </div>


                        <div class="col-md-3 start-time @if($doctor->slot_type != 2) d-none @endif">
                            <label class="form-control-label font-weight-bold">@lang('Current Start Time')</label>
                            <input class="form-control" type="text" placeholder="@lang('No time selected yet')"
                                value="{{ $doctor->start_time }}" readonly>
                        </div>
                        <div class="col-md-3 end-time @if($doctor->slot_type != 2) d-none @endif">
                            <label class="form-control-label font-weight-bold">@lang('Current End Time')</label>
                            <input class="form-control" type="text" placeholder="@lang('No time selected yet')"
                                value="{{ $doctor->end_time }}" readonly>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card mt-4" id="slot-value">

            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if ($doctor->slot_type && $doctor->serial_or_slot != null)
    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-md-12">
                            <h5>@lang('Current Schedule System')</h5>
                            <div class="mt-4">
                                @foreach ($doctor->serial_or_slot as $item)
                                <a href="#0" class="btn btn--primary mr-2 mb-2">{{ $item }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-md-12">
                            <h5>@lang('You have no schedule')</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('script')

<script>

    (function ($) {
    'use strict';

        $('select[name=slot_type]').val("{{$doctor->slot_type}}");

        var check_slot_type = $('select[name="slot_type"]').val();
        var time_div = `<div class="card-body time_div">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label class="form-control-label font-weight-bold">Time Slot Duration <span class="small-text">(@lang('minutes'))</span></label>
                                    <input class="form-control" type="number" name="duration" value="{{ $doctor->duration }}" placeholder="@lang('Example : 20')" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label font-weight-bold">New Start Time</label>
                                    <input class="form-control timepicker" type="text" name="start_time" value="@if($doctor->start_time) {{ Carbon\Carbon::parse($doctor->start_time)->format('H:i') }} @else 0.00 @endif" placeholder="@lang('Click here')" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label font-weight-bold">New End Time</label>
                                    <input class="form-control timepicker" type="text" name="end_time" value="@if($doctor->start_time) {{ Carbon\Carbon::parse($doctor->end_time)->format('H:i') }} @else 0.00 @endif" placeholder="@lang('Click here')" required>
                                </div>
                            </div>
                        </div>`;
        var serial_div = `<div class="card-body serial_div">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label class="form-control-label font-weight-bold">Maximum Serial</label>
                                    <input class="form-control" type="number" name="max_serial"  value="{{ $doctor->max_serial }}" placeholder="@lang('Example') : 20" required>
                                </div>
                            </div>
                        </div>`;

        var timePicker = function () {
            $('.timepicker').bootstrapMaterialDatePicker({
                format: 'HH:mm',
                shortTime: false,
                date: false,
                time: true,
                monthPicker: false,
                year: false,
                switchOnClick: true
            });
        }

        if (check_slot_type == 2) {

            $('#slot-value').html(time_div);
        }
        if (check_slot_type == 1) {

            $('#slot-value').html(serial_div);
        }
 
        $("#slot-type").on('change',function () {
            var check_slot_type = $('select[name="slot_type"]').val();
            if (check_slot_type == 1) {

                $('#slot-value').html(serial_div);
                $('.start-time').addClass('d-none');
                $('.end-time').addClass('d-none');
                $('.time_div').remove();
            }
            if (check_slot_type == 2) {

                $('#slot-value').html(time_div);
                $('.start-time').removeClass('d-none');
                $('.end-time').removeClass('d-none');
                $('.serial_div').remove();
            }
            timePicker();

        });

        timePicker();
    })(jQuery);

</script>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/doctor/js/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('assets/doctor/js/bootstrap-material-datetimepicker-bs4.min.js') }}"></script>
@endpush
