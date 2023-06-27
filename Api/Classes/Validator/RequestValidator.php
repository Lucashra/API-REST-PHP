<?php

namespace Validator;

use Util\ConstantesGenericasUtil;
use Util\JsonUtil;

class RequestValidator
{
    private $request;  
    private $dadosRequest = [];

    const GET = "GET";
    const DELETE = "DELETE";

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function processarRequest()
    {
        $this->request['metodo'] = 'POST';

        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;
        if(in_array($this->request['metodo'], ConstantesGenericasUtil::TIPO_REQUEST, true)) {
           $retorno = $this->direcionarRequest();
        }
        var_dump($retorno);exit;

        return $retorno;
    }

    private function direcionarRequest()
    {
        if($this->request['metodo'] !== self::GET && $this->request['metodo'] !== self::DELETE) {
            $this->dadosRequest = JsonUtil::tratarCorpoRequisição();
        }
    }
}