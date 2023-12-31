<?php

namespace Util;

use JsonException as JsonExceptionAlias;
use Util\ConstantesGenericasUtil as Constantes;
use Util\FuncoesUtil as Util;

class JsonUtil 
{    
    /**
     * processarArrayParaRetornar
     *
     * @param  mixed $retorno
     * @return mixed
     */
    public function processarArrayParaRetornar($retorno)
    {
        $dados = [];
        $dados[Constantes::TIPO] = Constantes::TIPO_ERRO;

        if ( is_array($retorno) && count($retorno) > 0 || strlen($retorno) > 10) {
            $dados[Constantes::TIPO] = Constantes::TIPO_SUCESSO;
            $dados[Constantes::RESPOSTA] = $retorno;
        }

        $this->retornarJson($dados);
    }

    private function retornarJson($json)
    {
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE');
        echo json_encode($json);
        exit;

    }

    public static function tratarCorpoRequisicaoJson()
    {
        try {
            $postJson = json_decode(file_get_contents('php://input'), true);
        } catch (JsonExceptionAlias $exception) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_JSON_VAZIO);
        }
    
        if(is_array($postJson) && count($postJson)){
            return $postJson;
        }
    }


}