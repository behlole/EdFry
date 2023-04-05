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
                            <h3>@lang('Please Pay') {{getAmount($deposit->final_amo)}} {{$deposit->method_currency}}</h3>
                            <h3 class="my-3">@lang('To Get') {{getAmount($deposit->amount)}}  {{$general->cur_text}}</h3>


                            <button type="button" class="btn cmn-btn mt-4 btn-custom2 " id="btn-confirm" onClick="payWithRave()">@lang('Pay Now')</button>

                            <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>

                            <script>
                                "use strict";
                                var btn = document.querySelector("#btn-confirm");
                                btn.setAttribute("type", "button");
                                const API_publicKey = "{{$data->API_publicKey}}";

                                function payWithRave() {
                                    var x = getpaidSetup({
                                        PBFPubKey: API_publicKey,
                                        customer_email: "{{$data->customer_email}}",
                                        amount: "{{$data->amount }}",
                                        customer_phone: "{{$data->customer_phone}}",
                                        currency: "{{$data->currency}}",
                                        txref: "{{$data->txref}}",
                                        onclose: function () {
                                        },
                                        callback: function (response) {
                                            var txref = response.tx.txRef;
                                            var status = response.tx.status;
                                            var chargeResponse = response.tx.chargeResponseCode;
                                            if (chargeResponse == "00" || chargeResponse == "0") {
                                                window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                                            } else {
                                                window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                                            }
                                            // x.close(); // use this to close the modal immediately after payment.
                                        }
                                    });
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- payment-section end -->
@endsection
