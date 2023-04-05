<?php

namespace App\Http\Controllers\Doctor\Auth;

use App\Doctor;
use App\DoctorPasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;


class ResetPasswordController extends Controller
{
    /*
        |--------------------------------------------------------------------------
        | Password Reset Controller
        |--------------------------------------------------------------------------
        |
        | This controller is responsible for handling password reset requests
        | and uses a simple trait to include this behavior. You're free to
        | explore this trait and override any methods you wish to tweak.
        |
        */

    use ResetsPasswords;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/doctor/dashboard';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('doctor.guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token)
    {
        $page_title = "Account Recovery";
        $tk = DoctorPasswordReset::where('token', $token)->where('status', 0)->first();

        if (empty($tk)) {
            $notify[] = ['error', 'Token Not Found!'];
            return redirect()->route('doctor.password.reset')->withNotify($notify);
        }
        $email = $tk->email;
        return view('doctor.auth.passwords.reset', compact('page_title', 'email', 'token'));
    }


    public function reset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:4',
        ]);

        $reset = DoctorPasswordReset::where('token', $request->token)->orderBy('created_at', 'desc')->first();
        $doctor = Doctor::where('email', $reset->email)->first();
        if ($reset->status == 1) {
            $notify[] = ['error', 'Invalid code'];
            return redirect()->route('doctor.login')->withNotify($notify);
        }

        $doctor->password = bcrypt($request->password);
        $doctor->save();
        DoctorPasswordReset::where('email', $doctor->email)->update(['status' => 1]);

        $doctorAgent = getIpInfo();
        send_email($doctor, 'PASS_RESET_DONE', [
            'operating_system' => $doctorAgent['os_platform'],
            'browser' => $doctorAgent['browser'],
            'ip' => $doctorAgent['ip'],
            'time' => $doctorAgent['time']
        ]);

        $notify[] = ['success', 'Password Changed'];
        return redirect()->route('doctor.login')->withNotify($notify);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('doctors');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('doctor');
    }
}
