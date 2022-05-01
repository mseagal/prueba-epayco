<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetBalanceRequest;
use App\Http\Requests\RechargeAccountRequest;
use App\Http\Requests\RegisterClientRequest;

class ClientController extends Controller
{
    public function RegisterClient(RegisterClientRequest $request)
    {   
        $wsdl = env('URL_SERVICES').'RegisterClient.php?wsdl';
        $cliente = new \SoapClient($wsdl);
        $args = [
            'cli_doc' => $request->doc,
            'cli_name' => $request->name,
            'cli_email' => $request->email,
            'cli_cel' => $request->cel,
        ];
        $resultado = $cliente->RegisterClientService($args);
        
        return response()->json($resultado);
    }

    public function RechargeAccount(RechargeAccountRequest $request)
    {
        $wsdl = env('URL_SERVICES').'RechargeAccount.php?wsdl';
        $cliente = new \SoapClient($wsdl);
        $args = [
            'cli_doc' => $request->doc,
            'cli_cel' => $request->cel,
            'amount' => $request->amount
        ];

        $resultado = $cliente->RechargeAccountService($args);
        return response()->json($resultado);

    }

    public function GetBalance(GetBalanceRequest $request)
    {
        $wsdl = env('URL_SERVICES').'GetBalance.php?wsdl';
        $cliente = new \SoapClient($wsdl);
        $args = [
            'cli_doc' => $request->doc,
            'cli_cel' => $request->cel
        ];

        $resultado = $cliente->GetBalanceService($args);
        return response()->json($resultado);
    }
}
