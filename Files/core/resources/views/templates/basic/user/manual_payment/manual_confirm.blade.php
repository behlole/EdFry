@extends($activeTemplate.'layouts.frontend')

@section('content')
@include($activeTemplate.'partials.breadcrumb')

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
                            <form action="{{ route('deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <ul class="payment-list">
                                    <li><span> {{ __($data->gateway_currency()->name) }}</span></li>
                                    <li><span> @lang('Doctor Fees')</span> - {{ getAmount($data['amount'])  }} {{$general->cur_text}}</li>
                                    <li><span> @lang('Payable')</span> - {{getAmount($data['final_amo']) .' '.$data['method_currency'] }}</li>
                                    <li><span> @lang('Please follow the instruction bellow')</span></li>
                                    <li><span> @php echo  $data->gateway->description @endphp</span></li>

                                    @if($method->gateway_parameter)
                                        @foreach(json_decode($method->gateway_parameter) as $k => $v)
                                            @if($v->type == "text")
                                                <li><span>{{__(inputTitle($v->field_level))}}</span>@if($v->validation == 'required') <span class="text-danger">*</span>  @endif</li>
                                                <li><input type="text" class="form-control" name="{{$k}}"  value="{{old($k)}}" placeholder="{{__($v->field_level)}}"></li>
                                            @elseif($v->type == "textarea")
                                                <li><span>{{__(inputTitle($v->field_level))}}</span>@if($v->validation == 'required') <span class="text-danger">*</span>  @endif</li>
                                                <li><textarea name="{{$k}}"  class="form-control"  placeholder="{{__($v->field_level)}}" rows="3">{{old($k)}}</textarea></li>
                                             @elseif($v->type == "file")
                                                <li><span>{{__($v->field_level)}}</span>@if($v->validation == 'required') <span class="text-danger">*</span>  @endif</li>
                                                <li><input type="file" class="form-control" name="{{$k}}" accept="image/*" required></li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                                <div class="payment-btn">
                                    <button class="cmn-btn btn-block" type="submit">@lang('Pay Now')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
