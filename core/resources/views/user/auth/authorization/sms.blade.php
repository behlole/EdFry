@extends('user.layouts.master')

@section('content')
    <div class="page-wrapper default-version">
        <div class="form-area bg_img" data-background="{{asset('assets/assistant/images/1.jpg')}}">
            <div class="form-wrapper">
                <h4 class="logo-text mb-15">@lang('Please Verify Your Mobile to Get Access')</h4>
                <h4>@lang('Your Mobile Number'): <strong>{{Auth::guard('user')->user()->mobile}}</strong></h4>
                <form action="{{route('user.verify_sms')}}" method="POST" class="cmn-form mt-30">
                    @csrf
                    <div class="form-group">
                        <div id="phoneInput">

                            <div class="field-wrapper">
                                <div class=" phone">
                                    <input type="text" name="sms_verified_code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="sms_verified_code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="sms_verified_code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="sms_verified_code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="sms_verified_code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="sms_verified_code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-between align-items-center">

                        <a href="{{route('user.send_verify_code')}}?type=phone" class="text-muted text--small">@lang('Try to send
                        again')</a>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn mt-25 b-radius--capsule">@lang('Verify Code') <i
                                class="las la-sign-in-alt"></i></button>
                    </div>
                </form>
            </div>
        </div><!-- login-area end -->
    </div>
@endsection
@push('script-lib')
    <script src="{{ asset($activeTemplateTrue.'js/jquery.inputLettering.js') }}"></script>
@endpush
@push('style')
    <style>

        #phoneInput .field-wrapper {
            position: relative;
            text-align: center;
        }

        #phoneInput .form-group {
            min-width: 300px;
            width: 50%;
            margin: 4em auto;
            display: flex;
            border: 1px solid rgba(96, 100, 104, 0.3);
        }

        #phoneInput .letter {
            height: 50px;
            border-radius: 0;
            text-align: center;
            max-width: calc((100% / 10) - 1px);
            flex-grow: 1;
            flex-shrink: 1;
            flex-basis: calc(100% / 10);
            outline-style: none;
            padding: 5px 0;
            font-size: 18px;
            font-weight: bold;
            color: red;
            border: 1px solid #0e0d35;
        }

        #phoneInput .letter + .letter {
        }

        @media (max-width: 480px) {
            #phoneInput .field-wrapper {
                width: 100%;
            }

            #phoneInput .letter {
                font-size: 16px;
                padding: 2px 0;
                height: 35px;
            }
        }

    </style>
@endpush

@push('script')
    <script>
        'use strict';

        (function ($) {
                $('#phoneInput').letteringInput({
                    inputClass: 'letter',
                    onLetterKeyup: function ($item, event) {

                    },
                    onSet: function ($el, event, value) {

                    }
                });
        })(jQuery);
    </script>
@endpush
