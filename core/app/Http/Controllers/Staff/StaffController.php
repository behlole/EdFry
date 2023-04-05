<?php

namespace App\Http\Controllers\Staff;

use App\Appointment;
use App\Doctor;
use App\Staff;
use App\GeneralSetting;
use App\Http\Controllers\Controller;
use App\Lib\GoogleAuthenticator;
use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Facades\Auth;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{

    public function dashboard()
    {
        $page_title = 'Dashboard';

        $new_appointment = Appointment::where('staff',Auth::guard('staff')->user()->id)->where('try',1)->where('d_status',0)->where('is_complete',0)->whereHas('relationStaff', function ($query) {
            $query->where('status',1);
        })->count();

        $appointment_done = Appointment::where('staff',Auth::guard('staff')->user()->id)->where('try',1)->where('is_complete',1)->where('p_status',1)->where('d_status',0)->latest()->whereHas('relationStaff', function ($query) {
            $query->where('status',1);
       })->count();

        $total_appointment = Appointment::where('staff',Auth::guard('staff')->user()->id)->where('try',1)->where('d_status',0)->latest()->where('is_complete',0)->whereHas('relationStaff', function ($query) {
            $query->where('status',1);
       })->count();

        return   view('staff.dashboard',compact('page_title','new_appointment','appointment_done','total_appointment'));
    }
    public function profile()
    {
        $page_title = 'Profile';
        $staff = Auth::guard('staff')->user();
        return view('staff.profile', compact('page_title', 'staff'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'image' => [new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ]);

        $staff = Auth::guard('staff')->user();

        $staff_image = $staff->image;
        if($request->hasFile('image')) {
            try{

                $location = imagePath()['staff']['path'];
                $size = imagePath()['staff']['size'];
                $old = $staff->image;
                $staff_image = uploadImage($request->image, $location , $size, $old);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $staff->image =$staff_image;
        $staff->save();

        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('staff.profile')->withNotify($notify);
    }

    public function password()
    {
        $page_title = 'Password Setting';
        $staff = Auth::guard('staff')->user();
        return view('staff.password', compact('page_title', 'staff'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $staff = Auth::guard('staff')->user();
        if (!Hash::check($request->old_password, $staff->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }
        $staff->update([
            'password' => Hash::make($request->password),
        ]);
        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('staff.password')->withNotify($notify);
    }

    public function createAppointment(){
        $page_title = 'Create Appoiment';
        $doctors = Doctor::where('status',1)->latest()->get();
        return view('staff.appointment.create-appointment',compact('page_title','doctors'));
    }

    public function appointmentDetails(Request $request){
        $request->validate([
            'doctor_id' => 'required|numeric|gt:0',
        ]);


        $doctor = Doctor::findOrFail($request->doctor_id);

        if ($doctor->status == 0) {
            $notify[] = ['error', 'This doctor is banned'];
            return redirect()->route('staff.appointment.create')->withNotify($notify);
        }

        if ($doctor->serial_or_slot == null || empty($doctor->serial_or_slot)) {
            $notify[] = ['error', 'No available schedule for this doctor'];
            return back()->withNotify($notify);
        }

        $available_date = [];
        $date = Carbon::now();

        for ($i=0; $i <$doctor->serial_day; $i++) {
            array_push($available_date, date('Y-m-d',strtotime($date)));
            $date->addDays(1);
        }

        $page_title = 'Appointment Booking';

        return view('staff.appointment.book-appointment',compact('doctor','page_title','available_date'));
    }

    public function appointmentStore(Request $request,$id)
    {
        $this->validate($request, [
            'booking_date' => 'required|date',
            'time_serial' => 'required',
            'name' => 'required|max:50',
            'email' => 'required|email',
            'mobile' => 'required|max:50',
            'age' => 'required|numeric|gt:0',
        ],[
            'time_serial.required'=>'You did not select any time or serial',
        ]);

        $doctor = Doctor::findOrFail($id);

        $time_serial_check = $doctor->whereJsonContains('serial_or_slot',$request->time_serial)->first();


        if($time_serial_check){
            $existed_appointment = Appointment::where('doctor_id',$doctor->id)->where('booking_date',$request->booking_date)->where('time_serial',$request->time_serial)->where('try',1)->where('d_status',0)->first();

            if($existed_appointment){
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
                'staff' => Auth::guard('staff')->user()->id,
                'disease' => $request->disease,
                'try' => 1,
            ]);

            $patient = 1;
            notify($appointment, 'APPOINTMENT_CONFIRM', [
                'booking_date' => $appointment->booking_date,
                'time_serial' => $appointment->time_serial,
                'doctor_name' => $doctor->name,
                'doctor_fees' => ''.$doctor->fees.' '.$general->cur_text.'',
            ],$patient);

            $notify[] = ['success', 'Your appointment has been taken.'];
            return back()->withNotify($notify);

        }else{
            $notify[] = ['error', 'Do not try to cheat us'];
            return back()->withNotify($notify);
        }

    }

    public function bookedDate(Request $request){
        $data = Appointment::where('doctor_id',$request->doctor_id)->where('try',1)->where('d_status',0)->whereDate('booking_date',Carbon::parse($request->date))->get()->map(function($item){
            return str_slug($item->time_serial);
        });
        return response()->json(@$data);
    }


    public function allAppointment(){
        $page_title = 'All Appointments';
        $appointments = Appointment::where('staff',Auth::guard('staff')->user()->id)->where('try',1)->where('is_complete',0)->where('d_status',0)->whereHas('relationStaff', function ($query) {
            $query->where('status',1);
       })->latest()->paginate(getPaginate());
        $empty_message = 'No Appointment Found';
        return view('staff.appointment.appointment',compact('page_title','appointments','empty_message'));
    }

    public function appointmentView(Request $request, $id)
    {

        $appointment =  Appointment::findOrFail($id);

        if ($request->complete) {
            $appointment->is_complete = 1;

            if($appointment->p_status == 0){
                $staff = staff::findOrFail($appointment->staff->id);
                $staff->balance += $staff->fees;
                $staff->save();

                $appointment->p_status = 1;
            }

            $appointment->save();

            $notify[] = ['success', 'Service Done Successfully'];
            return back()->withNotify($notify);
        }
    }

    public function appointmentDone(){
        $page_title = 'Done Appointments';
        $appointments = Appointment::where('staff',Auth::guard('staff')->user()->id)->where('try',1)->where('is_complete',1)->where('d_status',0)->whereHas('relationStaff', function ($query) {
            $query->where('status',1);
       })->latest()->paginate(getPaginate());
        $empty_message = 'No Done Appointment Found';
        return view('staff.appointment.appointment',compact('page_title','appointments','empty_message'));
    }

    public function appointmentRemove($id){
        $appointment = Appointment::where('staff',Auth::guard('staff')->user()->id)->findOrFail($id);

        if (!$appointment) {
            $notify[] = ['error', 'You are not authorized to make this operation'];
            return back()->withNotify($notify);
        }

        $appointment->d_status = 1;
        $appointment->d_staff = Auth::guard('staff')->user()->id;
        $appointment->save();

        $patient = 1;
        notify($appointment, 'APPOINTMENT_REJECT', [
            'booking_date' => $appointment->booking_date,
            'time_serial' => $appointment->time_serial,
            'doctor_name' => $appointment->doctor->name,
        ],$patient);

        $notify[] = ['success', 'Your appointment goes in trashed appointments'];
        return back()->withNotify($notify);
    }

    public function appointmentTrashed(){
        $page_title = 'Trashed Appointment';

        $appointments = Appointment::where('staff',Auth::guard('staff')->user()->id)->where('d_status',1)->whereHas('relationStaff', function ($query) {
            $query->where('status',1);
       })->latest()->paginate(getPaginate());
        $empty_message = 'No Trashed Appointment Found';

        return view('staff.appointment.trashed-appointment',compact('page_title','appointments','empty_message'));
    }

    public function show2faForm()
    {
        $gnl = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $staff = Auth::guard('staff')->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($staff->username . '@' . $gnl->sitename, $secret);
        $prevcode = $staff->tsc;
        $prevqr = $ga->getQRCodeGoogleUrl($staff->username . '@' . $gnl->sitename, $prevcode);
        $page_title = 'Two Factor';
        return view('staff.twofactor', compact('page_title', 'secret', 'qrCodeUrl', 'prevcode', 'prevqr'));
    }

    public function create2fa(Request $request)
    {
        $staff = Auth::guard('staff')->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);

        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        if ($oneCode === $request->code) {
            $staff->tsc = $request->key;
            $staff->ts = 1;
            $staff->tv = 1;
            $staff->save();


            $staffAgent = getIpInfo();
            send_email($staff, '2FA_ENABLE', [
                'operating_system' => $staffAgent['os_platform'],
                'browser' => $staffAgent['browser'],
                'ip' => $staffAgent['ip'],
                'time' => $staffAgent['time']
            ]);
            send_sms($staff, '2FA_ENABLE', [
                'operating_system' => $staffAgent['os_platform'],
                'browser' => $staffAgent['browser'],
                'ip' => $staffAgent['ip'],
                'time' => $staffAgent['time']
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

        $staff = Auth::guard('staff')->user();
        $ga = new GoogleAuthenticator();

        $secret = $staff->tsc;
        $oneCode = $ga->getCode($secret);
        $staffCode = $request->code;

        if ($oneCode == $staffCode) {

            $staff->tsc = null;
            $staff->ts = 0;
            $staff->tv = 1;
            $staff->save();


            $staffAgent = getIpInfo();
            send_email($staff, '2FA_DISABLE', [
                'operating_system' => $staffAgent['os_platform'],
                'browser' => $staffAgent['browser'],
                'ip' => $staffAgent['ip'],
                'time' => $staffAgent['time']
            ]);
            send_sms($staff, '2FA_DISABLE', [
                'operating_system' => $staffAgent['os_platform'],
                'browser' => $staffAgent['browser'],
                'ip' => $staffAgent['ip'],
                'time' => $staffAgent['time']
            ]);


            $notify[] = ['success', 'Two Factor Authenticator Disable Successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }
}
