<?php

namespace Service;

use Repository\ContatosRepository;
use Util\FuncoesUtil as Util;
use Util\ConstantesGenericasUtil as Constantes;

class ContatosService
{
    public const TABELA = 'tb_contato';
    public const RECURSOS_GET = ['listar'];
    public const RECURSOS_DELETE = ['deletar']; 
    public const RECURSOS_POST = ['cadastrar'];
    // public const RECURSOS_PUT = ['atualizar'];

    /**
     * @var array
     */
    private array $dados;

    /**
     * @var object|ContatosRepository
     */
    private object $ContatosRepository;

    /**
     * @var array
     */
    private array $dadosCorpoRequest = [];

    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->ContatosRepository = new ContatosRepository();
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
     * @return mixed
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
        return $this->ContatosRepository->getContatos($this->dados['id']);
    }

    private function listar()
    {
        return $this->ContatosRepository->getContatos();
    }

    private function deletar()
    {
        return $this->ContatosRepository->delete($this->dados['id']);
    }

    private function cadastrar()
    {

        $aContatos = ["nome"=> $this->dadosCorpoRequest['ps_nome'],
                      "cpf" => $this->dadosCorpoRequest['ps_cpf'],
                      "dt_nascimento" => $this->dadosCorpoRequest['ps_dt_nascimento'],
                      "telefone" => $this->dadosCorpoRequest['ct_telefone'],
                      "email" => $this->dadosCorpoRequest['ct_email'],
                      "whatsapp" => $this->dadosCorpoRequest['ct_whatsapp']];
                           
                 
        if (!empty($aContatos['nome'])) {
            if($this->ContatosRepository->insertContatos($aContatos['nome'],
                                                         $aContatos['cpf'], 
                                                         $aContatos['dt_nascimento'], 
                                                         $aContatos['telefone'], 
                                                         $aContatos['email'], 
                                                         $aContatos['whatsapp']) > 1) {
                $idInserido = $this->ContatosRepository->getMySQL()->getDb()->lastInsertId();
                $this->ContatosRepository->getMySQL()->getDb()->commit();
               
                return ['id_inserido' => $idInserido];
            }
           
            $this->ContatosRepository->getMySQL()->getDb()->rollBack();

            throw new \InvalidArgumentException(Constantes::MSG_ERRO_GENERICO);
        }
        throw new \InvalidArgumentException(Constantes::MSG_ERRO_NOME_OBRIGATORIO);
    }

    private function atualizar()
    {
        if ($this->ContatosRepository->updateUser($this->dados['id'], $this->dadosCorpoRequest) > 0) {
            $this->ContatosRepository->getMySQL()->getDb()->commit();
            return Constantes::MSG_ATUALIZADO_SUCESSO;
        }

        $this->ContatosRepository->getMySQL()->getDb()->rollBack();
        throw new \InvalidArgumentException(Constantes::MSG_ERRO_NAO_AFETADO);
    }

    /**
     * @param $retorno
     */
    private function validarRetornoRequest($retorno):void
    {
        if ($retorno == null) {
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

