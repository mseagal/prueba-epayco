<?php

require_once "vendor/econea/nusoap/src/nusoap.php";

// Nombre del servicio
$namespace = "RegisterClientSOAP";
$server = new soap_server();
$server->configureWSDL("SoapService",$namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Estructura del servicio
$server->wsdl->addComplexType(
    'RegisterClient',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'cli_doc' => array('name' => 'cli_doc', 'type' => 'xsd:string'),
        'cli_name' => array('name' => 'cli_name', 'type' => 'xsd:string'),
        'cli_email' => array('name' => 'cli_email', 'type' => 'xsd:string'),
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
    "RegisterClientService",
    array("RegisterClient" => "tns:RegisterClient"),
    array("RegisterClient" => "tns:response"),
    $namespace,
    false,
    "rpc",
    "encoded",
    "registra un cliente"
);

function RegisterClientService($request)
{
    $cod_error = '00';
    $message_error = 'ok';
    $data = null;

    if(isset($request['cli_doc']) && isset($request['cli_name']) && isset($request['cli_email']) && isset($request['cli_cel']) ){
        require_once "config/conexion.php";
        require_once "models/Client.php";

        $client = new Client();
        if (!$client->existsClient($request['cli_doc'],$request['cli_email'])) {

            $data = $client->insert_client($request['cli_doc'],$request['cli_name'],$request['cli_email'],$request['cli_cel']);
            
        }else{

            $cod_error = '02';
            $message_error = "Cliente ya existe o Correo duplicado";

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