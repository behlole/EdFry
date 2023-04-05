<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{$general->sitename}}</title>
</head>

<body>
<form action="{{$data->url}}" method="{{$data->method}}" id="auto_submit">
    @foreach($data->val as $k=> $v)
        <input type="hidden" id="{{$k}}" name="{{$k}}" value="{{$v}}"/>
    @endforeach
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

<script>
    "use strict";
    let action_url = 'https://uat-merchants.niftepay.pk/CustomerPortal/transactionmanagement/merchantform'
    if (document.getElementById('auto_submit').action == action_url) {
        var x;

        function Xero(x) {
            return x > 9 ? "" + x : "0" + x;
        }

        function getDateTime(incrementDays) {
            var d = new Date();
            d.setDate(d.getDate() + incrementDays);
            var M = d.getMonth() + 1;
            var D = d.getDate();
            var H = d.getHours();
            var m = d.getMinutes() + 1;
            var s = d.getSeconds() + 1;
            var dateTime = "".concat(d.getFullYear().toString(), Xero(M), Xero(D), Xero(H), Xero(m), Xero(s));
            return dateTime;
        }

        let dateTime = getDateTime(0);
        document.getElementById("pp_TxnRefNo").value = "T" + dateTime;
        document.getElementById("pp_BillReference").value = "T" + dateTime;
        document.getElementById("pp_TxnExpiryDateTime").value = dateTime;
        document.getElementById("pp_TxnExpiryDateTime").value = getDateTime(2);
        var inputs = {
            "pp_Amount": document.getElementById("pp_Amount").value,
            "pp_BillReference": document.getElementById("pp_BillReference").value,
            "pp_Description": document.getElementById("pp_Description").value,
            "pp_Language": document.getElementById("pp_Language").value,
            "pp_MerchantID": document.getElementById("pp_MerchantID").value,
            "pp_Password": document.getElementById("pp_Password").value,
            "pp_ReturnURL": document.getElementById("pp_ReturnURL").value,
            "pp_SubMerchantID": document.getElementById("pp_SubMerchantID").value,
            "pp_TxnCurrency": document.getElementById("pp_TxnCurrency").value,
            "pp_TxnDateTime": document.getElementById("pp_TxnDateTime").value,
            "pp_TxnExpiryDateTime": document.getElementById("pp_TxnExpiryDateTime").value,
            "pp_TxnRefNo": document.getElementById("pp_TxnRefNo").value,
            "pp_Version": document.getElementById("pp_Version").value,
        }
        let concatenatedString = document.getElementById("Salt").value;
        Object.keys(inputs).sort().forEach(key => {
            concatenatedString = !(inputs[key] === "" || inputs[key] == undefined) ? concatenatedString + "&" + inputs[key] : concatenatedString;
        });
        var hash = CryptoJS.HmacSHA256(concatenatedString, document.getElementById("Salt").value);
        document.getElementById("pp_SecureHash").value = hash.toString();
        document.getElementById("auto_submit").submit();
    } else {
        document.getElementById("auto_submit").submit();
    }
</script>
</body>

</html>

