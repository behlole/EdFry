<?php
namespace App\Http\Controllers\Assistant\Auth;

use App\GeneralSetting;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\AssistantLogin;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = 'assistant';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('assistant.guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $page_title = "Assistant Login";
        return view('assistant.auth.login', compact('page_title'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('assistant');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {


        $this->validateLogin($request);

        if(isset($request->captcha)){
            if(!captchaVerify($request->captcha, $request->captcha_secret)){
                $notify[] = ['error',"Invalid Captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }

//

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {

        $customRecaptcha = \App\Plugin::where('act', 'custom-captcha')->where('status', 1)->first();
        $validation_rule = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        if ($customRecaptcha) {
            $validation_rule['captcha'] = 'required';
        }

        $request->validate($validation_rule);

    }


    public function logout(Request $request)
    {
        $this->guard('assistant')->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect()->route('login');
    }

    public function resetPassword()
    {
        $page_title = 'Assistant Recovery';
        return view('assistant.reset', compact('page_title'));
    }

    public function authenticated(Request $request, $assistant)
    {
        if ($assistant->status == 0) {
            $this->guard()->logout();
            return redirect()->route('login')->withErrors(['Your account has been deactivated.']);
        }


        $assistant = Auth::guard('assistant')->user();
        $assistant->tv = $assistant->ts == 1 ? 0 : 1;
        $assistant->save();


        $info = json_decode(json_encode(getIpInfo()), true);
        $assistantLogin = new AssistantLogin();
        $assistantLogin->assistant_id = $assistant->id;
        $assistantLogin->assistant_ip =  request()->ip();
        $assistantLogin->longitude =  @implode(',',$info['long']);
        $assistantLogin->latitude =  @implode(',',$info['lat']);
        $assistantLogin->location =  @implode(',',$info['city']) . (" - ". @implode(',',$info['area']) ."- ") . @implode(',',$info['country']) . (" - ". @implode(',',$info['code']) . " ");
        $assistantLogin->country_code = @implode(',',$info['code']);
        $assistantLogin->browser = @$info['browser'];
        $assistantLogin->os = @$info['os_platform'];
        $assistantLogin->country =  @implode(',', $info['country']);
        $assistantLogin->save();

        return redirect()->route('assistant.dashboard');
    }
}
