<?php

require_once "vendor/econea/nusoap/src/nusoap.php";

// Nombre del servicio
$namespace = "RechargeAccountSOAP";
$server = new soap_server();
$server->configureWSDL("SoapService",$namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Estructura del servicio
$server->wsdl->addComplexType(
    'RechargeAccount',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'cli_doc' => array('name' => 'cli_doc', 'type' => 'xsd:string'),
        'cli_cel' => array('name' => 'cli_cel', 'type' => 'xsd:string'),
        'amount' => array('name' => 'amount', 'type' => 'xsd:decimal')
    )
    );

// Estructura de la respuesta del servicio
$server->wsdl->addComplexType(
    'response',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'success' => array('name' => 'success', 'type' => 'xsd:boolean'),
        'cod_error' => array('name' => 'cod_error', 'type' => 'xsd:string'),
        'message_error' => array('name' => 'message_error', 'type' => 'xsd:string'),
        'data' => array('name' => 'data', 'type' => 'xsd:string')
    )
    );

$server->register(
    "RechargeAccountService",
    array("RechargeAccount" => "tns:RechargeAccount"),
    array("RechargeAccount" => "tns:response"),
    $namespace,
    false,
    "rpc",
    "encoded",
    "recarga una cuenta de cliente"
);

function RechargeAccountService($request)
{
    $cod_error = '00';
    $message_error = 'ok';
    $data = null;

    if (isset($request['cli_doc']) && isset($request['cli_cel']) && isset($request['amount'])) {
        require_once "config/conexion.php";
        require_once "models/Client.php";

        $client = new Client();
        if ($resp = $client->validateAccount($request['cli_doc'],$request['cli_cel'])) {
            if (is_numeric($request['amount']) && $request['amount'] > 0) {
                $client->rechargeAccount($request['cli_doc'],$resp['cli_balance'] + $request['amount']);
                $data = 'Recarga exitosa';
            }else{
                $cod_error = '03';
                $message_error = "Monto invalido";
            }
        }else{
            $cod_error = '02';
            $message_error = "Cuenta inexistente";
        }
    }else{
        $cod_error = '01';
        $message_error = "Faltan datos";
    }

    return array(
        'success' => $cod_error == '00' ? true : false,
        'cod_error' => $cod_error,
        'message_error' => $message_error,
        'data' => $data
    );
}


$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();

?>