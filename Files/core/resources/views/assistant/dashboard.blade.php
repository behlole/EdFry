@extends('assistant.layouts.app')

@section('panel')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __($page_title) }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-sm-4 mb-30">
                            <div class="dashboard-w1 bg--gradi-7 b-radius--10 box-shadow has--link">
                                <a href="#0" class="item--link"></a>
                                <div class="icon">
                                    <i class="fas fa-check-square"></i>
                                </div>
                                <div class="details">
                                    <div class="numbers">
                                        <span class="amount">{{ $done_appointment }}</span>
                                    </div>
                                    <div class="desciption">
                                        <span>@lang('Appointment Done')</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-sm-4 mb-30">
                            <div class="dashboard-w1 bg--gradi-46 b-radius--10 box-shadow has--link">
                                <a href="#0" class="item--link"></a>
                                <div class="icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="details">
                                    <div class="numbers">
                                        <span class="amount">{{ $new_appointment }}</span>
                                    </div>
                                    <div class="desciption">
                                        <span>@lang('New Appointment')</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-sm-4 mb-30">
                            <div class="dashboard-w1 bg--gradi-1 b-radius--10 box-shadow has--link">
                                <a href="#0" class="item--link"></a>
                                <div class="icon">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div class="details">
                                    <div class="numbers">
                                        <span class="amount">{{ $total_doctor }}</span>
                                        <span class="currency-sign"></span>
                                    </div>
                                    <div class="desciption">
                                        <span>@lang('Total Doctors')</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
