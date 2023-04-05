<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\GoogleAuthenticator;
use Carbon\Carbon;
use Auth;
use Illuminate\Validation\ValidationException;

class DoctorAuthorizationController extends Controller
{
    public function __construct()
    {
        return $this->activeTemplate = activeTemplate();
    }
    public function checkValidCode($doctor, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$doctor->ver_code_send_at) return false;
        if ($doctor->ver_code_send_at->addMinutes($add_min) < Carbon::now()) return false;
        if ($doctor->ver_code !== $code) return false;
        return true;
    }


    public function authorizeForm()
    {

        if (auth()->guard('doctor')->check()) {
            $doctor = Auth::guard('doctor')->user();
            if (!$doctor->status) {
                Auth::logout();
            }elseif (!$doctor->ev) {
                if (!$this->checkValidCode($doctor, $doctor->ver_code)) {
                    $doctor->ver_code = verificationCode(6);
                    $doctor->ver_code_send_at = Carbon::now();
                    $doctor->save();
                    send_email($doctor, 'EVER_CODE', [
                        'code' => $doctor->ver_code
                    ]);
                }
                $page_title = 'Email verification form';
                return view('doctor.auth.authorization.email', compact('doctor', 'page_title'));
            }elseif (!$doctor->sv) {
                if (!$this->checkValidCode($doctor, $doctor->ver_code)) {
                    $doctor->ver_code = verificationCode(6);
                    $doctor->ver_code_send_at = Carbon::now();
                    $doctor->save();
                    send_sms($doctor, 'SVER_CODE', [
                        'code' => $doctor->ver_code
                    ]);
                }
                $page_title = 'SMS verification form';
                return view('doctor.auth.authorization.sms', compact('doctor', 'page_title'));
            }elseif (!$doctor->tv) {
                $page_title = 'Google Authenticator';
                return view('doctor.auth.authorization.2fa', compact('doctor', 'page_title'));
            }else{
                return redirect()->route('doctor.dashboard');
            }

        }

        return redirect()->route('doctor.login');
    }

    public function sendVerifyCode(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();


        if ($this->checkValidCode($doctor, $doctor->ver_code, 2)) {
            $target_time = $doctor->ver_code_send_at->addMinutes(2)->timestamp;
            $delay = $target_time - time();
            throw ValidationException::withMessages(['resend' => 'Please Try after ' . $delay . ' Seconds']);
        }
        if (!$this->checkValidCode($doctor, $doctor->ver_code)) {
            $doctor->ver_code = verificationCode(6);
            $doctor->ver_code_send_at = Carbon::now();
            $doctor->save();
        } else {
            $doctor->ver_code = $doctor->ver_code;
            $doctor->ver_code_send_at = Carbon::now();
            $doctor->save();
        }



        if ($request->type === 'email') {
            send_email($doctor, 'EVER_CODE',[
                'code' => $doctor->ver_code
            ]);

            $notify[] = ['success', 'Email verification code sent successfully'];
            return back()->withNotify($notify);
        } elseif ($request->type === 'phone') {
            send_sms($doctor, 'SVER_CODE', [
                'code' => $doctor->ver_code
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

        $doctor = Auth::guard('doctor')->user();

        if ($this->checkValidCode($doctor, $email_verified_code)) {
            $doctor->ev = 1;
            $doctor->ver_code = null;
            $doctor->ver_code_send_at = null;
            $doctor->save();
            return redirect()->intended(route('doctor.dashboard'));
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

        $doctor = Auth::guard('doctor')->user();
        if ($this->checkValidCode($doctor, $sms_verified_code)) {
            $doctor->sv = 1;
            $doctor->ver_code = null;
            $doctor->ver_code_send_at = null;
            $doctor->save();
            return redirect()->intended(route('doctor.dashboard'));
        }
        throw ValidationException::withMessages(['sms_verified_code' => 'Verification code didn\'t match!']);
    }
    public function g2faVerification(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();

        $this->validate(
            $request, [
            'code.*' => 'required',
        ], [
            'code.*.required' => 'Code is required',
        ]);

        $ga = new GoogleAuthenticator();


        $code =  str_replace(',','',implode(',',$request->code));

        $secret = $doctor->tsc;
        $oneCode = $ga->getCode($secret);
        $doctorCode = $code;
        if ($oneCode == $doctorCode) {
            $doctor->tv = 1;
            $doctor->save();
            return redirect()->route('doctor.dashboard');
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }
}
