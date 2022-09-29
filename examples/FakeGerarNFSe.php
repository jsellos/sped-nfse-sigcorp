<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\NFSeSig\Tools;
use NFePHP\NFSeSig\Rps;
use NFePHP\NFSeSig\Common\Soap\SoapFake;
use NFePHP\NFSeSig\Common\FakePretty;

try {

    $config = [
        'cnpj'             => '33392601000146',
        'im'               => '770890', //inscrição municipal
        'aliquota_simples' => '',
        'senha'            => '333926', //senha de acesso ao sistema
        'crc'              => '007550', //identificação do contador
        'crc_uf'           => 'MG', //estado de registro do contador Opcional
        'cmun'             => '3127701', // cód IBGE do município Opcional
        'razao'            => 'Sellos Tech Ltda',
        'tpamb'            => 2 //1-producao, 2-homologacao
    ];

    $configJson = json_encode($config);

    $soap = new SoapFake();
    $soap->disableCertValidation(true);

    $tools = new Tools($configJson);
    $tools->loadSoapClass($soap);

    $arps = [];

    $std = new \stdClass();
//    $std->id_sis_legado = '1234'; //Opcional Código da nota no sistema legado do contribuinte
    $std->servico = '101'; //Obrigatório Código do serviço utilizado na emissão da nota fiscal da lei 116/03.
    $std->situacao = 'tp'; //Obrigatório Situação da nota fiscal eletrônica:
    //tp – Tributada no prestador;
    //tt – Tributada no tomador;
    //is – Isenta;
    //im – Imune;
    //nt – Não tributada.
    $std->valor = 12345.67; //Obrigatório Valor da nota fiscal. ex. 1500,00 com virgula
    $std->base = 12345.67; //Obrigatório Valor da base de calculo.
    $std->descricaonf = 'Descrição do Serviço Prestado'; //Opcional. Descrição do Serviço Prestado
//    $std->rps_num = '12345'; //Opcional
//    $std->rps_serie = 'A1'; //Opcional
//    $std->rps_dia = 12; //Opcional
//    $std->rps_mes = 12; //Opcional
//    $std->rps_ano = 2017; //Opcional
//    $std->outro_municipio = 1; //Opcional
//    $std->cod_outro_municipio = '3529005'; //Opcional
//    $std->retencao_iss = 25.89; //Opcional
//    $std->pis = 1.58; //Opcional
//    $std->cofins = 12.60; //Opcional
//    $std->inss = 25.67; //Opcional
//    $std->irrf = 2.85; //Opcional
//    $std->csll = 1.22; //Opcional


    $std->tomador = new \stdClass();
    $std->tomador->tipo = 3; //Obrigatório
    //1 – PFNI;
    //2 – Pessoa Física;
    //3 – Jurídica do Município;
    //4 – Jurídica de Fora;
    //5 – Jurídica de Fora do País (exportação);
    //6 – Produtor Rural/Politico
    $std->tomador->cnpj = '04893601000121'; //Obrigatório
    $std->tomador->email = 'comunagv@sellos.com.br'; //Opcional
//    $std->tomador->ie = '12345678901'; //Opcional
    $std->tomador->im = '260265'; //Opcional
    $std->tomador->razao = 'Comunidade da Graça em Governador Valadares'; //Obrigatório
    $std->tomador->fantasia = 'Comuna GV'; //Opcional
    $std->tomador->endereco = 'Rua Euryto Orlando Bonesi'; //Opcional
    $std->tomador->numero = '25'; //Opcional
//    $std->tomador->complemento = 'Sobreloja'; //Opcional
    $std->tomador->bairro = 'Morada do Acampamento'; //Opcional
    $std->tomador->cep = '35020450'; //Opcional
    $std->tomador->cod_cidade = '3127701'; //Opcional
    $std->tomador->fone = '32717755'; //Opcional
//    $std->tomador->ramal = '454'; //Opcional
//    $std->tomador->fax = '888888888'; //Opcional

    $rps = new Rps($std);

    $response = $tools->gerarNfse($rps);

    echo FakePretty::prettyPrint($response, '');
} catch (\Exception $e) {
    echo $e->getMessage();
}
