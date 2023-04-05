<?php

namespace App\Http\Controllers\Admin;

use App\Assistant;
use App\AssistantDoctorTrack;
use App\AssistantLogin;
use App\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use Illuminate\Support\Facades\Hash;

class ManageAssistantController extends Controller
{
    public function allAssistants(){
        $page_title = 'All Assistants';
        $empty_message = 'No assistants found';
        $assistants = Assistant::latest()->paginate(getPaginate());
        return view('admin.assistants.list', compact('page_title', 'empty_message', 'assistants'));
    }

    public function activeAssistants()
    {
        $page_title = 'Manage Active Assistant';
        $empty_message = 'No active user found';
        $assistants = Assistant::active()->latest()->paginate(getPaginate());
        return view('admin.assistants.list', compact('page_title', 'empty_message', 'assistants'));
    }

    public function bannedAssistants()
    {
        $page_title = 'Banned Users';
        $empty_message = 'No banned user found';
        $assistants = Assistant::banned()->latest()->paginate(getPaginate());
        return view('admin.assistants.list', compact('page_title', 'empty_message', 'assistants'));
    }

    public function emailUnverifiedAssistants()
    {
        $page_title = 'Email Unverified Users';
        $empty_message = 'No email unverified user found';
        $assistants = Assistant::emailUnverified()->latest()->paginate(getPaginate());
        return view('admin.assistants.list', compact('page_title', 'empty_message', 'assistants'));
    }
    public function emailVerifiedAssistants()
    {
        $page_title = 'Email Verified Users';
        $empty_message = 'No email verified user found';
        $assistants = Assistant::emailVerified()->latest()->paginate(getPaginate());
        return view('admin.assistants.list', compact('page_title', 'empty_message', 'assistants'));
    }


    public function smsUnverifiedAssistants()
    {
        $page_title = 'SMS Unverified Users';
        $empty_message = 'No sms unverified user found';
        $assistants = Assistant::smsUnverified()->latest()->paginate(getPaginate());
        return view('admin.assistants.list', compact('page_title', 'empty_message', 'assistants'));
    }
    public function smsVerifiedAssistants()
    {
        $page_title = 'SMS Verified Users';
        $empty_message = 'No sms verified user found';
        $assistants = Assistant::smsVerified()->latest()->paginate(getPaginate());
        return view('admin.assistants.list', compact('page_title', 'empty_message', 'assistants'));
    }

    public function newAssistant(){
        $page_title = 'Add New Assiatant';
        $doctors = Doctor::where('status',1)->latest()->get();
        return view('admin.assistants.new', compact('page_title','doctors'));
    }

    public function storeAssistant(Request $request){

        $request->validate([
            'image' => [new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'name' => 'required|string|max:190',
            'email' => 'required|string|email|max:190|unique:assistants',
            'mobile' => 'required|string|max:190|unique:assistants',
            'password' => 'required|string|min:6|confirmed',
            'username' => 'required|alpha_num|unique:assistants|min:6',
            'address' => 'required|string|max:190',
        ]);

        $assistant_image = '';
        if($request->hasFile('image')) {
            try{

                $location = imagePath()['assistant']['path'];
                $size = imagePath()['assistant']['size'];

                $assistant_image = uploadImage($request->image, $location , $size);

            }catch(\Exception $exp) {
                return back()->withNotify(['error', 'Could not upload the image.']);
            }
        }

        $doctors = Doctor::findOrFail($request->doctor_id);

        $assistant = Assistant::create([
            'image' => $assistant_image,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'username' => $request->username,
            'address' => $request->address,
        ]);

        if ($doctors != null) {
            $assistant->doctors()->sync($doctors->pluck('id'));
        }

        notify($assistant, 'ASSISTANT_CREDENTIALS', [
            'username' => $assistant->username,
            'password' => $request->password,
        ]);

        $notify[] = ['success', 'Assistant details has been added'];
        return back()->withNotify($notify);
    }

    public function detail($id)
    {
        $page_title = 'Assistant Detail';
        $assistant = Assistant::findOrFail($id);
        $doctors = Doctor::where('status',1)->latest()->get();
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
        return view('admin.assistants.detail', compact('page_title', 'assistant','doctors','total_doctor','done_appointment','new_appointment'));
    }

    public function update(Request $request, $id)
    {
        $assistant = Assistant::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:190',
            'email' => 'required|email|max:190|unique:assistants,email,' . $assistant->id,
            'mobile' => 'required|string|max:190|unique:assistants,mobile,' . $assistant->id,
            'address' => 'required|string|max:190',
        ]);

