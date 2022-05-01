<?php

$location = "http://localhost:8080/InsertCategory.php?wsdl";
$request = "
<soapenv:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ins=\"InsertCategorySOAP\">
<soapenv:Header/>
<soapenv:Body>
   <ins:InsertCategoryService soapenv:encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\">
      <InsertCategory xsi:type=\"ins:InsertCategory\">
         <!--You may enter the following 3 items in any order-->
         <usu_nom xsi:type=\"xsd:string\">Consumo Test</usu_nom>
         <usu_ape xsi:type=\"xsd:string\">Consuto Test ape</usu_ape>
         <usu_correo xsi:type=\"xsd:string\">cosumo@test.com</usu_correo>
      </InsertCategory>
   </ins:InsertCategoryService>
</soapenv:Body>
</soapenv:Envelope>
";

print("Request : <br>");
print("<pre>".htmlentities($request)."</pre>");

$action = "InsertCategoryService";
$headers = [
    'Method: POST',
    'Connection: Keep-Alive',
    'User-Agent: PHP-SOAP-CURL',
    'Content-Type: text/xml; charset=utf-8',
    'SOAPAction: "InsertCategoryService"'
];

$ch = curl_init($location);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$request);
curl_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);

$response = curl_exec($ch);
$err_status = curl_errno($ch);

print("Response : <br>");
print("<pre>".$response."</pre>");
?>