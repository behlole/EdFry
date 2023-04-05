<?php

namespace App\Http\Controllers\Assistant\Auth;

use App\Assistant;
use App\AssistantPasswordReset;
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
    public $redirectTo = '/assistant/dashboard';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('assistant.guest');
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
        $tk = AssistantPasswordReset::where('token', $token)->where('status', 0)->first();

        if (empty($tk)) {
            $notify[] = ['error', 'Token Not Found!'];
            return redirect()->route('assistant.password.reset')->withNotify($notify);
        }
        $email = $tk->email;
        return view('assistant.auth.passwords.reset', compact('page_title', 'email', 'token'));
    }


    public function reset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:4',
        ]);

        $reset = AssistantPasswordReset::where('token', $request->token)->orderBy('created_at', 'desc')->first();
        $assistant = Assistant::where('email', $reset->email)->first();
        if ($reset->status == 1) {
            $notify[] = ['error', 'Invalid code'];
            return redirect()->route('assistant.login')->withNotify($notify);
        }

        $assistant->password = bcrypt($request->password);
        $assistant->save();
        AssistantPasswordReset::where('email', $assistant->email)->update(['status' => 1]);

        $assistantAgent = getIpInfo();
        send_email($assistant, 'PASS_RESET_DONE', [
            'operating_system' => $assistantAgent['os_platform'],
            'browser' => $assistantAgent['browser'],
            'ip' => $assistantAgent['ip'],
            'time' => $assistantAgent['time']
        ]);

        $notify[] = ['success', 'Password Changed'];
        return redirect()->route('assistant.login')->withNotify($notify);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('assistants');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('assistant');
    }
}
