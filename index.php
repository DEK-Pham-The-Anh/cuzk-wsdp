<h1>CUZK WSDP SOAP CLIENT TEST</h1>

<?php

require ('CuzkWsdpSoapClient.php');

$username = "WSTEST";
$password = "WSHESLO";

$wsdl = "https://wsdptrial.cuzk.cz/trial/dokumentace/ws29/wsdp/vyhledat_v29.wsdl";
$ws = 'vyhledat';
$args = array(
    'katastrUzemiKod' => 691232,
    'kmenoveCislo' => 68
);

try {
    $client = new CuzkWsdpSoapClient($wsdl, array(
        'soap_version' => SOAP_1_1,
        'trace' => true,
    ));
    $client->__setWsdp($username, $password, $ws);
    $res = $client->__soapCall('najdiParcelu', $args);

    echo json_encode($res);
} catch (SoapFault $e) {
    echo "Error: {$e}";
}

echo "<hr>Last Request";
echo "<pre style='width: 100%; background-color: #000000; color: #ffffff; overflow: auto;'>", htmlspecialchars($client->__getLastRequest()), "</pre>";
echo "<hr>Last Request Header";
echo "<pre style='width: 100%; background-color: #000000; color: #ffffff; overflow: auto;'>", htmlspecialchars($client->__getLastRequestHeaders()), "</pre>";
echo "<hr>Last Response";
echo "<pre style='width: 100%; background-color: #000000; color: #ffffff; overflow: auto;'>", htmlspecialchars($client->__getLastResponse()), "</pre>";
echo "<hr>Last Response Header";
echo "<pre style='width: 100%; background-color: #000000; color: #ffffff; overflow: auto;'>", htmlspecialchars($client->__getLastResponseHeaders()), "</pre>";

?>