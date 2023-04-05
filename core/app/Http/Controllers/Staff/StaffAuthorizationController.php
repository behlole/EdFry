<?php

namespace App\Http\Controllers\Staff;

use App\Lib\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Validation\ValidationException;

class StaffAuthorizationController extends Controller
{
    public function __construct()
    {
        return $this->activeTemplate = activeTemplate();
    }
    public function checkValidCode($staff, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$staff->ver_code_send_at) return false;
        if ($staff->ver_code_send_at->addMinutes($add_min) < Carbon::now()) return false;
        if ($staff->ver_code !== $code) return false;
        return true;
    }


    public function authorizeForm()
    {

        if (auth()->guard('staff')->check()) {
            $staff = Auth::guard('staff')->user();
            if (!$staff->status) {
                Auth::logout();
            }elseif (!$staff->ev) {
                if (!$this->checkValidCode($staff, $staff->ver_code)) {
                    $staff->ver_code = verificationCode(6);
                    $staff->ver_code_send_at = Carbon::now();
                    $staff->save();
                    send_email($staff, 'EVER_CODE', [
                        'code' => $staff->ver_code
                    ]);
                }
                $page_title = 'Email verification form';
                return view('staff.auth.authorization.email', compact('staff', 'page_title'));
            }elseif (!$staff->sv) {
                if (!$this->checkValidCode($staff, $staff->ver_code)) {
                    $staff->ver_code = verificationCode(6);
                    $staff->ver_code_send_at = Carbon::now();
                    $staff->save();
                    send_sms($staff, 'SVER_CODE', [
                        'code' => $staff->ver_code
                    ]);
                }
                $page_title = 'SMS verification form';
                return view('staff.auth.authorization.sms', compact('staff', 'page_title'));
            }elseif (!$staff->tv) {
                $page_title = 'Google Authenticator';
                return view('staff.auth.authorization.2fa', compact('staff', 'page_title'));
            }else{
                return redirect()->route('staff.dashboard');
            }

        }

        return redirect()->route('staff.login');
    }

    public function sendVerifyCode(Request $request)
    {
        $staff = Auth::guard('staff')->user();


        if ($this->checkValidCode($staff, $staff->ver_code, 2)) {
            $target_time = $staff->ver_code_send_at->addMinutes(2)->timestamp;
            $delay = $target_time - time();
            throw ValidationException::withMessages(['resend' => 'Please Try after ' . $delay . ' Seconds']);
        }
        if (!$this->checkValidCode($staff, $staff->ver_code)) {
            $staff->ver_code = verificationCode(6);
            $staff->ver_code_send_at = Carbon::now();
            $staff->save();
        } else {
            $staff->ver_code = $staff->ver_code;
            $staff->ver_code_send_at = Carbon::now();
            $staff->save();
        }



        if ($request->type === 'email') {
            send_email($staff, 'EVER_CODE',[
                'code' => $staff->ver_code
            ]);

            $notify[] = ['success', 'Email verification code sent successfully'];
            return back()->withNotify($notify);
        } elseif ($request->type === 'phone') {
            send_sms($staff, 'SVER_CODE', [
                'code' => $staff->ver_code
            ]);
            $notify[] = ['success', 'SMS verification code sent successfully'];
            return back()->withNotify($notify);
        } else {
            throw ValidationException::withMessages(['resend' => 'Sending Failed']);
        }
    }

    public function emailVerification(Request $request)
    {
        $rules = [
            'email_verified_code.*' => 'required',
        ];
        $msg = [
            'email_verified_code.*.required' => 'Email verification code is required',
        ];
        $validate = $request->validate($rules, $msg);


        $email_verified_code =  str_replace(',','',implode(',',$request->email_verified_code));

        $staff = Auth::guard('staff')->user();

        if ($this->checkValidCode($staff, $email_verified_code)) {
            $staff->ev = 1;
            $staff->ver_code = null;
            $staff->ver_code_send_at = null;
            $staff->save();
            return redirect()->intended(route('staff.dashboard'));
        }
        throw ValidationException::withMessages(['email_verified_code' => 'Verification code didn\'t match!']);
    }

    public function smsVerification(Request $request)
    {
        $request->validate([
            'sms_verified_code.*' => 'required',
        ], [
            'sms_verified_code.*.required' => 'SMS verification code is required',
        ]);


        $sms_verified_code =  str_replace(',','',implode(',',$request->sms_verified_code));

        $staff = Auth::guard('staff')->user();
        if ($this->checkValidCode($staff, $sms_verified_code)) {
            $staff->sv = 1;
            $staff->ver_code = null;
            $staff->ver_code_send_at = null;
            $staff->save();
            return redirect()->intended(route('staff.dashboard'));
        }
        throw ValidationException::withMessages(['sms_verified_code' => 'Verification code didn\'t match!']);
    }
    public function g2faVerification(Request $request)
    {
        $staff = Auth::guard('staff')->user();

        $this->validate(
            $request, [
            'code.*' => 'required',
        ], [
            'code.*.required' => 'Code is required',
        ]);

        $ga = new GoogleAuthenticator();


        $code =  str_replace(',','',implode(',',$request->code));

        $secret = $staff->tsc;
        $oneCode = $ga->getCode($secret);
        $staffCode = $code;
        if ($oneCode == $staffCode) {
            $staff->tv = 1;
            $staff->save();
            return redirect()->route('staff.dashboard');
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }

}
