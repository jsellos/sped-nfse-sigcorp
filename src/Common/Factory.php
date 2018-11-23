<?php

namespace NFePHP\NFSeSig\Common;

/**
 * Class for RPS XML convertion
 *
 * @category  NFePHP
 * @package   NFePHP\NFSeSig
 * @copyright NFePHP Copyright (c) 2008-2018
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse-sigcorp for the canonical source repository
 */

use stdClass;
use NFePHP\Common\DOMImproved as Dom;
use DOMNode;
use DOMElement;

class Factory
{
    /**
     * @var stdClass
     */
    protected $std;
    /**
     * @var Dom
     */
    protected $dom;
    /**
     * @var DOMNode
     */
    protected $rps;

    /**
     * Constructor
     * @param stdClass $std
     */
    public function __construct(stdClass $std)
    {
        $this->std = $std;
        
        $this->dom = new Dom('1.0', 'UTF-8');
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = true;
        $this->rps = $this->dom->createElement('DescricaoRps');
        $this->rps->setAttribute('xsi:type', 'urn:tcDescricaoRps');
    }
    
    /**
     * Builder, converts sdtClass Rps in XML Rps
     * NOTE: without Prestador Tag
     * @return string RPS in XML string format
     */
    public function render()
    {
        $this->addElement(
            $this->rps,
            'id_sis_legado',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($this->std->id_sis_legado) ? $this->std->id_sis_legado : null
        );
        $this->addElement(
            $this->rps,
            'servico',
            ['form' => 'string', 'content' => 'xsd:int'],
            isset($this->std->servico) ? $this->std->servico : null
        );
        $this->addElement(
            $this->rps,
            'situacao',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($this->std->situacao) ? $this->std->situacao : null
        );
        $this->addElement(
            $this->rps,
            'valor',
            ['form' => 'number', 'content' => 'xsd:string'],
            isset($this->std->valor) ? $this->std->valor : null
        );
        $this->addElement(
            $this->rps,
            'base',
            ['form' => 'number', 'content' => 'xsd:string'],
            isset($this->std->base) ? $this->std->base : null
        );
        $this->addElement(
            $this->rps,
            'descricaoNF',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($this->std->descricaonf) ? $this->std->descricaonf : null
        );
        $tom = $this->std->tomador;
        $this->addElement(
            $this->rps,
            'tomador_tipo',
            ['form' => 'string', 'content' => 'xsd:int'],
            isset($tom->tipo) ? $tom->tipo : ''
        );
        $this->addElement(
            $this->rps,
            'tomador_cnpj',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($tom->cnpj) ? $tom->cnpj : ''
        );
        $this->addElement(
            $this->rps,
            'tomador_email',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($tom->email) ? $tom->email : null
        );
        $this->addElement(
            $this->rps,
            'tomador_razao',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($tom->razao) ? $tom->razao : ''
        );
        $this->addElement(
            $this->rps,
            'tomador_fantasia',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($tom->fantasia) ? $tom->fantasia : null
        );
        $this->addElement(
            $this->rps,
            'tomador_endereco',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($tom->endereco) ? $tom->endereco : null
        );
        $this->addElement(
            $this->rps,
            'tomador_numero',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($tom->numero) ? $tom->numero : null
        );
        $this->addElement(
            $this->rps,
            'tomador_complemento',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($tom->complemento) ? $tom->complemento : null
        );
        $this->addElement(
            $this->rps,
            'tomador_bairro',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($tom->bairro) ? $tom->bairro : null
        );
        $this->addElement(
            $this->rps,
            'tomador_CEP',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($tom->cep) ? $tom->cep : null
        );
        $this->addElement(
            $this->rps,
            'tomador_cod_cidade',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($tom->cod_cidade) ? $tom->cod_cidade : null
        );
        $this->addElement(
            $this->rps,
            'tomador_fone',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($tom->fone) ? $tom->fone : null
        );
        $this->addElement(
            $this->rps,
            'tomador_ramal',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($tom->ramal) ? $tom->ramal : null
        );
        $this->addElement(
            $this->rps,
            'tomador_fax',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($tom->fax) ? $tom->fax : null
        );
        $this->addElement(
            $this->rps,
            'rps_num',
            ['form' => 'string', 'content' => 'xsd:int'],
            isset($this->std->rps_num) ? $this->std->rps_num : null
        );
        $this->addElement(
            $this->rps,
            'rps_serie',
            ['form' => 'string', 'content' => 'xsd:string'],
            isset($this->std->rps_serie) ? $this->std->rps_serie : null
        );
        $this->addElement(
            $this->rps,
            'rps_dia',
            ['form' => 'string', 'content' => 'xsd:int'],
            isset($this->std->rps_dia) ? $this->std->rps_dia : null
        );
        $this->addElement(
            $this->rps,
            'rps_mes',
            ['form' => 'string', 'content' => 'xsd:int'],
            isset($this->std->rps_mes) ? $this->std->rps_mes : null
        );
        $this->addElement(
            $this->rps,
            'rps_ano',
            ['form' => 'string', 'content' => 'xsd:int'],
            isset($this->std->rps_ano) ? $this->std->rps_ano : null
        );
        $this->addElement(
            $this->rps,
            'outro_municipio',
            ['form' => 'string', 'content' => 'xsd:int'],
            isset($this->std->outro_municipio) ? $this->std->outro_municipio : null
        );
        $this->addElement(
            $this->rps,
            'cod_outro_municipio',
            ['form' => 'string', 'content' => 'xsd:int'],
            isset($this->std->cod_outro_municipio) ? $this->std->cod_outro_municipio : null
        );
        $this->addElement(
            $this->rps,
            'retencao_iss',
            ['form' => 'number', 'content' => 'xsd:string'],
            isset($this->std->retencao_iss) ? $this->std->retencao_iss : null
        );
        $this->addElement(
            $this->rps,
            'pis',
            ['form' => 'number', 'content' => 'xsd:string'],
            isset($this->std->pis) ? $this->std->pis : null
        );
        $this->addElement(
            $this->rps,
            'cofins',
            ['form' => 'number', 'content' => 'xsd:string'],
            isset($this->std->cofins) ? $this->std->cofins : null
        );
        $this->addElement(
            $this->rps,
            'inss',
            ['form' => 'number', 'content' => 'xsd:string'],
            isset($this->std->inss) ? $this->std->inss : null
        );
        $this->addElement(
            $this->rps,
            'irrf',
            ['form' => 'number', 'content' => 'xsd:string'],
            isset($this->std->irrf) ? $this->std->irrf : null
        );
        $this->addElement(
            $this->rps,
            'csll',
            ['form' => 'number', 'content' => 'xsd:string'],
            isset($this->std->csll) ? $this->std->csll : null
        );
        
        $this->dom->appendChild($this->rps);
        return $this->dom->saveXML();
    }
    
    protected function addElement(&$node, $name, $attribute, $value)
    {
        if (isset($value)) {
            if ($attribute['form'] == 'number') {
                $value = number_format($value, 2, ',', '');
            }
            $newnode = $this->dom->createElement(
                $name,
                $value
            );
            $newnode->setAttribute('xsi:type', $attribute['content']);
            $node->appendChild($newnode);
        }
    }
}
