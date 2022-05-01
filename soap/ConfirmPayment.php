<?php

require_once "vendor/econea/nusoap/src/nusoap.php";

// Nombre del servicio
$namespace = "ConfirmPaymentSOAP";
$server = new soap_server();
$server->configureWSDL("SoapService",$namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Estructura del servicio
$server->wsdl->addComplexType(
    'ConfirmPayment',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'pay_session_id' => array('name' => 'pay_session_id', 'type' => 'xsd:string'),
        'pay_token' => array('name' => 'pay_token', 'type' => 'xsd:string')
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
    "ConfirmPaymentService",
    array("ConfirmPayment" => "tns:ConfirmPayment"),
    array("ConfirmPayment" => "tns:response"),
    $namespace,
    false,
    "rpc",
    "encoded",
    "recarga una cuenta de cliente"
);

function ConfirmPaymentService($request)
{
    $cod_error = '00';
    $message_error = 'ok';
    $data = null;

    if (isset($request['pay_session_id']) && isset($request['pay_token'])) {
        require_once "config/conexion.php";
        require_once "models/Payment.php";
        require_once "models/Client.php";

        $payment = new Payment();
        $respPayment = $payment->getPayment($request['pay_token']);
        if (count($respPayment) > 0) {
            $now = new DateTime('now',new DateTimeZone('America/Bogota'));
            $expirationDate = new DateTime($respPayment['pay_expires_at'],new DateTimeZone('America/Bogota'));

            if ( $respPayment['pay_session_id'] == $request['pay_session_id'] && $expirationDate > $now && $respPayment['pay_revoked'] == 0 && $respPayment['pay_approved'] == 0) {
                $client = new Client();
                $respClient = $client->getClientById($respPayment['cli_id']);
                
                $leftBalance = $respClient['cli_balance'] - $respPayment['pay_amount'];
                if ($leftBalance >= 0) {
                   $client->updateBalance($respClient['cli_id'],$leftBalance);
                   $payment->approvePayment($respPayment['pay_session_id']);
                   $data = "Pago realizado correctamente";
                   
                }else{
                    $cod_error = '03';
                    $message_error = "Fondos insuficientes";
                    $payment->revokeLeftPayments($respPayment['cli_id']);
                }

            }else{
                $cod_error = '02';
                $message_error = "La sesion ha expirado"; 
            }
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