        if ($request->email != $assistant->email && Assistant::whereEmail($request->email)->whereId('!=', $assistant->id)->count() > 0) {
            $notify[] = ['error', 'Email already exists.'];
            return back()->withNotify($notify);
        }
        if ($request->mobile != $assistant->mobile && Assistant::where('mobile', $request->mobile)->whereId('!=', $assistant->id)->count() > 0) {
            $notify[] = ['error', 'Mobile number already exists.'];
            return back()->withNotify($notify);
        }

        $doctors = Doctor::find($request->doctor_id);

        $assistant->update([
            'mobile' => $request->mobile,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'status' => $request->status ? 1 : 0,
            'ev' => $request->ev ? 1 : 0,
            'sv' => $request->sv ? 1 : 0,
            'tv' => $request->tv ? 1 : 0,
            'ts' => $request->ts ? 1 : 0,
        ]);

        if($doctors != null){
            $assistant->doctors()->sync($doctors->pluck('id'));
        }else{
            AssistantDoctorTrack::where('assistant_id',$id)->delete();
        }

        $notify[] = ['success', 'Assistant detail has been updated'];
        return redirect()->back()->withNotify($notify);
    }

    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $assistants = Assistant::where(function ($assistant) use ($search) {
            $assistant->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('mobile', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%");
        });
        $page_title = '';
        switch ($scope) {
            case 'active':
                $page_title .= 'Active ';
                $assistants = $assistants->where('status', 1);
                break;
            case 'banned':
                $page_title .= 'Banned';
                $assistants = $assistants->where('status', 0);
                break;
            case 'emailUnverified':
                $page_title .= 'Email Unerified ';
                $assistants = $assistants->where('ev', 0);
                break;
            case 'smsUnverified':
                $page_title .= 'SMS Unverified ';
                $assistants = $assistants->where('sv', 0);
                break;
        }
        $assistants = $assistants->paginate(getPaginate());
        $page_title .= 'Assistant Search - ' . $search;
        $empty_message = 'No search result found';
        return view('admin.assistants.list', compact('page_title', 'search', 'scope', 'empty_message', 'assistants'));
    }

    public function assistantLoginHistory($id)
    {
        $assistant = Assistant::findOrFail($id);
        $page_title = 'Assistant Login History - ' . $assistant->username;
        $empty_message = 'No assistants login found.';
        $login_logs = $assistant->login_logs()->latest()->paginate(getPaginate());
        return view('admin.assistants.logins', compact('page_title', 'empty_message', 'login_logs'));
    }

    public function loginHistory(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
            $page_title = 'Assistant Login History Search - ' . $search;
            $empty_message = 'No search result found.';
            $login_logs = AssistantLogin::whereHas('assistant', function ($query) use ($search) {
                $query->where('username', $search);
            })->latest()->paginate(getPaginate());
            return view('admin.assistants.logins', compact('page_title', 'empty_message', 'search', 'login_logs'));
        }
        $page_title = 'Assistant Login History';
        $empty_message = 'No assistant login found.';
        $login_logs = AssistantLogin::latest()->paginate(getPaginate());
        return view('admin.assistants.logins', compact('page_title', 'empty_message', 'login_logs'));
    }

    public function loginIpHistory($ip)
    {
        $page_title = 'Login By - ' . $ip;
        $login_logs = AssistantLogin::where('assistant_ip',$ip)->latest()->paginate(getPaginate());
        $empty_message = 'No assistants login found.';
        return view('admin.assistants.logins', compact('page_title', 'empty_message', 'login_logs'));

    }

    public function showEmailSingleForm($id)
    {
        $assistant = Assistant::findOrFail($id);
        $page_title = 'Send Email To: ' . $assistant->username;
        return view('admin.assistants.email_single', compact('page_title', 'assistant'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $assistant = Assistant::findOrFail($id);
        send_general_email($assistant->email, $request->subject, $request->message, $assistant->username);
        $notify[] = ['success', $assistant->username . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function showEmailAllForm()
    {
        $page_title = 'Send Email To All Assistants';
        return view('admin.assistants.email_all', compact('page_title'));
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        foreach (Assistant::where('status', 1)->cursor() as $assistant) {
            send_general_email($assistant->email, $request->subject, $request->message, $assistant->username);
        }

        $notify[] = ['success', 'All Assistants will receive an email shortly.'];
        return back()->withNotify($notify);
    }
}
