<?php

namespace Service;

use Repository\UsuariosRepository;
use Util\FuncoesUtil as Util;
use Util\ConstantesGenericasUtil as Constantes;

class UsuariosService
{
    public const TABELA = 'usuarios';
    public const RECURSOS_GET = ['listar'];
    public const RECURSOS_DELETE = ['deletar'];
    public const RECURSOS_POST = ['cadastrar'];
    public const RECURSOS_PUT = ['atualizar'];

    /**
     * @var array
     */
    private array $dados;

    /**
     * @var object|UsuariosRepository
     */
    private object $UsuariosRepository;

    /**
     * @var array
     */
    private array $dadosCorpoRequest = [];

    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->UsuariosRepository = new UsuariosRepository();
    }
    
    /**
     * validarGet
     *
     * @return mixed
     */
    public function validarGet()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if(in_array($recurso, self::RECURSOS_GET, true)) {
            $retorno = $this->dados['id'] > 0 ? $this->getOneByKey() : $this->$recurso();
        } else {
            throw new \InvalidArgumentException(Constantes::MSG_ERRO_RECURSO_INEXISTENTE);
        }
        
        $this->validarRetornoRequest($retorno);

        return $retorno;
    }
    
    /**
     * validarDelete
     *
     * @return mixed
     */
    public function validarDelete()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if(in_array($recurso, self::RECURSOS_DELETE, true)) {
            $retorno = $this->validarIdObrigatório($recurso);
        } else {
            throw new \InvalidArgumentException(Constantes::MSG_ERRO_RECURSO_INEXISTENTE);
        }
        
        $this->validarRetornoRequest($retorno);

        return $retorno;
    }
    
    /**
     * validarPut
     *
     * @return mixed
     */
    public function validarPut()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if(in_array($recurso, self::RECURSOS_PUT, true)) {
           $retorno = $this->validarIdObrigatório($recurso);
        } else {
            throw new \InvalidArgumentException(Constantes::MSG_ERRO_RECURSO_INEXISTENTE);
        }
        
        $this->validarRetornoRequest($retorno);

        return $retorno;
    }
    
    /**
     * validarPost
     *
     * @return voimixedd
     */
    public function validarPost()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        if(in_array($recurso, self::RECURSOS_POST, true)) {
            $retorno = $this->$recurso();
        } else {
            throw new \InvalidArgumentException(Constantes::MSG_ERRO_RECURSO_INEXISTENTE);
        }
        
        $this->validarRetornoRequest($retorno);

        return $retorno;
    }

    public function setDadosCorpoRequest($dadosRequest) {
        $this->dadosCorpoRequest = $dadosRequest;
    }

    private function getOneByKey()
    {
        return $this->UsuariosRepository->getMySQL()->getOneByKey(self::TABELA, $this->dados['id']);
    }

    private function listar()
    {
        return $this->UsuariosRepository->getMySQL()->getAll(self::TABELA);
    }

    private function deletar()
    {
        return $this->UsuariosRepository->getMySQL()->delete(self::TABELA, $this->dados['id']);
    }

    private function cadastrar()
    {
        [$login, $senha] = [$this->dadosCorpoRequest['login'], $this->dadosCorpoRequest['senha']];

        if ($login && $senha) {
            if($this->UsuariosRepository->insertUser($login, $senha) > 0) {
                $idInserido = $this->UsuariosRepository->getMySQL()->getDb()->lastInsertId();
                $this->UsuariosRepository->getMySQL()->getDb()->commit();
                return ['id_inserido' => $idInserido];
            }

            $this->UsuariosRepository->getMySQL()->getDb()->rollBack();

            throw new InvalidArgumentException(Constantes::MSG_ERRO_GENERICO);
        }
        throw new InvalidArgumentException(Constantess::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
    }

    private function atualizar()
    {
        if ($this->UsuariosRepository->updateUser($this->dados['id'], $this->dadosCorpoRequest) > 0) {
            $this->UsuariosRepository->getMySQL()->getDb()->commit();
            return Constantes::MSG_ATUALIZADO_SUCESSO;
        }

        $this->UsuariosRepository->getMySQL()->getDb()->rollBack();
        throw new \InvalidArgumentException(Constantes::MSG_ERRO_NAO_AFETADO);
    }

    /**
     * @param $retorno
     */
    private function validarRetornoRequest($retorno):void
    {
        if ($retorno === null) {
            throw new \InvalidArgumentException(Constantes::MSG_ERRO_GENERICO);
        }
    }

    private function validarIdObrigatório($recurso)
    {
        if($this->dados['id'] > 0 ) {
            $retorno = $this->$recurso();
        }else {
            throw new \InvalidArgumentException(Constantes::MSG_ERRO_ID_OBRIGATORIO);
        }

        return $retorno;
    }
}

