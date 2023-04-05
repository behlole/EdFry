<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\Doctor;
use App\GeneralSetting;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class AppointmentController extends Controller
{
    public function createAppointment(){
        $page_title = 'Create Appoiment';
        $doctors = Doctor::where('status',1)->latest()->get();
        return view('admin.appointment.create-appointment',compact('page_title','doctors'));
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

        return view('admin.appointment.book-appointment',compact('doctor','page_title','available_date'));
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
                'admin' => 1,
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
        $page_title = 'New Appointments';
        $appointments = Appointment::where('try',1)->where('is_complete',0)->where('d_status',0)->whereHas('doctor', function ($query) {
            $query->where('status',1);
       })->latest()->paginate(getPaginate());
        $empty_message = 'No Appointment Found';
        return view('admin.appointment.appointment',compact('page_title','appointments','empty_message'));
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

    public function appointmentDone(){
        $page_title = 'Done Appointments';
        $appointments = Appointment::where('try',1)->where('is_complete',1)->where('d_status',0)->whereHas('doctor', function ($query) {
            $query->where('status',1);
       })->latest()->paginate(getPaginate());
        $empty_message = 'No Done Appointment Found';
        return view('admin.appointment.appointment',compact('page_title','appointments','empty_message'));
    }

    public function appointmentRemove($id){
        $appointment = Appointment::findOrFail($id);
        $appointment->d_status = 1;
        $appointment->d_admin = 1;
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

        $appointments = Appointment::where('d_status',1)->whereHas('doctor', function ($query) {
            $query->where('status',1);
       })->latest()->paginate(getPaginate());
        $empty_message = 'No Trashed Appointment Found';

        return view('admin.appointment.trashed-appointment',compact('page_title','appointments','empty_message'));
    }
}
