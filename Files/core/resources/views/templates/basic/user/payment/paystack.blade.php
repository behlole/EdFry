@extends($activeTemplate.'layouts.frontend')

@section('content')
@include($activeTemplate.'partials.breadcrumb')

    <!-- payment-section start -->
    <section class="payment-confirm-section payment-section payment-preview-section ptb-80">
        <div class="container">
            <div class="row justify-content-center mrb-10">
                <div class="col-lg-8 mrb-30">
                    <div class="payment-item d-flex flex-wrap align-items-center justify-content-between">
                        <span class="payment-badge">
                            <i class="icon-panel"></i>
                        </span>
                        <div class="payment-thumb">
                            <img src="{{$deposit->gateway_currency()->methodImage()}}" alt="@lang('payment')">
                        </div>
                        <div class="payment-content text-center">
                            <form action="{{ route('ipn.'.$deposit->gateway->alias) }}" method="POST" class="text-center">
                                @csrf
                                <h3>@lang('Please Pay') {{getAmount($deposit->final_amo)}} {{$deposit->method_currency}}</h3>
                                <h3 class="my-3">@lang('To Get') {{getAmount($deposit->amount)}}  {{$general->cur_text}}</h3>


                                <button type="button" class=" mt-4 cmn-btn text-center" id="btn-confirm">@lang('Pay Now')</button>

                                <script src="//js.paystack.co/v1/inline.js"
                                    data-key="{{ $data->key }}"
                                    data-email="{{ $data->email }}"
                                    data-amount="{{$data->amount}}"
                                    data-currency="{{$data->currency}}"
                                    data-ref="{{ $data->ref }}"
                                    data-custom-button="btn-confirm"
                                >
                                </script>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- payment-section end -->

@endsection
