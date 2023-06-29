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
     * insertUser
     *
     * @param  mixed $login
     * @param  mixed $senha
     * @return void
     */
    public function insertUser($login, $senha) {
        $consultaInsert = ' INSERT INTO ' . self::TABELA . ' (login,senha) VALUES (:login, :senha) ';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($consultaInsert);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();
        return $stmt->rowCount();

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