<?php

namespace Service;

use Repository\UsuariosRepository;
use Util\FuncoesUtil as Util;
use Util\ConstantesGenericasUtil as Constantes;

class UsuariosService
{
    public const TABELA = 'usuarios';
    public const RECURSOS_GET = ['listar'];

    private array $dados;

    private object $UsuariosRepository;

    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->UsuariosRepository = new UsuariosRepository();
    }

    public function validarGet()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if(in_array($recurso, self::RECURSOS_GET, true)) {
            $retorno = $this->dados['id'] > 0 ? $this->getOneByKey() : $this->$recurso();
        } else {
            throw new \InvalidArgumentException(Constantes::MSG_ERRO_RECURSO_INEXISTENTE);
        }
        
        if($retorno === null) {
            throw new \InvalidArgumentException(Constantes::MSG_ERRO_GENERICO);
        }
    }


    private function getOneByKey()
    {

    }

    private function listar()
    {
        return $this->UsuariosRepository->getMySQL()->getAll(self::TABELA);
    }
}

