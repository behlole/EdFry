@extends($activeTemplate.'layouts.frontend')


@section('content')

@include($activeTemplate.'partials.breadcrumb')

    <!-- payment-section start -->
    <section class="payment-section payment-preview-section ptb-80">
        <div class="container">
            <div class="row justify-content-center mrb-10">
                <div class="col-lg-10 mrb-30">
                    <div class="payment-item d-flex flex-wrap align-items-center justify-content-between">
                        <span class="payment-badge">
                            <i class="icon-panel"></i>
                        </span>
                        <div class="payment-thumb">
                            <img src="{{ $data->gateway_currency()->methodImage() }}" alt="@lang('payment')">
                        </div>
                        <div class="payment-content">
                            <ul class="payment-list">
                                <li><span> {{ __($data->gateway_currency()->name) }}</span></li>
                                <li><span> @lang('Amount')</span> - {{getAmount($data->amount)}} {{$general->cur_text}}</li>
                                <li><span> @lang('Charge')</span> - {{getAmount($data->charge)}} {{$general->cur_text}}</li>
                                <li><span> @lang('Payable')</span> - {{getAmount($data->amount + $data->charge)}} {{$general->cur_text}}</li>
                                <li><span> @lang('Conversion Rate')</span> - 1 {{$general->cur_text}} = {{getAmount($data->rate)}}  {{$data->baseCurrency()}}</li>
                                <li><span> @lang('Payable In') {{$data->baseCurrency()}}</span> - {{getAmount($data->final_amo)}} {{$data->baseCurrency()}}</li>

                                @if($data->gateway->crypto==1)
                                    <li><span> @lang('Conversion With')</span> - {{ $data->method_currency }} @lang('and final value will Show on next step')</li>
                                @endif
                            </ul>
                            <div class="payment-btn">
                                @if( 1000 >$data->method_code)
                                    <a href="{{route('deposit.confirm')}}" class="cmn-btn">@lang('Pay Now')</a>
                                @else
                                    <a href="{{route('deposit.manual.confirm')}}" class="cmn-btn">@lang('Pay Now')</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- payment-section end -->
@endsection


