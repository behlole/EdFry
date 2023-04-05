@extends('staff.layouts.master')

@section('content')
<div class="page-wrapper default-version">
    <div class="form-area bg_img" data-background="{{asset('assets/staff/images/1.jpg')}}">
        <div class="form-wrapper">
            <h4 class="logo-text mb-15">@lang('Welcome to') <strong>{{$general->sitename}}</strong></h4>
            <p>{{$page_title}} @lang('to Dashboard')</p>
            <form  method="POST" class="cmn-form mt-30 route">
                @csrf
                <div class="form-group">
                    <label for="email">@lang('Select Access')</label>
                    <select name="" id="access" class="form-control b-radius--capsule">
                        <option value="" data-route="{{ route('doctor.login') }}" data-forget="{{ route('doctor.password.reset') }}">@lang('Doctor')</option>
                        <option value="" data-route="{{ route('assistant.login') }}" data-forget="{{ route('assistant.password.reset') }}">@lang('Assistant')</option>
                        <option value="" data-route="{{ route('staff.login') }}" data-forget="{{ route('staff.password.reset') }}">@lang('Staff')</option>
                    </select>
                    <i class="las la-user input-icon"></i>
                </div>
                <div class="form-group">
                    <label for="email">@lang('Username')</label>
                    <input type="text" name="username" class="form-control b-radius--capsule" id="username" value="{{ old('username') }}"
                           placeholder="@lang('Enter your username')">
                    <i class="las la-user input-icon"></i>
                </div>
                <div class="form-group">
                    <label for="pass">@lang('Password')</label>
                    <input type="password" name="password" class="form-control b-radius--capsule" id="pass"
                           placeholder="@lang('Enter your password')">
                    <i class="las la-lock input-icon"></i>
                </div>

                <div class="form-group">

                    @php echo recaptcha() @endphp

                </div>

                @include($activeTemplate.'partials.custom-captcha')
                <div class="form-group d-flex justify-content-between align-items-center">
                    <a class="text-muted text--small forget"><i class="las la-lock"></i>@lang('Forgot password?')</a>
                </div>
                <div class="form-group">
                    <button type="submit" class="submit-btn mt-25 b-radius--capsule">@lang('Login') <i
                            class="las la-sign-in-alt"></i></button>
                </div>
            </form>
        </div>
    </div><!-- login-area end -->
</div>
@endsection

@push('script')
    <script>
        'use strict';
        $(document).ready(function(){

            var elemData = $( "#access" );
            var resourse = elemData.find('option:selected').data('route');
            var forget = elemData.find('option:selected').data('forget');
            $('.route').attr('action',resourse);
            $(".forget").attr("href", forget);

            $( "#access" ).on('change',function() {
                var resourse = $(this).find('option:selected').data('route');
                var forget = $(this).find('option:selected').data('forget');
                $('.route').attr('action',resourse);
                $(".forget").attr("href", forget);
            });

            function submitUserForm() {
                var response = grecaptcha.getResponse();
                if (response.length == 0) {
                    document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">@lang("Captcha field is required.")</span>';
                    return false;
                }
                return true;
            }
            function verifyCaptcha() {
                document.getElementById('g-recaptcha-error').innerHTML = '';
            }
        });
    </script>
@endpush
