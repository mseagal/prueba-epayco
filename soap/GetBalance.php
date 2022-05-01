<?php

require_once "vendor/econea/nusoap/src/nusoap.php";

// Nombre del servicio
$namespace = "GetBalanceSOAP";
$server = new soap_server();
$server->configureWSDL("SoapService",$namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Estructura del servicio
$server->wsdl->addComplexType(
    'GetBalance',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'cli_doc' => array('name' => 'cli_doc', 'type' => 'xsd:string'),
        'cli_cel' => array('name' => 'cli_cel', 'type' => 'xsd:string')
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
    "GetBalanceService",
    array("GetBalance" => "tns:GetBalance"),
    array("GetBalance" => "tns:response"),
    $namespace,
    false,
    "rpc",
    "encoded",
    "recarga una cuenta de cliente"
);

function GetBalanceService($request)
{
    $cod_error = '00';
    $message_error = 'ok';
    $data = null;

    if(isset($request['cli_doc']) && isset($request['cli_cel'])){
        require_once "config/conexion.php";
        require_once "models/Client.php";
        $client = new Client();

        if ($respClient = $client->getClientByDocAndCel($request['cli_doc'],$request['cli_cel'])) {
            $data = $respClient['cli_balance'];
            
        }else{
            $cod_error = '02';
            $message_error = "Los datos no coinciden"; 
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