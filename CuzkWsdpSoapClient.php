<?php

/**
 * Tato třída slouží jako SOAP client wrapper pro aplikace,
 * které přistupují k údajům katastru nemovitostí ČR
 * za pomocí webové služby dálkového přístupu (WSDP verze 2.9)
 *
 * @author Pham The Anh - https://github.com/DEK-Pham-The-Anh
 * @see https://www.cuzk.cz/Katastr-nemovitosti/Poskytovani-udaju-z-KN/Dalkovy-pristup/Webove-sluzby-dalkoveho-pristupu.aspx
 */

class CuzkWsdpSoapClient extends SoapClient
{
    /**
     * Přihlašovací jméno
     * @var string
     */
    private $wsdp_username;

    /**
     * Přihlašovací heslo
     * @var string
     */
    private $wsdp_password;

    /**
     * Název funkce (operace)
     * Více info viz dokumentace s názvem "WSDP Popis webových služeb pro uživatele"
     * 
     * Příklad: "generujPrehledVlastnictviSNemovitostmi"
     * @var string
     */
    private $wsdp_func;

    /**
     * Typ webové služby
     * Může nabývat hodnot: "ciselnik", "informace", "sestavy", "ucet", "vyhledat"
     * @var string
     */
    private $wsdp_service_type;

    /**
     * Argumenty v těle requestu
     * 
     * Příklad:
     * array(
     *    "katastrUzemiKod" => 691232,
     *    "kmenoveCislo" => 68
     * )
     * @var array
     */
    private $wsdp_args;

    /**
	 * Nastaví WSDP - jméno, heslo, typ webové služby
	 * 
	 * @param string $username
	 * @param string $password
     * @param string $ws
	 */
	public function __setWsdp($username, $password, $ws)
	{
		$this->wsdp_username = $username;
		$this->wsdp_password = $password;
        $this->wsdp_service_type = $ws;
	}

    /**
     * Přepisuje původní metodu
     */
    public function __soapCall(string $name, array $args, ?array $options = null, $inputHeaders = null, &$outputHeaders = null)
    {
        $this->wsdp_func = $name;
        $this->wsdp_args = $args;
        $this->__setSoapHeaders($this->generateWsdpHeaders());
        return parent::__soapCall($this->wsdp_func, $this->generateWsdpArgs());
    }

    /**
     * Generuje argumenty v těle requestu
     * 
     * @return array
     */
    private function generateWsdpArgs()
    {
        $wsdp_func = ucfirst($this->wsdp_func) . 'Request';

        $xml = '<soapenv:Envelope
            xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
            xmlns:typ="http://katastr.cuzk.cz/' . $this->wsdp_service_type . '/types/"
            xmlns:v2="http://katastr.cuzk.cz/' . $this->wsdp_service_type . '/types/v2.9">
            <soapenv:Body>
                <v2:' . $wsdp_func . '>';
        foreach ($this->wsdp_args as $argKey => $argVal) {
            $xml .= '<v2:' . $argKey . '>' . $argVal . '</v2:' . $argKey . '>';
        }
        $xml .= '</v2:' . $wsdp_func . '>
            </soapenv:Body>
        </soapenv:Envelope>';

        return array(new SoapVar($xml, XSD_ANYXML));
    }

    /**
     * Generuje headers requestu
     * 
     * @return SoapHeader
     */
    private function generateWsdpHeaders() {
        $xml = '<wsse:Security SOAP-ENV:mustUnderstand="1"
            xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" 
            xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
            <wsse:UsernameToken>
                <wsse:Username>' . $this->wsdp_username . '</wsse:Username>
                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $this->wsdp_password . '</wsse:Password>
            </wsse:UsernameToken>
        </wsse:Security>';

        return new SoapHeader(
            "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd",
            "Security",
            new SoapVar($xml, XSD_ANYXML),
            true
        );
    }
}

?>