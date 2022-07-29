# CUZK WSDP

SOAP client wrapper pro aplikace, které přistupují k údajům katastru nemovitostí ČR přes WSDP 2.9


## Příklad použití

```php
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

    echo print_r($res);
} catch (SoapFault $e) {
    echo "Error: {$e}";
}
```