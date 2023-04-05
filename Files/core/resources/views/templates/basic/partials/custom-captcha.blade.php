@if(\App\Plugin::where('act', 'custom-captcha')->where('status', 1)->first())

        @php echo  getCustomCaptcha() @endphp

        <div class="form-group mt-1">
            <label for="">@lang('Enter The Code')</label>
            <input type="text" name="captcha" class="form-control b-radius--capsule" id="pass" required>
            <i class="las la-code input-icon"></i>
        </div>
@endif
