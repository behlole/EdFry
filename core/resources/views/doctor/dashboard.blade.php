@extends('doctor.layouts.app')

@section('panel')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __($page_title) }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-sm-3 mb-30">
                            <div class="dashboard-w1 bg--gradi-1 b-radius--10 box-shadow has--link">
                                <a href="#0" class="item--link"></a>
                                <div class="icon">
                                    <i class="fa fa-credit-card"></i>
                                </div>
                                <div class="details">
                                    <div class="numbers">
                                        <span class="amount">{{ round($total_online_earn) }} {{ $general->cur_sym }}</span>
                                        <span class="currency-sign"></span>
                                    </div>
                                    <div class="desciption">
                                        <span>@lang('Total Online Earn')</span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- dashboard-w1 end -->

                        <div class="col-xl-3 col-lg-3 col-sm-3 mb-30">
                            <div class="dashboard-w1 bg--gradi-44 b-radius--10 box-shadow has--link">
                                <a href="#0" class="item--link"></a>
                                <div class="icon">
                                    <i class="la la-exchange-alt"></i>
                                </div>
                                <div class="details">
                                    <div class="numbers">
                                        <span class="amount">{{ $total_cash_earn }} {{ $general->cur_sym }}</span>
                                    </div>
                                    <div class="desciption">
                                        <span>@lang('Total Cash Earn')</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-sm-3 mb-30">
                            <div class="dashboard-w1 bg--gradi-46 b-radius--10 box-shadow has--link">
                                <a href="#0" class="item--link"></a>
                                <div class="icon">
                                    <i class="la la-exchange-alt"></i>
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

                        <div class="col-xl-3 col-lg-3 col-sm-3 mb-30">
                            <div class="dashboard-w1 bg--gradi-7 b-radius--10 box-shadow has--link">
                                <a href="#0" class="item--link"></a>
                                <div class="icon">
                                    <i class="la la-exchange-alt"></i>
                                </div>
                                <div class="details">
                                    <div class="numbers">
                                        <span class="amount">{{ $appointment_done }}</span>
                                    </div>
                                    <div class="desciption">
                                        <span>@lang('Appointment Done')</span>
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

