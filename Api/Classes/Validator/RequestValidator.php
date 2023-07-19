<?php

namespace Validator;

use Repository\TokensAutorizadosRepository;
use Service\UsuariosService;
use Service\ContatosService;
use Util\ConstantesGenericasUtil as Constantes;
use Util\JsonUtil;
use Util\FuncoesUtil as Util;


class RequestValidator
{
    private $request;  
    private $dadosRequest = [];
    private object $TokenAutorizadosRepository;

    const GET = "GET";
    const DELETE = "DELETE";
    const POST = "POST";
    const USUARIOS = "USUARIOS";
    const CONTATO = "CONTATO";

    public function __construct($request)
    {
        $this->request = $request;
        // $this->TokenAutorizadosRepository = new TokensAutorizadosRepository();
    }

    /**
     * @return array|mixed|string|null
     */
    public function processarRequest()
    {
        $retorno = Constantes::MSG_ERRO_TIPO_ROTA;
        if (in_array($this->request['metodo'], Constantes::TIPO_REQUEST, true)) {
            
            $retorno = $this->direcionarRequest();
        }
        return $retorno;
    } 

    private function direcionarRequest()
    {
        if ($this->request['metodo'] !== self::GET && $this->request['metodo'] !== self::DELETE) {
                $this->dadosRequest = JsonUtil::tratarCorpoRequisicaoJson();
            }
            // $this->TokenAutorizadosRepository->validarToken(getallheaders()['Authorization']);
        $metodo = $this->request['metodo'];
        return $this->$metodo();
    }

    private function get() 
    {
        $retorno = Constantes::MSG_ERRO_TIPO_ROTA;
        if (in_array($this->request['rota'], Constantes::TIPO_GET, true)){
            switch ($this->request['rota']) {
                case self::USUARIOS:
                    $usuariosService = new UsuariosService($this->request);
                    $retorno = $usuariosService->validarGet();
                    break;
                case self::CONTATO:
                    $contatosService = new ContatosService($this->request);
                    $retorno = $contatosService->validarGet();
                    break;
                default:
                    throw new \InvalidArgumentException(Constantes::MSG_ERRO_TIPO_ROTA);
            }
        }
        return $retorno;
    }

    private function delete() 
    {
        $retorno = Constantes::MSG_ERRO_TIPO_ROTA;
        if (in_array($this->request['rota'], Constantes::TIPO_DELETE, true)){
            switch ($this->request['rota']) {
                case self::USUARIOS:
                    $usuariosService = new UsuariosService($this->request);
                    $retorno = $usuariosService->validarDelete();
                    break;
                case self::CONTATO:
                    $contatosService = new ContatosService($this->request);
                    $retorno = $contatosService->validarDelete();
                    break;
                default:
                    throw new \InvalidArgumentException(Constantes::MSG_ERRO_TIPO_ROTA);
            }
        }
        return $retorno;
    }

    private function post() 
    {
        $retorno = Constantes::MSG_ERRO_TIPO_ROTA;
        if (in_array($this->request['rota'], Constantes::TIPO_POST, true)){
            switch ($this->request['rota']) {
                case self::USUARIOS:
                    $usuariosService = new UsuariosService($this->request);
                    $usuariosService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $usuariosService->validarPost();
                    break;
                case self::CONTATO:
                    $contatosService = new ContatosService($this->request);
                    $contatosService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $contatosService->validarPost();
                break;
                default:
                    throw new \InvalidArgumentException(Constantes::MSG_ERRO_TIPO_ROTA) ;
            }
        }
        return $retorno;
    }
    
    private function put()
    {
        $retorno = Constantes::MSG_ERRO_TIPO_ROTA;
        if (in_array($this->request['rota'], Constantes::TIPO_PUT, true)){
            switch ($this->request['rota']) {
                case self::USUARIOS:
                    $usuariosService = new UsuariosService($this->request);
                    $usuariosService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $usuariosService->validarPut();
                    break;
                case self::CONTATO:
                    $contatosService = new ContatosService($this->request);
                    $contatosService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $contatosService->validarPut();
                    break;
                default:
                    throw new \InvalidArgumentException(Constantes::MSG_ERRO_TIPO_ROTA);
            }
        }
        return $retorno;
    }
}