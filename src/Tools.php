<?php

namespace NFePHP\NFSeSig;

/**
 * Class for comunications with NFSe webserver in Nacional Standard
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

use NFePHP\NFSeSig\Common\Tools as BaseTools;
use NFePHP\NFSeSig\RpsInterface;
use NFePHP\Common\Certificate;
use NFePHP\Common\Validator;

class Tools extends BaseTools
{
    
    public function __construct($config, Certificate $cert = null)
    {
        parent::__construct($config, $cert);
    }
    
    /**
     * Cancela NFSe
     * @param string $nota
     * @param string $motivo
     * @param string $email
     * @return string
     */
    public function cancelarNfse($nota, $motivo, $email = null)
    {
        $operation = 'CancelarNota';
        
        $content = "<DadosCancelaNota xsi:type=\"urn:tcDadosCancelaNota\">"
            . "<ccm xsi:type=\"xsd:int\">{$this->config->im}</ccm>"
            . "<cnpj xsi:type=\"xsd:string\">{$this->config->cnpj}</cnpj>"
            . "<senha xsi:type=\"xsd:string\">{$this->config->senha}</senha>"
            . "<nota xsi:type=\"xsd:int\">$nota</nota>"
            . "<motivo xsi:type=\"xsd:string\">$motivo</motivo>";
        
        if (!empty($email)) {
            $content .= "<email xsi:type=\"xsd:string\">?</email>";
        }
        
        $content .= "</DadosCancelaNota>";

        return $this->send($content, $operation);
    }
    
    /**
     * Consulat Nota de prestador
     * @param string $nota
     * @param string $serie
     * @param float $valor
     * @param string $codigo
     * @param string $im
     * @param string $cnpj
     * @return string
     */
    public function consultarNotas($nota, $serie, $valor, $codigo, $im, $cnpj)
    {
        $operation = "ConsultarNotaValida";
        
        $content = "<DadosConsultaNota xsi:type=\"urn:tcDadosConsultaNota\">"
            . "<nota xsi:type=\"xsd:int\">$nota</nota>"
            . "<serie xsi:type=\"xsd:string\">$serie</serie>"
            . "<valor xsi:type=\"xsd:string\">"
            . number_format($valor, 2, ',', '')
            . "</valor>"
            . "<prestador_ccm xsi:type=\"xsd:int\">$im</prestador_ccm>"
            . "<prestador_cnpj xsi:type=\"xsd:string\">$cnpj</prestador_cnpj>"
            . "<autenticidade xsi:type=\"xsd:string\">$codigo</autenticidade>"
            . "</DadosConsultaNota>";
        
        return $this->send($content, $operation);
    }
    
    /**
     * Consulta Nota emitida
     * @param string $nota
     * @return string
     */
    public function consultarNFse($nota)
    {
        $operation = "ConsultarNotaPrestador";
        
        $content = "<DadosPrestador xsi:type=\"urn:tcDadosPrestador\">"
            . "<ccm xsi:type=\"xsd:int\">{$this->config->im}</ccm>"
            . "<cnpj xsi:type=\"xsd:string\">{$this->config->cnpj}</cnpj>"
            . "<senha xsi:type=\"xsd:string\">{$this->config->senha}</senha>"
            . "<crc xsi:type=\"xsd:int\">{$this->config->crc}</crc>"
            . "<crc_estado xsi:type=\"xsd:string\">{$this->config->crc_uf}</crc_estado>"
            . "<aliquota_simples xsi:type=\"xsd:string\">{$this->config->aliquota_simples}</aliquota_simples>"
            . "</DadosPrestador>"
            . "<Nota xsi:type=\"xsd:int\">$nota</Nota>";
        
        return $this->send($content, $operation);
            
    }
    
    /**
     * Gera NFSe
     * @param RpsInterface $rps
     * @return string
     */
    public function gerarNfse(RpsInterface $rps)
    {
        $operation = "GerarNota";
        $content = $this->putPrestadorInRps($rps);
        
        return $this->send($content, $operation);
    }
}
