<?php

require_once "vendor/econea/nusoap/src/nusoap.php";

// Nombre del servicio
$namespace = "PaymentSOAP";
$server = new soap_server();
$server->configureWSDL("SoapService",$namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Estructura del servicio
$server->wsdl->addComplexType(
    'Payment',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'cli_doc' => array('name' => 'cli_doc', 'type' => 'xsd:string'),
        'amount' => array('name' => 'amount', 'type' => 'xsd:string')
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
    "PaymentService",
    array("Payment" => "tns:Payment"),
    array("Payment" => "tns:response"),
    $namespace,
    false,
    "rpc",
    "encoded",
    "Realiza un pago"
);

function PaymentService($request)
{
    $cod_error = '00';
    $message_error = 'ok';
    $data = null;

    if(isset($request['cli_doc']) && isset($request['amount'])){
        require_once "config/conexion.php";
        require_once "models/Client.php";
        require_once "models/Payment.php";
        require_once "./config/mail.php";

        $client = new Client();
        if ($respCli = $client->existsClient($request['cli_doc'],'')) {
            if (is_numeric($request['amount']) && $request['amount'] > 0) {
                $token = rand(100000,999999);
                $expires_at = new DateTime('+15 minute',new DateTimeZone('America/Bogota'));
                
                $payment = new Payment();
                $payment->createPayment($token,$expires_at->format('Y-m-d H:i:s'),$respCli['cli_id'],$request['amount']);
                $paymentCreated = $payment->getPayment($token);
    
                $mail = new Mailing();
                if($mail->sendEmail($respCli['cli_name'],$respCli['cli_email'],$paymentCreated['pay_session_id'],$paymentCreated['pay_token'])){
                    $data = "Se ha enviado el correo de confirmacion para aprobar la compra";
                }else{
                    $cod_error = '04';
                    $message_error = "El mensaje no pudo ser enviado";
                };
    
            }else{
                $cod_error = '03';
                $message_error = "Monto invalido";
            }
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