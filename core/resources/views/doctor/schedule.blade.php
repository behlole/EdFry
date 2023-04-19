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
                                <label
                                    class="form-control-label font-weight-bold">@lang('Select Schedule Slot Type')</label>
                                <select name="slot_type" id="slot-type" required>
                                    <option value="" selected disabled>@lang('Select One')</option>
                                    <option value="1">@lang('Serial')</option>
                                    <option value="2">@lang('Time')</option>
                                    {{--                                    New Feature for Weekly--}}
                                    {{--                                    and Monthly Scheduling--}}
                                    <option value="3">@lang('Weekly')</option>

                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-control-label font-weight-bold">Serial Available For Next How Many
                                    Days
                                </label>
                                <input class="form-control" type="number" name="serial_day"
                                       value="{{ $doctor->serial_day }}" placeholder="@lang('Example: 7')" required>
                            </div>


                            <div class="col-md-3 start-time @if($doctor->slot_type !=2) d-none @endif">
                                <label class="form-control-label font-weight-bold">@lang('Current Start Time')</label>
                                <input class="form-control" type="text" placeholder="@lang('No time selected yet')"
                                       value="{{ $doctor->start_time }}" readonly>
                            </div>
                            <div class="col-md-3 end-time @if($doctor->slot_type !=2) d-none @endif">
                                <label class="form-control-label font-weight-bold">@lang('Current End Time')</label>
                                <input class="form-control" type="text" placeholder="@lang('No time selected yet')"
                                       value="{{ $doctor->end_time }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4" id="slot-value">

                </div>
                <div
                    class="weekly-frequency-chart row justify-content-center mt-4 @if($doctor->slot_type!=3) d-none @endif">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-row">
                                    <h5>@lang('Select Concurrency Frequency Per Day')</h5>
                                    <div class="col-md-12" id="recurringChart">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
            let weekConcurrency = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            let check_slot_type = $('select[name="slot_type"]').val();
            let time_div = `<div class="card-body time_div">
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
            let serial_div = `<div class="card-body serial_div">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label class="form-control-label font-weight-bold">Maximum Serial</label>
                                    <input class="form-control" type="number" name="max_serial"  value="{{ $doctor->max_serial }}" placeholder="@lang('Example') : 20" required>
                                </div>
                            </div>
                        </div>`;
            let weekly_div = `<div class="card-body" id="weekly_div">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label class="form-control-label font-weight-bold">Maximum Week</label>
                                <input class="form-control" type="number" name="max_serial"  value="{{ $doctor->max_serial }}" placeholder="@lang('Example') : 20" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-control-label font-weight-bold">Select Concurrency Frequency</label>
                                <select class="form-control" name="recurring_frequency" id="recurring_frequency" multiple>
                                    <option value="Monday">Every Monday</option>
                                    <option value="Tuesday">Every Tuesday</option>
                                    <option value="Wednesday">Every Wednesday</option>
                                    <option value="Thursday">Every Thursday</option>
                                    <option value="Friday">Every Friday</option>
                                    <option value="Saturday">Every Saturday</option>
                                    <option value="Sunday">Every Sunday</option>
                                </select>
                            </div>
                        </div>
                    </div>`;
            let timePicker = function () {
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
            let recurringFrequency = [];

            if (check_slot_type == 2) {

                $('#slot-value').html(time_div);
            }
            if (check_slot_type == 1) {

                $('#slot-value').html(serial_div);
            }
            if (check_slot_type == 3) {
                let weeklyFrequency = "{{$doctor->weekly_frequency}}"
                weeklyFrequency = weeklyFrequency.replace(/&quot;/g, '"');
                weeklyFrequency = JSON.parse(weeklyFrequency.replace(/\\/g, "").slice(1, -1));


                $('select[name=recurring_frequency]').val(Object.keys(weeklyFrequency));

                $('#slot-value').html(weekly_div);
                $('.weekly-frequency-chart').removeClass('d-none')
                $('#slot-value').html(weekly_div)
                $('#recurring_frequency').selectpicker().on('changed.bs.select', (e, clickedIndex) => {
                    if (recurringFrequency.includes(weekConcurrency[clickedIndex])) {
                        recurringFrequency.splice(recurringFrequency.indexOf(weekConcurrency[clickedIndex]), 1);
                    } else {
                        recurringFrequency.push(weekConcurrency[clickedIndex]);
                    }
                    updateConcurrencyView(recurringFrequency)
                })
                $('select[name=recurring_frequency]').val(Object.keys(weeklyFrequency));
                Object.keys(weeklyFrequency).forEach((singleDay) => {
                    if (recurringFrequency.includes(singleDay)) {
                        recurringFrequency.splice(recurringFrequency.indexOf(singleDay), 1);
                    } else {
                        recurringFrequency.push(singleDay);
                    }
                })
                $('#recurring_frequency').selectpicker('refresh');
                updateConcurrencyView(recurringFrequency)
                recurringFrequency.forEach((singleFrequency, index) => {
                    let weeklyFrequency = "{{$doctor->weekly_frequency}}"
                    weeklyFrequency = weeklyFrequency.replace(/&quot;/g, '"');
                    weeklyFrequency = JSON.parse(weeklyFrequency.replace(/\\/g, "").slice(1, -1));
                    let allTimeSlots = weeklyFrequency
                        [singleFrequency]
                        [
                        Object.keys(
                            weeklyFrequency[singleFrequency])
                            [0]
                        ]
                    allTimeSlots[index].forEach((singleSlot)=>{
                        addTimeRow(singleFrequency, index, singleSlot.from_time, singleSlot.to_time)
                    })
                })
            }
            $("#slot-type").on('change', function () {
                var check_slot_type = $('select[name="slot_type"]').val();
                removeAllDivs()

                if (check_slot_type == 1) {
                    $('#slot-value').html(serial_div);
                }
                if (check_slot_type == 2) {
                    $('#slot-value').html(time_div);
                    $('.start-time').removeClass('d-none');
                    $('.end-time').removeClass('d-none');
                }
                if (check_slot_type == 3) {
                    $('.weekly-frequency-chart').removeClass('d-none')
                    $('#slot-value').html(weekly_div)
                    $('#recurring_frequency').selectpicker().on('changed.bs.select', (e, clickedIndex) => {
                        if (recurringFrequency.includes(weekConcurrency[clickedIndex])) {
                            recurringFrequency.splice(recurringFrequency.indexOf(weekConcurrency[clickedIndex]), 1);
                        } else {
                            recurringFrequency.push(weekConcurrency[clickedIndex]);
                        }
                        updateConcurrencyView(recurringFrequency)
                    })

                }
                timePicker();

            });

            timePicker();

            function removeAllDivs() {
                $('.start-time').addClass('d-none');
                $('.weekly-frequency-chart').addClass('d-none');
                $('.end-time').addClass('d-none');
                $('.month-select').addClass('d-none');
                $('.serial_div').remove();
                $('.time_div').remove();
            }

            function addTimeRow(singleDay, index, from_time = null, to_time = null) {
                return $(`#body-${singleDay}`).append(
                    returnTimeRow(
                        singleDay,
                        index,
                        document.getElementById(`body-${singleDay}`).childElementCount,
                        from_time,
                        to_time
                    ))
            }

            function returnTimeRow(singleDay, index, latestChildIndex, from_time = null, to_time = null) {
                return `<div class="flex flex-row justify-content-between" id="row-${singleDay}-${index}-${latestChildIndex}"  style="display: flex">
                                    <div style="width: 40%">
                                     <label for="from_time_${singleDay}_${index}_${latestChildIndex}">From Time:</label>
                                      <input class="form-control timepicker" value="${from_time == null ? '0.00' : from_time}" type="text" name="from_time_${singleDay}_${index}_${latestChildIndex}" id="from_time_${singleDay}_${index}_${latestChildIndex}" />
                                    </div>
                                    <div style="width: 40%">
                                                    <label for="to_time_${singleDay}_${index}_${latestChildIndex}">To Time:</label>
                                    <div style="display: flex;flex-direction: row">
                                         <input class="form-control timepicker" style="width: 80%" value="${to_time == null ? '0.00' : to_time}" type="text" name="to_time_${singleDay}_${index}_${latestChildIndex}" id="to_time_${singleDay}_${index}_${latestChildIndex}" />
                                        <button type="button" class="btn btn-outline-danger" id="remove-button-${singleDay}-${index}-${latestChildIndex}" style="width: 20%">X</button>
                                     </div>
                                    </div>
                                </div>`
            }

            function updateConcurrencyView(recurringFrequency) {
                $('#recurringChart').html('');
                recurringFrequency.forEach((singleDay, index) => {
                    $('#recurringChart').append(`
                    <input type="hidden" name="every-${singleDay}" value="true"/>
                    <div id="accordion">
                      <div class="card">
                        <div class="card-header" id="heading${index}"  data-toggle="collapse" data-target="#${singleDay}" aria-expanded="true" aria-controls="${singleDay}>
                          <h5 class="mb-0">
                            <a class="btn btn-link"">
                              ${singleDay}
                            </a>
                          </h5>
                        </div>

                        <div id="${singleDay}" class="collapse" aria-labelledby="${singleDay}" data-parent="#accordion">
                          <button class="btn btn--primary" style="display: flex;margin: auto;width: 30%;flex-direction:column-reverse;align-items: center;margin-top: 2%" id="button-${index}" type="button">Add</button>
                          <div class="card-body time-row-body" id="body-${singleDay}">
                          </div>
                        </div>
                      </div>
                    </div>
                    `)
                    $(`#button-${index}`).on('click', () => {
                        addTimeRow(singleDay, index)
                        let childElementCount = document.getElementById(`body-${singleDay}`).childElementCount - 1;
                        $(`#remove-button-${singleDay}-${index}-${childElementCount}`).on('click', () => {
                            $(`#row-${singleDay}-${index}-${childElementCount}`).remove()
                        })
                        timePicker()

                    })
                })
                timePicker()
            }
        })(jQuery);

    </script>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/doctor/js/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('assets/doctor/js/bootstrap-material-datetimepicker-bs4.min.js') }}"></script>
@endpush
