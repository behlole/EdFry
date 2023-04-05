@extends($activeTemplate.'layouts.frontend')

@section('content')
@include($activeTemplate.'partials.breadcrumb')

<!-- payment-section start -->
    <section class="payment-section ptb-80">
        <div class="container">
            <div class="row justify-content-center mrb-10">
                @foreach($gatewayCurrency as $data)
                    <div class="col-xl-6 col-lg-4 col-md-6 mrb-30">
                        <form action="{{route('deposit.insert')}}" method="post">
                            @csrf
                            <input type="hidden" name="currency" value="{{ $data->currency }}">
                            <input type="hidden" name="method_code"  value="{{ $data->method_code }}">
                            <input type="hidden" name="amount"  value="{{ $doctor->fees }}">
                            <input type="hidden" name="doctor_id"  value="{{ $doctor->id }}">
                            <input type="hidden" name="trx"  value="{{ $payment_trx }}">

                            <div class="payment-item d-flex flex-wrap align-items-center justify-content-between">
                                <span class="payment-badge">
                                    <i class="icon-panel"></i>
                                </span>
                                <div class="payment-thumb">
                                    <img src="{{$data->methodImage()}}" alt="@lang('payment-method-image')">
                                </div>
                                <div class="payment-content">
                                    <ul class="payment-list">
                                        <li><span> {{ __($data->name) }}</span></li>
                                        <li><span> @lang('Fees')</span> : {{ __($doctor->fees) }} {{ __($general->cur_text) }}</li>
                                        <li><span> @lang('Charge')</span> - {{ getAmount($data->fixed_charge) }} {{$general->cur_text}} @if($data->percent_charge > 0)+ {{ getAmount($data->percent_charge) }}% @endif</li>
                                    </ul>
                                    <div class="payment-btn">
                                        <button type="submit" class="cmn-btn">@lang('Deposit Now')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- payment-section end -->
@stop
