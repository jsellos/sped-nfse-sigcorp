<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\NFSeSig\Rps;

$std = new \stdClass();
$std->id_sis_legado = '1234'; //Opcional Código da nota no sistema legado do contribuinte
$std->servico = '1176'; //Obrigatório Código do serviço utilizado na emissão da nota fiscal da lei 116/03.
$std->situacao = 'tp'; //Obrigatório Situação da nota fiscal eletrônica: 
                    //tp – Tributada no prestador;
                    //tt – Tributada no tomador;
                    //is – Isenta;
                    //im – Imune;
                    //nt – Não tributada.
$std->valor = 1543.21; //Obrigatório Valor da nota fiscal. ex. 1500,00 com virgula
$std->base = 1543.21; //Obrigatório Valor da base de calculo.
$std->descricaonf = 'Descrição do Serviço Prestado';//Opcional. Descrição do Serviço Prestado
$std->rps_num = '12345';//Opcional
$std->rps_serie = 'A1';//Opcional
$std->rps_dia = 12;//Opcional
$std->rps_mes = 12;//Opcional
$std->rps_ano = 2017;//Opcional
$std->outro_municipio = 1;//Opcional
$std->cod_outro_municipio = '3529005';//Opcional
$std->retencao_iss = 25.89;//Opcional
$std->pis = 1.58;//Opcional
$std->cofins = 12.60;//Opcional
$std->inss = 25.67;//Opcional
$std->irrf = 2.85;//Opcional
$std->csll = 1.22;//Opcional


$std->tomador = new \stdClass();  
$std->tomador->tipo = 3;//Obrigatório 
                        //1 – PFNI;
                        //2 – Pessoa Física;
                        //3 – Jurídica do Município;
                        //4 – Jurídica de Fora;
                        //5 – Jurídica de Fora do País (exportação);
                        //6 – Produtor Rural/Politico
$std->tomador->cnpj = '12345678901';//Obrigatório
$std->tomador->email = 'fulano@mail.com';//Opcional
$std->tomador->ie = '12345678901';//Opcional
$std->tomador->im = '1234567';//Opcional
$std->tomador->razao = 'Fulano de Tal ME';//Obrigatório
$std->tomador->fantasia = 'Fulano';//Opcional
$std->tomador->endereco = 'Rua Velha';//Opcional
$std->tomador->numero = '123A';//Opcional
$std->tomador->complemento = 'Sobreloja';//Opcional
$std->tomador->bairro = 'Centro';//Opcional
$std->tomador->cep = '45795111';//Opcional
$std->tomador->cod_cidade = '3530706';//Opcional
$std->tomador->fone = '999999999';//Opcional
$std->tomador->ramal = '454';//Opcional
$std->tomador->fax = '888888888';//Opcional

$rps = new Rps($std);

//header("Content-type: text/xml");
//echo $rps->render();


echo "<pre>";
echo str_replace(
            ['<', '>'],
            ['&lt;','&gt;'],
            str_replace(
                '<?xml version="1.0"?>',
                '<?xml version="1.0" encoding="UTF-8"?>',
                $rps->render()
            )
        );
echo "</pre>";


