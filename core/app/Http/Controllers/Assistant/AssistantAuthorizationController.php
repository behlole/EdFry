<?php

namespace App\Http\Controllers\Assistant;

use App\Lib\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Validation\ValidationException;

class AssistantAuthorizationController extends Controller
{
    public function __construct()
    {
        return $this->activeTemplate = activeTemplate();
    }
    public function checkValidCode($assistant, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$assistant->ver_code_send_at) return false;
        if ($assistant->ver_code_send_at->addMinutes($add_min) < Carbon::now()) return false;
        if ($assistant->ver_code !== $code) return false;
        return true;
    }


    public function authorizeForm()
    {

        if (auth()->guard('assistant')->check()) {
            $assistant = Auth::guard('assistant')->user();
            if (!$assistant->status) {
                Auth::logout();
            }elseif (!$assistant->ev) {
                if (!$this->checkValidCode($assistant, $assistant->ver_code)) {
                    $assistant->ver_code = verificationCode(6);
                    $assistant->ver_code_send_at = Carbon::now();
                    $assistant->save();
                    send_email($assistant, 'EVER_CODE', [
                        'code' => $assistant->ver_code
                    ]);
                }
                $page_title = 'Email verification form';
                return view('assistant.auth.authorization.email', compact('assistant', 'page_title'));
            }elseif (!$assistant->sv) {
                if (!$this->checkValidCode($assistant, $assistant->ver_code)) {
                    $assistant->ver_code = verificationCode(6);
                    $assistant->ver_code_send_at = Carbon::now();
                    $assistant->save();
                    send_sms($assistant, 'SVER_CODE', [
                        'code' => $assistant->ver_code
                    ]);
                }
                $page_title = 'SMS verification form';
                return view('assistant.auth.authorization.sms', compact('assistant', 'page_title'));
            }elseif (!$assistant->tv) {
                $page_title = 'Google Authenticator';
                return view('assistant.auth.authorization.2fa', compact('assistant', 'page_title'));
            }else{
                return redirect()->route('assistant.dashboard');
            }

        }

        return redirect()->route('assistant.login');
    }

    public function sendVerifyCode(Request $request)
    {
        $assistant = Auth::guard('assistant')->user();


        if ($this->checkValidCode($assistant, $assistant->ver_code, 2)) {
            $target_time = $assistant->ver_code_send_at->addMinutes(2)->timestamp;
            $delay = $target_time - time();
            throw ValidationException::withMessages(['resend' => 'Please Try after ' . $delay . ' Seconds']);
        }
        if (!$this->checkValidCode($assistant, $assistant->ver_code)) {
            $assistant->ver_code = verificationCode(6);
            $assistant->ver_code_send_at = Carbon::now();
            $assistant->save();
        } else {
            $assistant->ver_code = $assistant->ver_code;
            $assistant->ver_code_send_at = Carbon::now();
            $assistant->save();
        }



        if ($request->type === 'email') {
            send_email($assistant, 'EVER_CODE',[
                'code' => $assistant->ver_code
            ]);

            $notify[] = ['success', 'Email verification code sent successfully'];
            return back()->withNotify($notify);
        } elseif ($request->type === 'phone') {
            send_sms($assistant, 'SVER_CODE', [
                'code' => $assistant->ver_code
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

        $assistant = Auth::guard('assistant')->user();

        if ($this->checkValidCode($assistant, $email_verified_code)) {
            $assistant->ev = 1;
            $assistant->ver_code = null;
            $assistant->ver_code_send_at = null;
            $assistant->save();
            return redirect()->intended(route('assistant.dashboard'));
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

        $assistant = Auth::guard('assistant')->user();
        if ($this->checkValidCode($assistant, $sms_verified_code)) {
            $assistant->sv = 1;
            $assistant->ver_code = null;
            $assistant->ver_code_send_at = null;
            $assistant->save();
            return redirect()->intended(route('assistant.dashboard'));
        }
        throw ValidationException::withMessages(['sms_verified_code' => 'Verification code didn\'t match!']);
    }
    public function g2faVerification(Request $request)
    {
        $assistant = Auth::guard('assistant')->user();

        $this->validate(
            $request, [
            'code.*' => 'required',
        ], [
            'code.*.required' => 'Code is required',
        ]);

        $ga = new GoogleAuthenticator();


        $code =  str_replace(',','',implode(',',$request->code));

        $secret = $assistant->tsc;
        $oneCode = $ga->getCode($secret);
        $assistantCode = $code;
        if ($oneCode == $assistantCode) {
            $assistant->tv = 1;
            $assistant->save();
            return redirect()->route('assistant.dashboard');
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }

}
