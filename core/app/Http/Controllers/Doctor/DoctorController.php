<?php

namespace App\Http\Controllers\Doctor;

use App\Appointment;
use App\Deposit;
use App\Doctor;
use App\GeneralSetting;
use App\Lib\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Education;
use App\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\FileTypeValidate;
use App\SocialIcon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DoctorController extends Controller
{

    public function dashboard()
    {
        $page_title = 'Dashboard';
        $doctor = Auth::guard('doctor')->user();
        $total_online_earn = Deposit::where('doctor_id',$doctor->id)->where('status',1)->sum('amount');
        $total_cash_earn = $doctor->balance - $total_online_earn;
        $appointment_done = Appointment::where('doctor_id',$doctor->id)->where('try',1)->where('is_complete',1)->where('d_status',0)->count();
        $new_appointment = Appointment::where('doctor_id',$doctor->id)->where('try',1)->where('is_complete',0)->where('d_status',0)->count();
        return   view('doctor.dashboard',compact('page_title','total_online_earn','total_cash_earn','appointment_done','new_appointment'));
    }
    public function profile()
    {
        $page_title = 'Profile';
        $doctor = Auth::guard('doctor')->user();
        return view('doctor.profile', compact('page_title', 'doctor'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'image' => [new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ]);

        $doctor = Auth::guard('doctor')->user();

        $doctor_image = $doctor->image;
        if($request->hasFile('image')) {
            try{

                $location = imagePath()['doctor']['path'];
                $size = imagePath()['doctor']['size'];
                $old = $doctor->image;
                $doctor_image = uploadImage($request->image, $location , $size, $old);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $doctor->image = $doctor_image;
        $doctor->save();

        $notify[] = ['success', 'Your profile has been updated.'];
        return redirect()->route('doctor.profile')->withNotify($notify);
    }

    public function password()
    {
        $page_title = 'Password Setting';
        $doctor = Auth::guard('doctor')->user();
        return view('doctor.password', compact('page_title', 'doctor'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $doctor = Auth::guard('doctor')->user();
        if (!Hash::check($request->old_password, $doctor->password)) {
            $notify[] = ['error', 'Password Do not match !!'];
            return back()->withErrors(['Invalid old password.']);
        }
        $doctor->update([
            'password' => Hash::make($request->password),
        ]);
        $notify[] = ['success', 'Password Changed Successfully.'];
        return redirect()->route('doctor.password')->withNotify($notify);
    }

    public function schedule(){
        $page_title = 'Manage Schedule';
        $doctor = Auth::guard('doctor')->user();
        return view('doctor.schedule',compact('page_title','doctor'));
    }

    public function about(){

        $page_title = 'About';
        $doctor = Auth::guard('doctor')->user();
        return view('doctor.about',compact('page_title','doctor'));
    }

    public function aboutUpdate(Request $request){

        $this->validate($request, [
            'about' => 'required',
        ]);
        $doctor = Auth::guard('doctor')->user();

        $doctor->update([
            'about' => $request->about
        ]);

        $notify[] = ['success', 'About you has been updated'];
        return back()->withNotify($notify);
    }

    public function educationAll(){

        $page_title = 'All Education Details';
        $education_details = Education::where('doctor_id', Auth::guard('doctor')->user()->id)->get();
        return view('doctor.education',compact('page_title','education_details'));
    }

    public function educationStore(Request $request){

        $this->validate($request, [
            'institution' => 'required|max:190',
            'discipline' => 'required|max:190',
            'period' => 'required|max:190',
        ]);

        Education::create([
            'doctor_id' => Auth::guard('doctor')->user()->id,
            'institution' => $request->institution,
            'discipline' => $request->discipline,
            'period' => $request->period,
        ]);

        $notify[] = ['success', 'Education details has been added'];
        return back()->withNotify($notify);

    }

    public function educationUpdate(Request $request, $id){

        $this->validate($request, [
            'institution' => 'required|max:190',
            'discipline' => 'required|max:190',
            'period' => 'required|max:190',
        ]);

        $education_details = Education::where('doctor_id', Auth::guard('doctor')->user()->id)->findOrFail($id);

        $education_details->update([
            'institution' => $request->institution,
            'discipline' => $request->discipline,
            'period' => $request->period,
        ]);

        $notify[] = ['success', 'Education details has been updated'];
        return back()->withNotify($notify);

    }

    public function educationRemove($id){

        $education_details = Education::where('doctor_id', Auth::guard('doctor')->user()->id)->findOrFail($id);
        $education_details->delete();

        $notify[] = ['success', 'Education details successfuly deleted'];
        return back()->withNotify($notify);

    }

    public function experienceAll(){

        $page_title = 'All experience Details';
        $experience_details = Experience::where('doctor_id', Auth::guard('doctor')->user()->id)->get();
        return view('doctor.experience',compact('page_title','experience_details'));
    }

    public function experienceStore(Request $request){

        $this->validate($request, [
            'institution' => 'required|max:190',
            'discipline' => 'required|max:190',
            'period' => 'required|max:190',
        ]);

        Experience::create([
            'doctor_id' => Auth::guard('doctor')->user()->id,
            'institution' => $request->institution,
            'discipline' => $request->discipline,
            'period' => $request->period,
        ]);

        $notify[] = ['success', 'experience details has been added'];
        return back()->withNotify($notify);

    }

    public function experienceUpdate(Request $request, $id){

        $this->validate($request, [
            'institution' => 'required|max:190',
            'discipline' => 'required|max:190',
            'period' => 'required|max:190',
        ]);

        $experience_details = Experience::where('doctor_id', Auth::guard('doctor')->user()->id)->findOrFail($id);

        $experience_details->update([
            'institution' => $request->institution,
            'discipline' => $request->discipline,
            'period' => $request->period,
        ]);

        $notify[] = ['success', 'experience details has been updated'];
        return back()->withNotify($notify);

    }

    public function experienceRemove($id){

        $experience_details = Experience::where('doctor_id', Auth::guard('doctor')->user()->id)->findOrFail($id);
        $experience_details->delete();

        $notify[] = ['success', 'experience details successfuly deleted'];
        return back()->withNotify($notify);

    }

    public function scheduleManage(Request $request){

        $this->validate($request, [
            'slot_type' => 'required|numeric|gt:0',
            'max_serial' => 'sometimes|required|numeric|min:1',
            'duration' => 'sometimes|required|numeric|gt:0',
            'serial_day' => 'required|numeric|gt:0',
            'start_time' => 'sometimes|required',
            'end_time' => 'sometimes|required'
        ]);

        $doctor = Doctor::findOrFail(Auth::guard('doctor')->user()->id);

        if ($request->slot_type == 1 && $request->max_serial > 0) {

            $serial_or_slot = [];

            for ($i=1; $i <= $request->max_serial; $i++) {
                array_push($serial_or_slot,"$i");
            }

            $doctor->serial_or_slot = $serial_or_slot;
            $doctor->max_serial = $request->max_serial;

        }
        elseif ($request->slot_type == 2 && $request->duration > 0) {

            $start_time = Carbon::parse($request->start_time);
            $end_time = Carbon::parse($request->end_time);
            $total_min = $end_time->diffInMinutes($start_time);
            $total_slot = $total_min / $request->duration;

            $serial_or_slot = [];

            for ($i=1; $i <= $total_slot; $i++) {

                array_push($serial_or_slot,date('h:i:a',strtotime($start_time)));
                $start_time->addMinutes($request->duration);
            }

            $doctor->serial_or_slot = $serial_or_slot;
            $doctor->duration = $request->duration;
            $doctor->start_time = Carbon::parse($request->start_time)->format('h:i a');
            $doctor->end_time = Carbon::parse($request->end_time)->format('h:i a');
        }
        else{
            $notify[] = ['error', 'Please select maximum serial or time duration.'];
            return back()->withNotify($notify);
        }

        $doctor->slot_type = $request->slot_type;
        $doctor->serial_day = $request->serial_day;
        $doctor->save();

        $notify[] = ['success', 'Time schedule has been updated'];
        return back()->withNotify($notify);
    }

    public function bookedDate(Request $request){

        $data = Appointment::where('doctor_id',$request->doctor_id)->where('try',1)->where('d_status',0)->whereDate('booking_date',Carbon::parse($request->date))->get()->map(function($item){
            return str_slug($item->time_serial);
        });
        return response()->json(@$data);
    }

    public function appointmentDetails(){

        $doctor = Auth::guard('doctor')->user();

        if ($doctor->serial_or_slot == null || empty($doctor->serial_or_slot)) {
            $notify[] = ['error', 'No available schedule for this doctor'];
            return back()->withNotify($notify);
        }

        if ($doctor->status == 0) {
            $notify[] = ['error', 'This doctor is banned'];
            return redirect()->route('doctor.appointments.create')->withNotify($notify);
        }

        if ($doctor->serial_or_slot == null || $doctor->serial_day <= 0) {
            $notify[] = ['error', 'This doctor does not have available time or serial day'];
            return back()->withNotify($notify);
        }

        $available_date = [];
        $date = Carbon::now();

        for ($i=0; $i <$doctor->serial_day; $i++) {
            array_push($available_date, date('Y-m-d',strtotime($date)));
            $date->addDays(1);
        }

        $page_title = 'Appointment Booking';

        return view('doctor.appointment.book-appointment',compact('doctor','page_title','available_date'));
    }

    public function appointmentStore(Request $request)
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

        $doctor = Doctor::findOrFail(Auth::guard('doctor')->user()->id);
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
                'entry_doctor' => Auth::guard('doctor')->user()->id,
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

    public function allAppointment(){
        $page_title = 'All Appointments';
        $appointments = Appointment::where('doctor_id',Auth::guard('doctor')->user()->id)->where('try',1)->where('is_complete',0)->where('d_status',0)->latest()->paginate(getPaginate());
        $empty_message = 'No Appointment Found';
        return view('doctor.appointment.appointment',compact('page_title','appointments','empty_message'));
    }

    public function appointmentView(Request $request, $id)
    {

        $appointment =  Appointment::where('doctor_id',Auth::guard('doctor')->user()->id)->findOrFail($id);

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

    public function appointmentDone(){
        $page_title = 'Done Appointments';
        $appointments = Appointment::where('doctor_id',Auth::guard('doctor')->user()->id)->where('try',1)->where('is_complete',1)->where('d_status',0)->latest()->paginate(getPaginate());
        $empty_message = 'No Done Appointment Found';
        return view('doctor.appointment.appointment',compact('page_title','appointments','empty_message'));
    }

    public function appointmentRemove($id){
        $appointment = Appointment::where('doctor_id',Auth::guard('doctor')->user()->id)->findOrFail($id);
        $doctor = Auth::guard('doctor')->user();
        $appointment->d_status = 1;
        $appointment->d_doctor = Auth::guard('doctor')->user()->id;
        $appointment->save();

        $patient = 1;
        notify($appointment, 'APPOINTMENT_REJECT', [
            'booking_date' => $appointment->booking_date,
            'time_serial' => $appointment->time_serial,
            'doctor_name' => $doctor->name,
        ],$patient);

        $notify[] = ['success', 'Your appointment goes in trashed appointments'];
        return back()->withNotify($notify);
    }

    public function appointmentTrashed(){
        $page_title = 'Trashed Appointment';
        $appointments = Appointment::where('doctor_id',Auth::guard('doctor')->user()->id)->where('d_status',1)->latest()->paginate(getPaginate());
        $empty_message = 'No Trashed Appointment Found';

        return view('doctor.appointment.trashed-appointment',compact('page_title','appointments','empty_message'));
    }

    public function socialIcon(){
        $page_title = 'All Social Icons';
        $social_icons = SocialIcon::where('doctor_id', Auth::guard('doctor')->user()->id)->get();
        return view('doctor.social',compact('page_title','social_icons'));
    }

    public function socialIconStore(Request $request){

        $this->validate($request, [
            'title' => 'required|max:190',
            'icon' => 'required|max:190',
            'url' => 'required|url|max:190',
        ]);

        SocialIcon::create([
            'doctor_id' => Auth::guard('doctor')->user()->id,
            'title' => $request->title,
            'icon' => $request->icon,
            'url' => $request->url,
        ]);

        $notify[] = ['success', 'Social icon has been added'];
        return back()->withNotify($notify);

    }

    public function socialIconUpdate(Request $request, $id){

        $this->validate($request, [
            'title' => 'required|max:190',
            'icon' => 'required|max:190',
            'url' => 'required|url|max:190',
        ]);

        $icon = SocialIcon::where('doctor_id',Auth::guard('doctor')->user()->id)->findOrFail($id);

        $icon->update([
            'title' => $request->title,
            'icon' => $request->icon,
            'url' => $request->url,
        ]);

        $notify[] = ['success', 'Social icon has been updated'];
        return back()->withNotify($notify);

    }

    public function  socialIconRemove($id){

        $icon = SocialIcon::where('doctor_id',Auth::guard('doctor')->user()->id)->findOrFail($id);
        $icon->delete();

        $notify[] = ['success', 'Social icon successfuly deleted'];
        return back()->withNotify($notify);

    }

    public function speciality(Request $request){

        $page_title = 'Speciality';
        $speciality = Auth::guard('doctor')->user()->speciality;
        return view('doctor.speciality',compact('page_title','speciality'));
    }

    public function specialityUpdate(Request $request){

        $request->validate([
            'speciality.*' => 'sometimes|required',
        ],[
            'speciality.*.required' => 'Speciality Field is required',
        ]);

        $speciality = Auth::guard('doctor')->user();

        $speciality->update([
            'speciality' => $request->speciality,
        ]);

        $notify[] = ['success', 'Speciality has been updated'];
        return back()->withNotify($notify);
    }

    public function show2faForm()
    {
        $gnl = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $doctor = Auth::guard('doctor')->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($doctor->username . '@' . $gnl->sitename, $secret);
        $prevcode = $doctor->tsc;
        $prevqr = $ga->getQRCodeGoogleUrl($doctor->username . '@' . $gnl->sitename, $prevcode);
        $page_title = 'Two Factor';
        return view('doctor.twofactor', compact('page_title', 'secret', 'qrCodeUrl', 'prevcode', 'prevqr'));
    }

    public function create2fa(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);

        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        if ($oneCode === $request->code) {
            $doctor->tsc = $request->key;
            $doctor->ts = 1;
            $doctor->tv = 1;
            $doctor->save();


            $doctorAgent = getIpInfo();
            send_email($doctor, '2FA_ENABLE', [
                'operating_system' => $doctorAgent['os_platform'],
                'browser' => $doctorAgent['browser'],
                'ip' => $doctorAgent['ip'],
                'time' => $doctorAgent['time']
            ]);
            send_sms($doctor, '2FA_ENABLE', [
                'operating_system' => $doctorAgent['os_platform'],
                'browser' => $doctorAgent['browser'],
                'ip' => $doctorAgent['ip'],
                'time' => $doctorAgent['time']
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

        $doctor = Auth::guard('doctor')->user();
        $ga = new GoogleAuthenticator();

        $secret = $doctor->tsc;
        $oneCode = $ga->getCode($secret);
        $doctorCode = $request->code;

        if ($oneCode == $doctorCode) {

            $doctor->tsc = null;
            $doctor->ts = 0;
            $doctor->tv = 1;
            $doctor->save();


            $doctorAgent = getIpInfo();
            send_email($doctor, '2FA_DISABLE', [
                'operating_system' => $doctorAgent['os_platform'],
                'browser' => $doctorAgent['browser'],
                'ip' => $doctorAgent['ip'],
                'time' => $doctorAgent['time']
            ]);
            send_sms($doctor, '2FA_DISABLE', [
                'operating_system' => $doctorAgent['os_platform'],
                'browser' => $doctorAgent['browser'],
                'ip' => $doctorAgent['ip'],
                'time' => $doctorAgent['time']
            ]);


            $notify[] = ['success', 'Two Factor Authenticator Disable Successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong Verification Code'];
            return back()->withNotify($notify);
        }
    }
}

