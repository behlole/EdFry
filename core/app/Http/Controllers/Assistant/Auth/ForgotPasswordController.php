<?php

namespace App\Http\Controllers\Assistant\Auth;

use App\Assistant;
use App\AssistantPasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
        |--------------------------------------------------------------------------
        | Password Reset Controller
        |--------------------------------------------------------------------------
        |
        | This controller is responsible for handling password reset emails and
        | includes a trait which assists in sending these notifications from
        | your application to your users. Feel free to explore this trait.
        |
        */

    use SendsPasswordResetEmails;

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
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        $page_title = 'Account Recovery';
        return view('assistant.auth.passwords.email', compact('page_title'));
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

    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);



        $assistant = Assistant::where('email', $request->email)->first();
        if ($assistant == null) {
            return back()->withErrors(['Email Not Available']);
        }

        $code = verificationCode(6);

        AssistantPasswordReset::create([
            'email' => $assistant->email,
            'token' => $code,
            'status' => 0,
            'created_at' => date("Y-m-d h:i:s")
        ]);

        $assistantAgent = getIpInfo();
        send_email($assistant, 'PASS_RESET_CODE', [
            'code' => $code,
            'operating_system' => $assistantAgent['os_platform'],
            'browser' => $assistantAgent['browser'],
            'ip' => $assistantAgent['ip'],
            'time' => $assistantAgent['time']
        ]);

        $page_title = 'Account Recovery';
        $notify[] = ['success', 'Password reset email sent successfully'];
        return view('assistant.auth.passwords.code_verify', compact('page_title', 'notify'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code.*' => 'required']);
        $notify[] = ['success', 'You can change your password.'];

        $code =  str_replace(',','',implode(',',$request->code));

        return redirect()->route('assistant.password.change-link', $code)->withNotify($notify);
    }
}
