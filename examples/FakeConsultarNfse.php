<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\NFSeSig\Tools;
use NFePHP\NFSeSig\Common\Soap\SoapFake;
use NFePHP\NFSeSig\Common\FakePretty;

try {
    
    $config = [
        'cnpj' => '99999999000191',
        'im' => '1733160024',
        'aliquota_simples' => '',
        'senha' => '1234', //senha de acesso ao sistema
        'crc' => '10292929', //identificação do contador
        'crc_uf' => 'SP', //estado de registro do contador Opcional
        'cmun' => '3529005', //ira determinar as urls e outros dados Opcional
        'razao' => 'Empresa Test Ltda',
        'tpamb' => 2 //1-producao, 2-homologacao
    ];

    $configJson = json_encode($config);

    $soap = new SoapFake();
    $soap->disableCertValidation(true);
    
    $tools = new Tools($configJson);
    $tools->loadSoapClass($soap);

    $nota = '1234';

    $response = $tools->consultarNfse($nota);
    
    echo FakePretty::prettyPrint($response, '');
 
} catch (\Exception $e) {
    echo $e->getMessage();
}
