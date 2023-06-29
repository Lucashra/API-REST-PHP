<?php

namespace Repository;

use DB\MySQL;


class UsuariosRepository
{
    private object $MySQL;
    public const TABELA = "usuarios";
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->MySQL = new MySQL();
    }

    
    /**
     * Retorna instancia da conexão
     *
     * @return void
     */
    public function getMySQL()
    {
        return $this->MySQL;
    }
}