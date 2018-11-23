<?php

namespace NFePHP\NFSeSig\Common;

/**
 * Auxiar Tools Class for comunications with NFSe webserver in Nacional Standard
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

use NFePHP\Common\Certificate;
use NFePHP\NFSeSig\RpsInterface;
use NFePHP\Common\DOMImproved as Dom;
use NFePHP\NFSeSig\Common\Signer;
use NFePHP\NFSeSig\Common\Soap\SoapInterface;
use NFePHP\NFSeSig\Common\Soap\SoapCurl;

class Tools
{
    public $lastRequest;
    
    protected $config;
    protected $prestador;
    protected $certificate;
    protected $wsobj;
    protected $soap;
    protected $environment;
    protected $storage;


    /**
     * Constructor
     * @param string $config
     * @param Certificate $cert
     */
    public function __construct($config, Certificate $cert = null)
    {
        $this->config = json_decode($config);
        $this->certificate = $cert;
        $this->storage = realpath(
            __DIR__ . '/../../storage/'
        );
        $urls = json_decode(file_get_contents($this->storage . '/municipios_sigcorp.json'), true);
        if (empty($urls[$this->config->cmun])) {
            throw new \Exception("O municipio [{$this->config->cmun}] não consta da lista dos atendidos.");
        }
        $this->wsobj = json_decode(json_encode($urls[$this->config->cmun]));
        $this->environment = 'homologacao';
        if ($this->config->tpamb === 1) {
            $this->environment = 'producao';
        }
    }
    
    /**
     * SOAP communication dependency injection
     * @param SoapInterface $soap
     */
    public function loadSoapClass(SoapInterface $soap)
    {
        $this->soap = $soap;
    }
    
    
    /**
     * Send message to webservice
     * @param string $message
     * @param string $operation
     * @return string XML response from webservice
     */
    public function send($message, $operation)
    {
        $action = "urn:sigiss_ws#$operation";
        
        $url = $this->wsobj->homologacao;
        if ($this->environment === 'producao') {
            $url = $this->wsobj->producao;
        }
        $request = $this->createSoapRequest($message, $operation);
        $this->lastRequest = $request;
        
        if (empty($this->soap)) {
            $this->soap = new SoapCurl($this->certificate);
        }
        $msgSize = strlen($request);
        $parameters = [
            "Content-Type: text/xml;charset=UTF-8",
            "SOAPAction: \"$action\"",
            "Content-length: $msgSize"
        ];
        $response = (string) $this->soap->send(
            $operation,
            $url,
            $action,
            $request,
            $parameters
        );
        return $response;
    }

    /**
     * Build SOAP request
     * @param string $message
     * @param string $operation
     * @return string XML SOAP request
     */
    protected function createSoapRequest($message, $operation)
    {
        
        $env = "<soapenv:Envelope "
            . "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" "
            . "xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" "
            . "xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" "
            . "xmlns:urn=\"urn:sigiss_ws\">"
            . "<soapenv:Header/>"
            . "<soapenv:Body>"
            . "<urn:$operation "
            . "soapenv:encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\">"
            . $message
            . "</urn:$operation>"
            . "</soapenv:Body>"
            . "</soapenv:Envelope>";
        
        return $env;
    }

    /**
     * Create tag Prestador and insert into RPS xml
     * @param RpsInterface $rps
     * @return string RPS XML (not signed)
     */
    protected function putPrestadorInRps(RpsInterface $rps)
    {
        libxml_use_internal_errors(true);
        $dom = new Dom('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($rps->render());
        
        $node = $dom->documentElement;
        $refNode = $dom->getElementsByTagName('servico')->item(0);
       
        
        $ccm = $dom->createElement('ccm', $this->config->im);
        $ccm->setAttribute('xsi:type', 'xsd:string');
        $node->insertBefore($ccm, $refNode);
        
        $cnpj = $dom->createElement('cnpj', $this->config->cnpj);
        $cnpj->setAttribute('xsi:type', 'xsd:string');
        $node->insertBefore($cnpj, $refNode);
        
        $senha = $dom->createElement('senha', $this->config->senha);
        $senha->setAttribute('xsi:type', 'xsd:string');
        $node->insertBefore($senha, $refNode);
        
        $crc = $dom->createElement('crc', $this->config->crc);
        $crc->setAttribute('xsi:type', 'xsd:int');
        $node->insertBefore($crc, $refNode);
        
        $crc_uf = $dom->createElement('crc_estado', $this->config->crc_uf);
        $ccm->setAttribute('xsi:type', 'xsd:string');
        $node->insertBefore($crc_uf, $refNode);
        
        if (!empty($this->config->aliquota_simples)) {
            $als = $dom->createElement(
                'aliquota_simples',
                number_format($this->config->aliquota_simples, 2, ',', '')
            );
            $als->setAttribute('xsi:type', 'xsd:string');
            $node->insertBefore($als, $refNode);
        }
        return $dom->saveXML($dom->documentElement);
    }
}
