<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\Deposit;
use App\Doctor;
use App\GeneralSetting;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class DepositController extends Controller
{

    public function pending()
    {
        $page_title = 'Pending Payments';
        $empty_message = 'No pending payments';
        $deposits = Deposit::where('method_code', '>=', 1000)->where('status', 2)->with(['gateway'])->latest()->paginate(getPaginate());
        return view('admin.deposit.log', compact('page_title', 'empty_message', 'deposits'));
    }


    public function approved()
    {
        $page_title = 'Approved Payments';
        $empty_message = 'No approved payments';
        $deposits = Deposit::where('method_code','>=',1000)->where('status', 1)->with(['gateway'])->latest()->paginate(getPaginate());
        return view('admin.deposit.log', compact('page_title', 'empty_message', 'deposits'));
    }

    public function successful()
    {
        $page_title = 'Successful Payments';
        $empty_message = 'No successful payments';
        $deposits = Deposit::where('status', 1)->with(['gateway'])->latest()->paginate(getPaginate());
        return view('admin.deposit.log', compact('page_title', 'empty_message', 'deposits'));
    }

    public function rejected()
    {
        $page_title = 'Rejected Payments';
        $empty_message = 'No rejected payments';
        $deposits = Deposit::where('method_code', '>=', 1000)->where('status', 3)->with(['gateway'])->latest()->paginate(getPaginate());
        return view('admin.deposit.log', compact('page_title', 'empty_message', 'deposits'));
    }

    public function deposit()
    {
        $page_title = 'Payments History';
        $empty_message = 'No payment history available.';
        $deposits = Deposit::with(['gateway'])->where('status','!=',0)->latest()->paginate(getPaginate());
        return view('admin.deposit.log', compact('page_title', 'empty_message', 'deposits'));
    }

    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $page_title = '';
        $empty_message = 'No search result was found.';
        $deposits = Deposit::with(['gateway'])->where('status','!=',0)->where(function ($q) use ($search) {
            $q->where('trx', 'like', "%$search%")->orWhereHas(function ($user) use ($search) {
                $user->where('username', 'like', "%$search%");
            });
        });
        switch ($scope) {
            case 'pending':
                $page_title .= 'Pending Payments Search';
                $deposits = $deposits->where('method_code', '>=', 1000)->where('status', 2);
                break;
            case 'approved':
                $page_title .= 'Approved Payments Search';
                $deposits = $deposits->where('method_code', '>=', 1000)->where('status', 1);
                break;
            case 'rejected':
                $page_title .= 'Rejected Payments Search';
                $deposits = $deposits->where('method_code', '>=', 1000)->where('status', 3);
                break;
            case 'list':
                $page_title .= 'Payments History Search';
                break;
        }
        $deposits = $deposits->paginate(getPaginate());
        $page_title .= ' - ' . $search;

        return view('admin.deposit.log', compact('page_title', 'search', 'scope', 'empty_message', 'deposits'));
    }

    public function details($id)
    {
        $general = GeneralSetting::first();
        $deposit = Deposit::where('id', $id)->where('method_code', '>=', 1000)->with(['gateway'])->firstOrFail();
        $page_title = 'Appointment request for '.$deposit->doctor->name.' Amount ' . getAmount($deposit->amount) . ' '.$general->cur_text;
        $details = ($deposit->detail != null) ? json_encode($deposit->detail) : null;

        return view('admin.deposit.detail', compact('page_title', 'deposit','details'));
    }




    public function approve(Request $request)
    {

        $request->validate(['id' => 'required|integer']);
        $deposit = Deposit::where('id',$request->id)->where('status',2)->firstOrFail();
        $deposit->update(['status' => 1]);

        $appointment = Appointment::where('trx',$deposit->trx)->latest()->first();
        $appointment->p_status = 1;
        $appointment->try = 1;
        $appointment->save();

        $doctor = Doctor::findOrFail($appointment->doctor->id);
        $doctor->balance += $deposit->amount;
        $doctor->save();

        $gnl = GeneralSetting::first();


        $patient = 1;
        notify($appointment, 'PAYMENT_APPROVE', [
            'method_name' => $deposit->gateway_currency()->name,
            'method_currency' => $deposit->method_currency,
            'method_amount' => getAmount($deposit->final_amo),
            'amount' => getAmount($deposit->amount),
            'charge' => getAmount($deposit->charge),
            'currency' => $gnl->cur_text,
            'rate' => getAmount($deposit->rate),
            'trx' => $deposit->trx,
            'booking_date' => $appointment->booking_date,
            'time_serial' => $appointment->time_serial,
            'doctor_name' => $appointment->doctor->name,
        ],$patient);

        $notify[] = ['success', 'payment has been approved.'];

        return redirect()->route('admin.deposit.pending')->withNotify($notify);
    }

    public function reject(Request $request)
    {

        $request->validate([
            'id' => 'required|integer',
            'message' => 'required|max:250'
        ]);

        $deposit = Deposit::where('id',$request->id)->where('status',2)->firstOrFail();
        $appointment = Appointment::where('trx',$deposit->trx)->latest()->first();
        $deposit->admin_feedback = $request->message;
        $deposit->status = 3;
        $deposit->save();

        $appointment->d_status = 1;
        $appointment->d_admin = 1;
        $appointment->save();

        $gnl = GeneralSetting::first();
        $patient = 1;
        notify($appointment, 'DEPOSIT_REJECT', [
            'method_name' => $deposit->gateway_currency()->name,
            'method_currency' => $deposit->method_currency,
            'method_amount' => getAmount($deposit->final_amo),
            'amount' => getAmount($deposit->amount),
            'charge' => getAmount($deposit->charge),
            'currency' => $gnl->cur_text,
            'rate' => getAmount($deposit->rate),
            'trx' => $deposit->trx,
            'rejection_message' => $request->message
        ],$patient);

        $notify[] = ['success', 'Deposit has been rejected.'];
        return  redirect()->route('admin.deposit.pending')->withNotify($notify);

    }
}
