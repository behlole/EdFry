@extends($activeTemplate.'layouts.frontend')

@push('style')
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .StripeElement {
            box-sizing: border-box;
            height: 40px;
            padding: 10px 12px;
            border: 1px solid transparent;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }

        .card button {
            padding-left: 0px !important;
        }
    </style>
@endpush

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
                            <form action="{{$data->url}}" method="{{$data->method}}">
                                <h3 class="text-center">@lang('Please Pay') {{getAmount($deposit->final_amo)}} {{$deposit->method_currency}}</h3>
                                <h3 class="my-3 text-center">@lang('To Get') {{getAmount($deposit->amount)}}  {{$general->cur_text}}</h3>
                                <script
                                    src="{{$data->src}}"
                                    class="stripe-button"
                                    @foreach($data->val as $key=> $value)
                                    data-{{$key}}="{{$value}}"
                                    @endforeach
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

@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            $('button[type="submit"]').removeClass("stripe-button-el").addClass("cmn-btn text-center");
        })
    </script>
@endpush
