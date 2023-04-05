<?php

namespace App\Http\Controllers\Gateway\NIFTepay;

use App\Appointment;
use App\Deposit;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use function Sodium\crypto_aead_aes256gcm_encrypt;
use function Sodium\crypto_shorthash;

class ProcessController extends Controller
{
    /*
     * NIFT Pay Gateway
     */

    public static function process($deposit)
    {
        $merchant_creds = json_decode(json_decode($deposit)->gateway->parameters);
        $currency = json_decode(json_decode($deposit)->gateway->supported_currencies)->PKR;
//        dd(json_decode(json_decode($deposit)->gateway->parameters)->Merchant_Name->value);
        $deposit = json_decode($deposit);

        $merchant_creds = json_decode($deposit->gateway->parameters);
        $deposit->user_id = auth()->guard('user')->user()->id;
        $val['pp_Version'] = "1.1";
        $val['pp_Language'] = "EN";
        $val['pp_MerchantID'] = $merchant_creds->Merchant_ID->value;
        $val['pp_SubMerchantID'] = "";
        $val['pp_Password'] = $merchant_creds->Password->value;
        $val['pp_TxnRefNo'] = "T" . session()->get('appoinment_data')['trx'];
        $val['pp_Amount'] = $deposit->amount * 100;
        $val['pp_TxnCurrency'] = "PKR";
        $val['pp_TxnDateTime'] =session()->get('appoinment_data')['trx'];
        $val['pp_BillReference'] = "T" . session()->get('appoinment_data')['trx'];
        $val['pp_Description'] = "Item(s) Bought";
        $val['pp_TxnExpiryDateTime'] = Carbon::now()->addHour(1)->format('YmdHms');
        $val['pp_ReturnURL'] = $merchant_creds->Return_URL->value;
        $val['Salt'] = $merchant_creds->Integrity_Salt->value;
        $val['pp_SecureHash'] = hash_hmac('SHA256', 'key', $merchant_creds->Integrity_Salt->value);
        $send['val'] = $val;

        $send['view'] = 'user.payment.redirect';
        $send['method'] = 'post';
        $send['url'] = 'https://uat-merchants.niftepay.pk/CustomerPortal/transactionmanagement/merchantform'; // use for sandbod text
        return json_encode($send);


    }

    public function ipn()
    {
        $track = $_GET['invoice_id'];
        $value_in_btc = $_GET['value'] / 100000000;
        $data = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();
        if ($data->btc_amo == $value_in_btc && $_GET['address'] == $data->btc_wallet && $_GET['secret'] == "ABIR" && $_GET['confirmations'] > 2 && $data->status == 0) {
            PaymentController::userDataUpdate($data->trx);
        }
    }
}
