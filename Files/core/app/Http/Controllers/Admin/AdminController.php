<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\Assistant;
use App\Deposit;
use App\Doctor;
use App\DoctorLogin;
use App\Gateway;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Staff;

class AdminController extends Controller
{

    public function dashboard()
    {
        $page_title = 'Dashboard';

        $page_title = 'Dashboard';

        //Info
        $widget['total_doctors'] = Doctor::count();
        $widget['total_assistants'] = Assistant::count();
        $widget['total_staff'] = Staff::count();
        $widget['new_appointments'] = Appointment::where('try',1)->where('is_complete',0)->where('d_status',0)->where('is_complete',0)->whereHas('doctor', function ($query) {
            $query->where('status',1);
       })->count();
        $widget['done_appointments'] = Appointment::where('try',1)->where('is_complete',1)->where('d_status',0)->where('is_complete',0)->whereHas('doctor', function ($query) {
            $query->where('status',1);
       })->count();
        $widget['trashed_appointments'] = Appointment::where('d_status',1)->where('is_complete',0)->whereHas('doctor', function ($query) {
            $query->where('status',1);
       })->count();
        $widget['email_verified_doctors'] = Doctor::where('ev', 1)->count();
        $widget['sms_verified_doctors'] = Doctor::where('sv', 1)->count();

        $appointment_chart = Appointment::where('try',1)->whereYear('created_at', '=', date('Y'))->orderBy('created_at')->get()->groupBy(function ($d) {
            return $d->created_at->format('F');
        });

        $appointment_all = [];
        $month_appointment = [];
        foreach ($appointment_chart as $key => $value) {
            $appointment_all[] = count($value);
            $month_appointment[] = $key;
        }

        // Monthly Online Payment
        $report['months'] = collect([]);
        $report['deposit_month_amount'] = collect([]);

        $depositsMonth = Deposit::whereYear('created_at', '>=', Carbon::now()->subYear())
            ->selectRaw("SUM( CASE WHEN status = 1 THEN amount END) as depositAmount")
            ->selectRaw("DATE_FORMAT(created_at,'%M') as months")
            ->orderBy('created_at')
            ->groupBy(DB::Raw("MONTH(created_at)"))->get();

        $depositsMonth->map(function ($aaa) use ($report) {
            $report['months']->push($aaa->months);
            $report['deposit_month_amount']->push(getAmount($aaa->depositAmount));
        });



        // user Browsing, Country, Operating Log
        $doctor_login_data = DoctorLogin::whereDate('created_at', '>=', \Carbon\Carbon::now()->subDay(30))->get(['browser', 'os', 'country']);

        $chart['doctor_browser_counter'] = $doctor_login_data->groupBy('browser')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['doctor_os_counter'] = $doctor_login_data->groupBy('os')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['doctor_country_counter'] = $doctor_login_data->groupBy('country')->map(function ($item, $key) {
            return collect($item)->count();
        })->sort()->reverse()->take(5);


        $payment['payment_method'] = Gateway::count();
        $payment['total_deposit_amount'] = Deposit::where('status',1)->sum('amount');
        $payment['total_deposit_charge'] = Deposit::where('status',1)->sum('charge');
        $payment['total_deposit_pending'] = Deposit::where('status',2)->count();


        $latestDoctors = Doctor::latest()->limit(6)->get();
        $empty_message = 'No doctor found';
        return view('admin.dashboard', compact('page_title', 'widget', 'report', 'chart','payment','latestDoctors','empty_message','appointment_all','month_appointment'));
    }


    public function profile()
    {
        $page_title = 'Profile';
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('page_title', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'image' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $user = Auth::guard('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image ?: null;
                $user->image = uploadImage($request->image, 'assets/admin/images/profile/', '400X400', $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('admin.profile')->withNotify($notify);
    }


    public function password()
    {
        $page_title = 'Password Setting';
        $admin = Auth::guard('admin')->user();
        return view('admin.password', compact('page_title', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = Auth::guard('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }
        $user->update([
            'password' => bcrypt($request->password)
        ]);
        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('admin.password')->withNotify($notify);
    }


}
