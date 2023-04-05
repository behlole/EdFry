<?php

namespace App\Http\Controllers\Assistant;

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

class AssistantController extends Controller
{

    public function dashboard()
    {
        $page_title = 'Dashboard';
        $assistant = Auth::guard('assistant')->user();
        $total_doctor = $assistant->doctors->count();

        $done_appointment = 0;
        foreach($assistant->doctors as $item){
            $vals = $item->appointments()->where('try',1)->where('is_complete',1)->where('d_status',0)->whereHas('doctor', function ($query) {
                $query->where('status',1);
           })->count();

            $done_appointment += $vals;
        }

        $new_appointment = 0;
        foreach($assistant->doctors as $item){
            $vals = $item->appointments()->where('try',1)->where('d_status',0)->where('is_complete',0)->whereHas('doctor', function ($query) {
                $query->where('status',1);
           })->count();
            $new_appointment += $vals;
        }
        return   view('assistant.dashboard',compact('page_title','total_doctor','done_appointment','new_appointment'));
    }
    public function profile()
    {
        $page_title = 'Profile';
        $assistant = Auth::guard('assistant')->user();
        return view('assistant.profile', compact('page_title', 'assistant'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'image' => [new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ]);

        $assistant = Auth::guard('assistant')->user();

        $assistant_image = $assistant->image;
        if($request->hasFile('image')) {
            try{

                $location = imagePath()['assistant']['path'];
                $size = imagePath()['assistant']['size'];
                $old = $assistant->image;
                $assistant_image = uploadImage($request->image, $location , $size, $old);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $assistant->image =$assistant_image;
        $assistant->save();

        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('assistant.profile')->withNotify($notify);
    }

    public function password()
    {
        $page_title = 'Password Setting';
        $assistant = Auth::guard('assistant')->user();
        return view('assistant.password', compact('page_title', 'assistant'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $assistant = Auth::guard('assistant')->user();
        if (!Hash::check($request->old_password, $assistant->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }
        $assistant->update([
            'password' => Hash::make($request->password),
        ]);
        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('assistant.password')->withNotify($notify);
    }

    public function createAppointment(){
        $page_title = 'Create Appoiment';
        $assistant = Auth::guard('assistant')->user();
        $doctors = $assistant->doctors()->where('status',1)->get();
        return view('assistant.appointment.create-appointment',compact('page_title','doctors'));
    }

    public function bookedDate(Request $request){
        $data = Appointment::where('doctor_id',$request->doctor_id)->where('try',1)->where('d_status',0)->whereDate('booking_date',Carbon::parse($request->date))->get()->map(function($item){
            return str_slug($item->time_serial);
        });
        return response()->json(@$data);
    }

    public function appointmentDetails(Request $request){

        $request->validate([
            'doctor_id' => 'required|numeric|gt:0',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);


        if ($doctor->status == 0) {
            $notify[] = ['error', 'This doctor is banned'];
            return redirect()->route('assistant.appointments.create')->withNotify($notify);
        }

        $check = AssistantDoctorTrack::where('assistant_id',Auth::guard('assistant')->user()->id)->where('doctor_id',$doctor->id)->count();

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

        for ($i=0; $i <$doctor->serial_day; $i++) {
            array_push($available_date, date('Y-m-d',strtotime($date)));
            $date->addDays(1);
        }

        $page_title = 'Appointment Booking';

        return view('assistant.appointment.book-appointment',compact('doctor','page_title','available_date'));
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

        $check = AssistantDoctorTrack::where('assistant_id',Auth::guard('assistant')->user()->id)->where('doctor_id',$doctor->id)->count();

        if ($check <= 0) {
            $notify[] = ['error', 'You are not authorized to acceass this'];
            return back()->withNotify($notify);
        }

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
                'assistant' => Auth::guard('assistant')->user()->id,
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

    public function allDoctors(){
        $page_title = 'All Doctors Under You';
        $assistant = Auth::guard('assistant')->user();
        $doctors = $assistant->doctors()->where('status',1)->get();
        $empty_message = 'No doctor assigned yet';
        return view('assistant.doctor',compact('page_title','doctors','empty_message'));

    }


    public function doctorAppointment($id){

        $doctor = Doctor::findOrFail($id);
        $check = AssistantDoctorTrack::where('assistant_id',Auth::guard('assistant')->user()->id)->where('doctor_id',$doctor->id)->count();

        if ($check <= 0) {
            $notify[] = ['error', 'You are not authorized to acceass this'];
            return back()->withNotify($notify);
        }

        $page_title = ''.$doctor->name.' - Appointments';

        $appointments = Appointment::where('doctor_id',$doctor->id)->where('try',1)->where('is_complete',0)->where('d_status',0)->latest()->paginate(getPaginate());

        $empty_message = 'No Appointment Found';

        return view('assistant.appointment.appointment',compact('page_title','appointments','empty_message'));
    }

    public function appointmentView(Request $request, $id)
    {

        $appointment =  Appointment::findOrFail($id);

        if ($request->complete) {
            $appointment->is_complete = 1;

            if($appointment->p_status == 0){
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

    public function appointmentDone($id){
        $doctor = Doctor::findOrFail($id);
        $check = AssistantDoctorTrack::where('assistant_id',Auth::guard('assistant')->user()->id)->where('doctor_id',$doctor->id)->count();

        if ($check <= 0) {
            $notify[] = ['error', 'You are not authorized to acceass this'];
            return back()->withNotify($notify);
        }

        $page_title = ''.$doctor->name.' - Done Appointments';
        $empty_message = 'No Done Appointment Found';

        $appointments = Appointment::where('doctor_id',$doctor->id)->where('try',1)->where('is_complete',1)->where('d_status',0)->latest()->paginate(getPaginate());

        return view('assistant.appointment.appointment',compact('page_title','appointments','empty_message'));
    }

    public function appointmentRemove($id){
        $appointment = Appointment::findOrFail($id);
        $check = AssistantDoctorTrack::where('assistant_id',Auth::guard('assistant')->user()->id)->where('doctor_id',$appointment->doctor->id)->count();

        if ($check <= 0) {
            $notify[] = ['error', 'You are not authorized to acceass this'];
            return back()->withNotify($notify);
        }

        $appointment->d_status = 1;
        $appointment->d_assistant = Auth::guard('assistant')->user()->id;
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

    public function appointmentTrashed($id){
        $doctor = Doctor::findOrFail($id);
        $check = AssistantDoctorTrack::where('assistant_id',Auth::guard('assistant')->user()->id)->where('doctor_id',$doctor->id)->count();

        if ($check <= 0) {
            $notify[] = ['error', 'You are not authorized to acceass this'];
            return back()->withNotify($notify);
        }

        $page_title = ''.$doctor->name.' - Trashed Appointments';
        $empty_message = 'No Done Trashed Appointment Found';

        $appointments = Appointment::where('doctor_id',$doctor->id)->where('d_status',1)->latest()->paginate(getPaginate());
        $empty_message = 'No Trashed Appointment Found';

        return view('assistant.appointment.trashed-appointment',compact('page_title','appointments','empty_message'));
    }

    public function show2faForm()
    {
        $gnl = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $assistant = Auth::guard('assistant')->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($assistant->username . '@' . $gnl->sitename, $secret);
        $prevcode = $assistant->tsc;
        $prevqr = $ga->getQRCodeGoogleUrl($assistant->username . '@' . $gnl->sitename, $prevcode);
        $page_title = 'Two Factor';
        return view('assistant.twofactor', compact('page_title', 'secret', 'qrCodeUrl', 'prevcode', 'prevqr'));
    }

    public function create2fa(Request $request)
    {
        $assistant = Auth::guard('assistant')->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);

        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        if ($oneCode === $request->code) {
            $assistant->tsc = $request->key;
            $assistant->ts = 1;
            $assistant->tv = 1;
            $assistant->save();


            $assistantAgent = getIpInfo();
            send_email($assistant, '2FA_ENABLE', [
                'operating_system' => $assistantAgent['os_platform'],
                'browser' => $assistantAgent['browser'],
                'ip' => $assistantAgent['ip'],
                'time' => $assistantAgent['time']
            ]);
            send_sms($assistant, '2FA_ENABLE', [
                'operating_system' => $assistantAgent['os_platform'],
                'browser' => $assistantAgent['browser'],
                'ip' => $assistantAgent['ip'],
                'time' => $assistantAgent['time']
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

        $assistant = Auth::guard('assistant')->user();
        $ga = new GoogleAuthenticator();

        $secret = $assistant->tsc;
        $oneCode = $ga->getCode($secret);
        $assistantCode = $request->code;

        if ($oneCode == $assistantCode) {

            $assistant->tsc = null;
            $assistant->ts = 0;
            $assistant->tv = 1;
            $assistant->save();


            $assistantAgent = getIpInfo();
            send_email($assistant, '2FA_DISABLE', [
                'operating_system' => $assistantAgent['os_platform'],
                'browser' => $assistantAgent['browser'],
                'ip' => $assistantAgent['ip'],
                'time' => $assistantAgent['time']
            ]);
            send_sms($assistant, '2FA_DISABLE', [
                'operating_system' => $assistantAgent['os_platform'],
                'browser' => $assistantAgent['browser'],
                'ip' => $assistantAgent['ip'],
                'time' => $assistantAgent['time']
            ]);


            $notify[] = ['success', 'Two Factor Authenticator Disable Successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }
}
