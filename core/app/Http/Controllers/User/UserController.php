<?php

namespace App\Http\Controllers\User;

use App\Appointment;
use App\AssistantDoctorTrack;
use App\Doctor;
use App\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Lib\GoogleAuthenticator;
use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Facades\Auth;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function dashboard()
    {
        /**
         * TODO: implement dashboard of user
         */
        $page_title = 'Dashboard';
        $user = Auth::guard('user')->user();
        return view('user.dashboard', compact('page_title'));

//        return redirect('/');
    }

    public function profile()
    {
        $page_title = 'Profile';
        $user = Auth::guard('user')->user();
        return view('user.profile', compact('page_title', 'user'));
    }
    public function allAppointment()
    {
        $page_title = 'All Appointments';
        $appointments = Appointment::where('user_id', Auth::guard('user')->user()->id)->where('try', 1)->where('is_complete', 0)->where('d_status', 0)->whereHas('relationUser', function ($query) {
            $query->where('status', 1);
        })->latest()->paginate(getPaginate());
        // dd(Appointment::where('user_id',Auth::guard('user')->user()->id)->where('try', 1)->where('is_complete', 0)->where('d_status', 0)->latest()->paginate(getPaginate()));
        $empty_message = 'No Appointment Found';
        return view('user.appointment.appointment', compact('page_title', 'appointments', 'empty_message'));
    }
    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'image' => [new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ]);

        $user = Auth::guard('user')->user();

        $user_image = $user->image;
        if ($request->hasFile('image')) {
            try {

                $location = imagePath()['assistant']['path'];
                $size = imagePath()['assistant']['size'];
                $old = $user->image;
                $user_image = uploadImage($request->image, $location, $size, $old);

            } catch (\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $user->image = $user_image;
        $user->firstname=$request->firstname;
        $user->lastname=$request->lastname;
        $user->mobile=$request->mobile;
        $user->save();

        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('user.profile')->withNotify($notify);
    }

    public function password()
    {
        $page_title = 'Password Setting';
        $user = Auth::guard('user')->user();
        return view('user.password', compact('page_title', 'user'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::guard('user')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('user.password')->withNotify($notify);
    }

    public function createAppointment()
    {
        $page_title = 'Create Appoiment';
        $user = Auth::guard('user')->user();
        $doctors = $user->doctors()->where('status', 1)->get();
        return view('user.appointment.create-appointment', compact('page_title', 'doctors'));
    }

    public function bookedDate(Request $request)
    {
        $data = Appointment::where('doctor_id', $request->doctor_id)->where('try', 1)->where('d_status', 0)->whereDate('booking_date', Carbon::parse($request->date))->get()->map(function ($item) {
            return str_slug($item->time_serial);
        });
        return response()->json(@$data);
    }

    public function appointmentDetails(Request $request)
    {

        $request->validate([
            'doctor_id' => 'required|numeric|gt:0',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);


        if ($doctor->status == 0) {
            $notify[] = ['error', 'This doctor is banned'];
            return redirect()->route('user.appointments.create')->withNotify($notify);
        }

        $check = AssistantDoctorTrack::where('user_id', Auth::guard('user')->user()->id)->where('doctor_id', $doctor->id)->count();

        if ($check <= 0) {
            $notify[] = ['error', 'You are not authorized to acceass this'];
            return back()->withNotify($notify);
        }

        if ($doctor->serial_or_slot == null || empty($doctor->serial_or_slot)) {
            $notify[] = ['error', 'No available schedule for this doctor'];
            return back()->withNotify($notify);
        }

        $available_date = [];
        $date = Carbon::now();

        for ($i = 0; $i < $doctor->serial_day; $i++) {
            array_push($available_date, date('Y-m-d', strtotime($date)));
            $date->addDays(1);
        }

        $page_title = 'Appointment Booking';

        return view('user.appointment.book-appointment', compact('doctor', 'page_title', 'available_date'));
    }

    public function appointmentStore(Request $request, $id)
    {

        $this->validate($request, [
            'booking_date' => 'required|date',
            'time_serial' => 'required',
            'name' => 'required|max:50',
            'email' => 'required|email',
            'mobile' => 'required|max:50',
            'age' => 'required|numeric|gt:0',
        ], [
            'time_serial.required' => 'You did not select any time or serial',
        ]);

        $doctor = Doctor::findOrFail($id);

        $check = AssistantDoctorTrack::where('user_id', Auth::guard('user')->user()->id)->where('doctor_id', $doctor->id)->count();

        if ($check <= 0) {
            $notify[] = ['error', 'You are not authorized to acceass this'];
            return back()->withNotify($notify);
        }

        $time_serial_check = $doctor->whereJsonContains('serial_or_slot', $request->time_serial)->first();

        if ($time_serial_check) {
            $existed_appointment = Appointment::where('doctor_id', $doctor->id)->where('booking_date', $request->booking_date)->where('time_serial', $request->time_serial)->where('try', 1)->where('d_status', 0)->first();

            if ($existed_appointment) {
                $notify[] = ['error', 'This appointment is already booked. Try another date or serial'];
                return back()->withNotify($notify);
            }

            $general = GeneralSetting::first();

            $appointment = Appointment::create([
                'booking_date' => Carbon::parse($request->booking_date)->format('Y-m-d'),
                'time_serial' => $request->time_serial,
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'age' => $request->age,
                'doctor_id' => $doctor->id,
                'user' => Auth::guard('user')->user()->id,
                'disease' => $request->disease,
                'try' => 1,
            ]);

            $patient = 1;
            notify($appointment, 'APPOINTMENT_CONFIRM', [
                'booking_date' => $appointment->booking_date,
                'time_serial' => $appointment->time_serial,
                'doctor_name' => $doctor->name,
                'doctor_fees' => '' . $doctor->fees . ' ' . $general->cur_text . '',
            ], $patient);

            $notify[] = ['success', 'Your appointment has been taken.'];
            return back()->withNotify($notify);

        } else {
            $notify[] = ['error', 'Do not try to cheat us'];
            return back()->withNotify($notify);
        }

    }

    public function allDoctors()
    {
        $page_title = 'All Doctors Under You';
        $user = Auth::guard('user')->user();
        $doctors = $user->doctors()->where('status', 1)->get();
        $empty_message = 'No doctor assigned yet';
        return view('user.doctor', compact('page_title', 'doctors', 'empty_message'));

    }


    public function doctorAppointment($id)
    {

        $doctor = Doctor::findOrFail($id);
        $check = AssistantDoctorTrack::where('user_id', Auth::guard('user')->user()->id)->where('doctor_id', $doctor->id)->count();

        if ($check <= 0) {
            $notify[] = ['error', 'You are not authorized to acceass this'];
            return back()->withNotify($notify);
        }

        $page_title = '' . $doctor->name . ' - Appointments';

        $appointments = Appointment::where('doctor_id', $doctor->id)->where('try', 1)->where('is_complete', 0)->where('d_status', 0)->latest()->paginate(getPaginate());

        $empty_message = 'No Appointment Found';

        return view('user.appointment.appointment', compact('page_title', 'appointments', 'empty_message'));
    }

    public function appointmentView(Request $request, $id)
    {

        $appointment = Appointment::findOrFail($id);

        if ($request->complete) {
            $appointment->is_complete = 1;

            if ($appointment->p_status == 0) {
                $doctor = Doctor::findOrFail($appointment->doctor->id);
                $doctor->balance += $doctor->fees;
                $doctor->save();

                $appointment->p_status = 1;
            }

            $appointment->save();

            $notify[] = ['success', 'Service Done Successfully'];
            return back()->withNotify($notify);
        }
    }

    public function appointmentDone($id)
    {
        $doctor = Doctor::findOrFail($id);
        $check = AssistantDoctorTrack::where('user_id', Auth::guard('user')->user()->id)->where('doctor_id', $doctor->id)->count();

        if ($check <= 0) {
            $notify[] = ['error', 'You are not authorized to acceass this'];
            return back()->withNotify($notify);
        }

        $page_title = '' . $doctor->name . ' - Done Appointments';
        $empty_message = 'No Done Appointment Found';

        $appointments = Appointment::where('doctor_id', $doctor->id)->where('try', 1)->where('is_complete', 1)->where('d_status', 0)->latest()->paginate(getPaginate());

        return view('user.appointment.appointment', compact('page_title', 'appointments', 'empty_message'));
    }

    public function appointmentRemove($id)
    {
        $appointment = Appointment::findOrFail($id);
        $check = AssistantDoctorTrack::where('user_id', Auth::guard('user')->user()->id)->where('doctor_id', $appointment->doctor->id)->count();

        if ($check <= 0) {
            $notify[] = ['error', 'You are not authorized to acceass this'];
            return back()->withNotify($notify);
        }

        $appointment->d_status = 1;
        $appointment->d_user = Auth::guard('user')->user()->id;
        $appointment->save();


        $patient = 1;
        notify($appointment, 'APPOINTMENT_REJECT', [
            'booking_date' => $appointment->booking_date,
            'time_serial' => $appointment->time_serial,
            'doctor_name' => $appointment->doctor->name,
        ], $patient);

        $notify[] = ['success', 'Your appointment goes in trashed appointments'];
        return back()->withNotify($notify);
    }

    public function appointmentTrashed($id)
    {
        $doctor = Doctor::findOrFail($id);
        $check = AssistantDoctorTrack::where('user_id', Auth::guard('user')->user()->id)->where('doctor_id', $doctor->id)->count();

        if ($check <= 0) {
            $notify[] = ['error', 'You are not authorized to acceass this'];
            return back()->withNotify($notify);
        }

        $page_title = '' . $doctor->name . ' - Trashed Appointments';
        $empty_message = 'No Done Trashed Appointment Found';

        $appointments = Appointment::where('doctor_id', $doctor->id)->where('d_status', 1)->latest()->paginate(getPaginate());
        $empty_message = 'No Trashed Appointment Found';

        return view('user.appointment.trashed-appointment', compact('page_title', 'appointments', 'empty_message'));
    }

    public function show2faForm()
    {
        $gnl = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $user = Auth::guard('user')->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $gnl->sitename, $secret);
        $prevcode = $user->tsc;
        $prevqr = $ga->getQRCodeGoogleUrl($user->username . '@' . $gnl->sitename, $prevcode);
        $page_title = 'Two Factor';
        return view('user.twofactor', compact('page_title', 'secret', 'qrCodeUrl', 'prevcode', 'prevqr'));
    }

    public function create2fa(Request $request)
    {
        $user = Auth::guard('user')->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);

        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        if ($oneCode === $request->code) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->tv = 1;
            $user->save();


            $userAgent = getIpInfo();
            send_email($user, '2FA_ENABLE', [
                'operating_system' => $userAgent['os_platform'],
                'browser' => $userAgent['browser'],
                'ip' => $userAgent['ip'],
                'time' => $userAgent['time']
            ]);
            send_sms($user, '2FA_ENABLE', [
                'operating_system' => $userAgent['os_platform'],
                'browser' => $userAgent['browser'],
                'ip' => $userAgent['ip'],
                'time' => $userAgent['time']
            ]);


            $notify[] = ['success', 'Google Authenticator Enabled Successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = Auth::guard('user')->user();
        $ga = new GoogleAuthenticator();

        $secret = $user->tsc;
        $oneCode = $ga->getCode($secret);
        $userCode = $request->code;

        if ($oneCode == $userCode) {

            $user->tsc = null;
            $user->ts = 0;
            $user->tv = 1;
            $user->save();


            $userAgent = getIpInfo();
            send_email($user, '2FA_DISABLE', [
                'operating_system' => $userAgent['os_platform'],
                'browser' => $userAgent['browser'],
                'ip' => $userAgent['ip'],
                'time' => $userAgent['time']
            ]);
            send_sms($user, '2FA_DISABLE', [
                'operating_system' => $userAgent['os_platform'],
                'browser' => $userAgent['browser'],
                'ip' => $userAgent['ip'],
                'time' => $userAgent['time']
            ]);


            $notify[] = ['success', 'Two Factor Authenticator Disable Successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }
}
