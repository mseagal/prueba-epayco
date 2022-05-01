<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function NewPayment(PaymentRequest $request)
    {
        $wsdl = env('URL_SERVICES').'Payment.php?wsdl';
        $cliente = new \SoapClient($wsdl);
        $args = [
            'cli_doc' => $request->doc,
            'amount' => $request->amount
        ];

        $resultado = $cliente->PaymentService($args);
        return response()->json($resultado);
    }

    public function ConfirmPayment(Request $request)
    {
        $wsdl = env('URL_SERVICES').'ConfirmPayment.php?wsdl';
        $cliente = new \SoapClient($wsdl);
        $args = [
            'pay_session_id' => $request->input('session_id',''),
            'pay_token' => $request->input('token','')
        ];

        $resultado = $cliente->ConfirmPaymentService($args);
        return response()->json($resultado);

    }
}
