<?php

namespace Repository;

use DB\MySQL;

class TokensAutorizadosRepository
{
    private object $MySQL;
    public const TABELA = "tokens_autorizados";
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    public function validarToken($token)
    {

    }
    
    /**
     * getMySQL
     *
     * @return void
     */
    public function getMySQL()
    {
        return $this->MySQL;
    }
}