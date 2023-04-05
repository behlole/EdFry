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
                            <form action="{{$data->url}}" method="{{$data->method}}">


                                <h3 class="text-center">@lang('Please Pay') {{getAmount($deposit->final_amo)}} {{$deposit->method_currency}}</h3>
                                <h3 class="my-3 text-center">@lang('To Get') {{getAmount($deposit->amount)}}  {{$general->cur_text}}</h3>


                                <script src="{{$data->checkout_js}}"
                                        @foreach($data->val as $key=>$value)
                                        data-{{$key}}="{{$value}}"
                                    @endforeach >

                                </script>

                                <input type="hidden" custom="{{$data->custom}}" name="hidden">

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
            $('input[type="submit"]').addClass("mt-4 cmn-btn text-center").css("width","auto");
        })
    </script>
@endpush
