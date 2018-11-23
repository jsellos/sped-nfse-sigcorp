<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$version = '1_00';

$jsonSchema = '{
    "title": "RPS",
    "type": "object",
    "properties": {
        "id_sis_legado": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^.{1,15}$"
        },
        "servico": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{1,15}$"
        },
        "situacao": {
            "required": true,
            "type": "string",
            "pattern": "^(tp|tt|is|im|nt)$"
        },
        "valor": {
            "required": true,
            "type": "number"
        },
        "base": {
            "required": true,
            "type": "number"
        },
        "descricaonf": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^.{1,1000}$"
        },
        "rps_num": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^[0-9]{1,15}$"
        },
        "rps_serie": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^.{1,10}$"
        },
        "rps_dia": {
            "required": false,
            "type": ["integer","null"],
            "minumum": 1,
            "maximum": 31
        },
        "rps_mes": {
            "required": false,
            "type": ["integer","null"],
            "minumum": 1,
            "maximum": 12
        },
        "rps_mes": {
            "required": false,
            "type": ["integer","null"],
            "minumum": 2017,
            "maximum": 2200
        },
        "outro_municipio": {
            "required": false,
            "type": ["integer","null"],
            "minumum": 0,
            "maximum": 1
        },
        "cod_outro_municipio": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^[0-9]{7}$"
        },
        "retencao_iss": {
            "required": false,
            "type": ["number","null"]
        },
        "pis": {
            "required": false,
            "type": ["number","null"]
        },
        "cofins": {
            "required": false,
            "type": ["number","null"]
        },
        "inss": {
            "required": false,
            "type": ["number","null"]
        },
        "irrf": {
            "required": false,
            "type": ["number","null"]
        },
        "csll": {
            "required": false,
            "type": ["number","null"]
        },
        "tomador": {
            "required": true,
            "type": "object",
            "properties": {
                "tipo": {
                    "required": true,
                    "type": "integer",
                    "minumum": 1,
                    "maximum": 6
                },
                "cnpj": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^[0-9]{11,14}$"
                },
                "razao": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{1,200}$"
                    
                },
                "fantasia": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{1,200}$"
                },
                "email": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{1,200}$"
                },
                "ie": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^[0-9]{6,20}$"
                },    
                "im": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^[0-9]{5,20}$"
                },
                "endereco": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{1,200}$"
                },
                "numero": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{1,200}$"
                },
                "complemento": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{1,200}$"
                },
                "bairro": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^.{1,200}$"
                },
                "cep": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^[0-9]{8}$"
                },
                "cod_cidade": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^[0-9]{7}$"
                },
                "fone": {
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^[0-9]{6,9}$"
                },
                "ramal": {
                    "required": false,
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^[0-9]{1,6}$"
                },
                "fax": {
                    "required": false,
                    "required": false,
                    "type": ["string","null"],
                    "pattern": "^[0-9]{6,9}$"
                }
            }
        }
    }
}';


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

// Schema must be decoded before it can be used for validation
$jsonSchemaObject = json_decode($jsonSchema);
if (empty($jsonSchemaObject)) {
    echo "<h2>Erro de digitação no schema ! Revise</h2>";
    echo "<pre>";
    print_r($jsonSchema);
    echo "</pre>";
    die();
}
// The SchemaStorage can resolve references, loading additional schemas from file as needed, etc.
$schemaStorage = new SchemaStorage();
// This does two things:
// 1) Mutates $jsonSchemaObject to normalize the references (to file://mySchema#/definitions/integerData, etc)
// 2) Tells $schemaStorage that references to file://mySchema... should be resolved by looking in $jsonSchemaObject
$schemaStorage->addSchema('file://mySchema', $jsonSchemaObject);
// Provide $schemaStorage to the Validator so that references can be resolved during validation
$jsonValidator = new Validator(new Factory($schemaStorage));
// Do validation (use isValid() and getErrors() to check the result)
$jsonValidator->validate(
    $std,
    $jsonSchemaObject,
    Constraint::CHECK_MODE_COERCE_TYPES  //tenta converter o dado no tipo indicado no schema
);

if ($jsonValidator->isValid()) {
    echo "The supplied JSON validates against the schema.<br/>";
} else {
    echo "Dados não validados. Violações:<br/>";
    foreach ($jsonValidator->getErrors() as $error) {
        echo sprintf("[%s] %s<br/>", $error['property'], $error['message']);
    }
    die;
}
//salva se sucesso
file_put_contents("../storage/jsonSchemes/rps.schema", $jsonSchema);