<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\Assistant;
use App\Deposit;
use App\Doctor;
use App\DoctorLogin;
use App\Http\Controllers\Controller;
use App\Location;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Sector;
use App\DoctorAssistantTrack;
use Illuminate\Support\Facades\Hash;

class ManageDoctorsController extends Controller
{
    public function sectors(){
        $page_title = 'Manage Sector';
        $empty_message = 'No sector found';
        $sectors = Sector::latest()->paginate(getPaginate());
        return view('admin.doctors.sector', compact('page_title', 'empty_message','sectors'));
    }

    public function storeSectors(Request $request){
        $request->validate([
            'image' => ['required', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'name' => 'required|string|max:190',
            'details' => 'required|string|max:190'
        ]);

        $subject_image = '';
        if($request->hasFile('image')) {
            try{

                $location = imagePath()['sector']['path'];
                $size = imagePath()['sector']['size'];

                $subject_image = uploadImage($request->image, $location , $size);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        Sector::create([
            'image' => $subject_image,
            'name' => $request->name,
            'details' => $request->details,
        ]);

        $notify[] = ['success', 'Sector details has been added'];
        return back()->withNotify($notify);
    }

    public function updateSectors(Request $request,$id){

        $request->validate([
            'image' => [new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'name' => 'required|string|max:190',
            'details' => 'required|string|max:190'
        ]);

        $sector = Sector::findOrFail($id);

        $subject_image = $sector->image;
        if($request->hasFile('image')) {
            try{

                $location = imagePath()['sector']['path'];
                $size = imagePath()['sector']['size'];
                $old = $sector->image;
                $subject_image = uploadImage($request->image, $location , $size, $old);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $sector->update([
            'image' => $subject_image,
            'name' => $request->name,
            'details' => $request->details,
        ]);

        $notify[] = ['success', 'Sector details has been Updated'];
        return back()->withNotify($notify);
    }

    public function locations(){
        $page_title = 'Manage Location';
        $empty_message = 'No location found';
        $locations = Location::latest()->paginate(getPaginate());
        return view('admin.doctors.location', compact('page_title', 'empty_message','locations'));
    }

    public function storeLocations(Request $request){
        $request->validate([
            'name' => 'required|string|max:190'
        ]);

        Location::create([
            'name' => $request->name,
        ]);

        $notify[] = ['success', 'Location details has been added'];
        return back()->withNotify($notify);
    }

    public function updateLocations(Request $request,$id){

        $request->validate([
            'name' => 'required|string|max:190'
        ]);

        $sector = Location::findOrFail($id);

        $sector->update([
            'name' => $request->name,
        ]);

        $notify[] = ['success', 'Location details has been Updated'];
        return back()->withNotify($notify);
    }

    public function allDoctors(){
        $page_title = 'Manage Mentors';
        $empty_message = 'No mentor found';
        $doctors = Doctor::latest()->paginate(getPaginate());
        return view('admin.doctors.list', compact('page_title', 'empty_message', 'doctors'));
    }

    public function activeDoctors()
    {
        $page_title = 'Manage Active Mentors';
        $empty_message = 'No active doctor found';
        $doctors = Doctor::active()->latest()->paginate(getPaginate());
        return view('admin.doctors.list', compact('page_title', 'empty_message', 'doctors'));
    }

    public function bannedDoctors()
    {
        $page_title = 'Banned Mentors';
        $empty_message = 'No banned doctor found';
        $doctors = Doctor::banned()->latest()->paginate(getPaginate());
        return view('admin.doctors.list', compact('page_title', 'empty_message', 'doctors'));
    }

    public function emailUnverifiedDoctors()
    {
        $page_title = 'Email Unverified Mentors';
        $empty_message = 'No email unverified doctor found';
        $doctors = Doctor::emailUnverified()->latest()->paginate(getPaginate());
        return view('admin.doctors.list', compact('page_title', 'empty_message', 'doctors'));
    }
    public function emailVerifiedDoctors()
    {
        $page_title = 'Email Verified Mentors';
        $empty_message = 'No email verified doctor found';
        $doctors = Doctor::emailVerified()->latest()->paginate(getPaginate());
        return view('admin.doctors.list', compact('page_title', 'empty_message', 'doctors'));
    }


    public function smsUnverifiedDoctors()
    {
        $page_title = 'SMS Unverified Mentors';
        $empty_message = 'No sms unverified doctor found';
        $doctors = Doctor::smsUnverified()->latest()->paginate(getPaginate());
        return view('admin.doctors.list', compact('page_title', 'empty_message', 'doctors'));
    }
    public function smsVerifiedDoctors()
    {
        $page_title = 'SMS Verified Mentors';
        $empty_message = 'No sms verified doctor found';
        $doctors = Doctor::smsVerified()->latest()->paginate(getPaginate());
        return view('admin.doctors.list', compact('page_title', 'empty_message', 'doctors'));
    }

    public function newDoctor(){
        $page_title = 'Add New Mentor';
        $sectors = Sector::latest()->get();
        $locations = Location::latest()->get();
        return view('admin.doctors.new', compact('page_title','sectors','locations'));
    }

    public function storeDoctor(Request $request){
        $request->validate([
            'image' => ['required',new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:doctors',
            'mobile' => 'required|string|max:191|unique:doctors',
            'password' => 'required|string|min:6|confirmed',
            'username' => 'required|alpha_num|unique:doctors|min:6',
            'sector_id' => 'required|numeric|gt:0',
            'qualification' => 'required|string|max:180',
            'address' => 'required|string|max:191',
            'location_id' => 'required|numeric|gt:0',
            'fees' => 'required|numeric',
            'rating' => 'required|numeric|gt:0|max:5',
            'about' => 'required',
            ]);

        $doctor_image = '';
        if($request->hasFile('image')) {
            try{
                $location = imagePath()['doctor']['path'];
                $size = imagePath()['doctor']['size'];

                $doctor_image = uploadImage($request->image, $location , $size);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $doctor = Doctor::create([
            'image' => $doctor_image,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'username' => $request->username,
            'sector_id' => $request->sector_id,
            'qualification' => $request->qualification,
            'address' => $request->address,
            'location_id' => $request->location_id,
            'fees' => $request->fees,
            'rating' => $request->rating,
            'about' => $request->about,
            'featured' => $request->featured ? 1 : 0,
        ]);

        notify($doctor, 'DOCTOR_CREDENTIALS', [
            'username' => $doctor->username,
            'password' => $request->password,
        ]);

        $notify[] = ['success', 'Doctor details has been added'];
        return back()->withNotify($notify);
    }

    public function detail($id)
    {
        $page_title = 'Mentor Detail';
        $doctor = Doctor::findOrFail($id);
        $assistants = Assistant::where('status',1)->latest()->get();
        $sectors = Sector::latest()->get();
        $locations = Location::latest()->get();
        $total_online_earn = Deposit::where('doctor_id',$doctor->id)->where('status',1)->sum('amount');
        $total_cash_earn = $doctor->balance - $total_online_earn;
        $appointment_done = Appointment::where('doctor_id',$doctor->id)->where('try',1)->where('is_complete',1)->count();
        $appointment_trashed = Appointment::where('doctor_id',$doctor->id)->where('d_status',1)->count();
        $total_appointment = Appointment::where('doctor_id',$doctor->id)->where('try',1)->count();
        return view('admin.doctors.detail', compact('page_title', 'doctor','assistants','sectors','locations','total_online_earn','total_cash_earn','appointment_done','total_appointment','appointment_trashed'));
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:doctors,email,'.$doctor->id,
            'mobile' => 'required|string|max:191|unique:doctors,mobile,'. $doctor->id,
            'address' => 'required|string|max:191',
            'sector_id' => 'required|numeric|gt:0',
            'qualification' => 'required|string|max:180',
            'location_id' => 'required|numeric|gt:0',
            'fees' => 'required|numeric',
            'rating' => 'required|numeric|gt:0|max:5',
        ]);



        if ($request->email != $doctor->email && Doctor::whereEmail($request->email)->whereId('!=', $doctor->id)->count() > 0) {
            $notify[] = ['error', 'Email already exists.'];
            return back()->withNotify($notify);
        }
        if ($request->mobile != $doctor->mobile && Doctor::where('mobile', $request->mobile)->whereId('!=', $doctor->id)->count() > 0) {
            $notify[] = ['error', 'Mobile number already exists.'];
            return back()->withNotify($notify);
        }

        $doctor->update([
            'mobile' => $request->mobile,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'sector_id' => $request->sector_id,
            'qualification' => $request->qualification,
            'location_id' => $request->location_id,
            'fees' => $request->fees,
            'rating' => $request->rating,
            'status' => $request->status ? 1 : 0,
            'ev' => $request->ev ? 1 : 0,
            'sv' => $request->sv ? 1 : 0,
            'tv' => $request->tv ? 1 : 0,
            'ts' => $request->ts ? 1 : 0,
            'featured' => $request->featured ? 1 : 0,
        ]);

        $notify[] = ['success', 'Doctor detail has been updated'];
        return redirect()->back()->withNotify($notify);
    }

    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $doctors = Doctor::where(function ($doctor) use ($search) {
            $doctor->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('mobile', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%");
        });
        $page_title = '';
        switch ($scope) {
            case 'active':
                $page_title .= 'Active ';
                $doctors = $doctors->where('status', 1);
                break;
            case 'banned':
                $page_title .= 'Banned';
                $doctors = $doctors->where('status', 0);
                break;
            case 'emailUnverified':
                $page_title .= 'Email Unerified ';
                $doctors = $doctors->where('ev', 0);
                break;
            case 'smsUnverified':
                $page_title .= 'SMS Unverified ';
                $doctors = $doctors->where('sv', 0);
                break;
        }
        $doctors = $doctors->paginate(getPaginate());
        $page_title .= 'User Search - ' . $search;
        $empty_message = 'No search result found';
        return view('admin.doctors.list', compact('page_title', 'search', 'scope', 'empty_message', 'doctors'));
    }

    public function doctorLoginHistory($id)
    {
        $doctor = Doctor::findOrFail($id);
        $page_title = 'User Login History - ' . $doctor->username;
        $empty_message = 'No Doctors login found.';
        $login_logs = $doctor->login_logs()->latest()->paginate(getPaginate());
        return view('admin.doctors.logins', compact('page_title', 'empty_message', 'login_logs'));
    }

    public function loginHistory(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
            $page_title = 'Doctor Login History Search - ' . $search;
            $empty_message = 'No search result found.';
            $login_logs = DoctorLogin::whereHas('doctor', function ($query) use ($search) {
                $query->where('username', $search);
            })->latest()->paginate(getPaginate());
            return view('admin.doctors.logins', compact('page_title', 'empty_message', 'search', 'login_logs'));
        }
        $page_title = 'Doctor Login History';
        $empty_message = 'No doctors login found.';
        $login_logs = DoctorLogin::latest()->paginate(getPaginate());
        return view('admin.doctors.logins', compact('page_title', 'empty_message', 'login_logs'));
    }

    public function loginIpHistory($ip)
    {
        $page_title = 'Login By - ' . $ip;
        $login_logs = DoctorLogin::where('doctor_ip',$ip)->latest()->paginate(getPaginate());
        $empty_message = 'No doctors login found.';
        return view('admin.doctors.logins', compact('page_title', 'empty_message', 'login_logs'));

    }

    public function showEmailSingleForm($id)
    {
        $doctor = Doctor::findOrFail($id);
        $page_title = 'Send Email To: ' . $doctor->username;
        return view('admin.doctors.email_single', compact('page_title', 'doctor'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $doctor = Doctor::findOrFail($id);
        send_general_email($doctor->email, $request->subject, $request->message, $doctor->username);
        $notify[] = ['success', $doctor->username . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function showEmailAllForm()
    {
        $page_title = 'Send Email To All Mentors';
        return view('admin.doctors.email_all', compact('page_title'));
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        foreach (Doctor::where('status', 1)->cursor() as $doctor) {
            send_general_email($doctor->email, $request->subject, $request->message, $doctor->username);
        }

        $notify[] = ['success', 'All Doctors will receive an email shortly.'];
        return back()->withNotify($notify);
    }
}
