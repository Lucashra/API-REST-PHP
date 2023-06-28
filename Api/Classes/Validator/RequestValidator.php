<?php

namespace Validator;

use Repository\TokensAutorizadosRepository;
use Util\ConstantesGenericasUtil;
use Util\JsonUtil;

class RequestValidator
{
    private $request;  
    private $dadosRequest = [];
    private object $TokenAutorizadosRepository;

    const GET = "GET";
    const DELETE = "DELETE";
     const USUARIOS = 'USUARIOS';

    public function __construct($request)
    {
        $this->request = $request;
        $this->TokenAutorizadosRepository = new TokensAutorizadosRepository();
    }

    /**
     * @return array|mixed|string|null
     */
    public function processarRequest()
    {
        $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);
        if (in_array($this->request['metodo'], ConstantesGenericasUtil::TIPO_REQUEST, true)) {
            $retorno = $this->direcionarRequest();
        }
        return $retorno;
    } 

    private function direcionarRequest()
    {
        if ($this->request['metodo'] !== self::GET && $this->request['metodo'] !== self::DELETE) {
                $this->dadosRequest = JsonUtil::tratarCorpoRequisicaoJson();
        }
    }
}