@extends($activeTemplate.'layouts.frontend')

@section('content')
@include($activeTemplate.'partials.breadcrumb')

<div class="payment-confirm ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center">
                    <div class="card-header bg-site-color">
                        <h5 class="card-title text-white">@lang('Payment Preview')</h5>
                    </div>
                    <div class="card-body card-body-deposit text-center">
                        <h4 class="my-2"> @lang('Please Send Exactly') <span class="text-color"> {{ $data->amount }} </span> {{$data->currency}}</h4>
                        <h5 class="mb-2">@lang('To') <span class="text-color">{{ $data->sendto }}</span></h5>
                        <img src="{{$data->img}}" alt="..">
                        <h4 class="text-color bold my-4">@lang('Scan To Send')</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
