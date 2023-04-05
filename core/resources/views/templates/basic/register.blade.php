@extends('staff.layouts.master')

@section('content')
    <div class="page-wrapper default-version">
        <div class="form-area bg_img" style="background-color:white;">
            <div class="form-wrapper">
                <h4 class="logo-text mb-15">@lang('Welcome to') <strong>{{$general->sitename}}</strong></h4>
                <p>{{$page_title}} @lang('to Dashboard')</p>
                <form method="POST" class="cmn-form mt-30 route">
                    @csrf
                    <div class="form-group">
                        <label for="email">@lang('Username')</label>
                        <input type="text" name="username" class="form-control b-radius--capsule" id="username"
                               value="{{ old('username') }}"
                               placeholder="@lang('Enter your username')">
                        <i class="las la-user input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="firstname">@lang('First Name')</label>
                        <input type="text" name="firstname" class="form-control b-radius--capsule" id="username"
                               value="{{ old('firstname') }}"
                               placeholder="@lang('Enter your First Name')">
                        <i class="las la-user input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="firstname">@lang('Last Name')</label>
                        <input type="text" name="lastname" class="form-control b-radius--capsule" id="username"
                               value="{{ old('lastname') }}"
                               placeholder="@lang('Enter your Last Name')">
                        <i class="las la-user input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="email">@lang('Email')</label>
                        <input type="email" name="email" class="form-control b-radius--capsule" id="username"
                               value="{{ old('email') }}"
                               placeholder="@lang('Enter your Email')">
                        <i class="las la-user input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="mobile">@lang('Mobile Number')</label>
                        <input type="text" name="mobile" class="form-control b-radius--capsule" id="username"
                               value="{{ old('mobile') }}"
                               placeholder="@lang('Enter your Mobile Number')">
                        <i class="las la-user input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="pass">@lang('Password')</label>
                        <input type="password" name="password" class="form-control b-radius--capsule" id="pass"
                               placeholder="@lang('Enter your password')">
                        <i class="las la-lock input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="confirmpass">@lang('Confirm Password')</label>
                        <input type="password" name="confirmpass" class="form-control b-radius--capsule" id="pass"
                               placeholder="@lang('Confirm Your Password')">
                        <i class="las la-lock input-icon"></i>
                    </div>
                    <div class="form-group">

                        @php echo recaptcha() @endphp

                    </div>

                    @include($activeTemplate.'partials.custom-captcha')
                    <div class="form-group d-flex justify-content-between align-items-center">
                        <a class="text-muted text--small forget" href="{{route('login')}}"><i
                                class="las la-lock"></i>@lang('Login')
                        </a>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="submit-btn mt-25 b-radius--capsule">@lang('Register') <i
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
        $(document).ready(function () {

            var elemData = $("#access");
            var resourse = '{{ route('user.register') }}';
            $('.route').attr('action', resourse);

            $("#access").on('change', function () {
                var resourse = $(this).find('option:selected').data('route');
                $('.route').attr('action', resourse);
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
