<?php

namespace Repository;

use DB\MySQL;
use PDO;
use Util\ConstantesGenericasUtil as Constantes;
use Util\FuncoesUtil as Util;


class ContatosRepository
{
    /**
     * @var object|MySQL
     */
    private object $MySQL;
    public const TB_CONTATO = "tb_contato";
    public const TB_PESSOA = "tb_pessoa";
    
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
     * Consulta contatos se passar id como parâmetro irá buscar contato específico
     *
     * @return array|object
     */
    public function getContatos($idContato = null)
    {
          
        $sql =  'SELECT ';
        $sql .= '   ct_id, ';
        $sql .= '   ct_ps_id, ';
        $sql .= '   ps_nome, ';
        $sql .= '   ps_cpf, ';
        $sql .= '   ps_dt_nascimento, ';
        $sql .= '   ct_telefone, ';
        $sql .= '   ct_email, ';
        $sql .= '   ct_whatsapp ';
        $sql .= 'FROM ';
        $sql .= '   tb_contato ct ';
        $sql .= '   LEFT JOIN tb_pessoa ps on ct.ct_ps_id = ps.ps_id ';
       
        $query = $this->getMySQL()->getDb();

        if ($idContato) {
            $sql .= 'WHERE ';
            $sql .= '   ct.ct_id = :ct_id ; ';
            $stmt = $query->prepare($sql);
            $stmt->bindParam(':ct_id', $idContato);
        } else {
            $stmt = $query->prepare($sql);
        }
        $stmt->execute();
        $registros = $stmt->fetchAll($query::FETCH_ASSOC);
      
        if (is_array($registros) && count($registros) > 0) {
            return $registros;
        }
        
        throw new \InvalidArgumentException(Constantes::MSG_ERRO_SEM_RETORNO);
    }
        
    /**
     * Insert de dados na tabela de pessoa e contato
     *
     * @param  mixed $nome
     * @param  mixed $cpf
     * @param  mixed $dtNascimento
     * @param  mixed $telefone
     * @param  mixed $email
     * @param  mixed $whatsapp
     * @return int
     */
    public function insertContatos($nome, $cpf, $dtNascimento, $telefone, $email, $whatsapp) {
        try {
            /** Gravando dados na tabela da pessoa */
            $sql = ' INSERT INTO '. self::TB_PESSOA . ' (ps_nome, ps_cpf, ps_dt_nascimento) ';
            $sql .= 'VALUES (:ps_nome, :ps_cpf, :ps_dt_nascimento) ;';
            $queryPessoa = $this->getMySQL()->getDb();
            $queryPessoa->beginTransaction();
            $stmt = $queryPessoa->prepare($sql);
            $stmt->bindParam(':ps_nome', $nome);
            $stmt->bindParam(':ps_cpf', $cpf);
            $stmt->bindParam(':ps_dt_nascimento', $dtNascimento);
            $stmt->execute();
            $idPessoa = $queryPessoa->lastInsertId();
            $retorno = $stmt->rowCount();
            $queryPessoa->commit();

            /** Gravando dados na tebela de contatos */
            $sql = ' INSERT INTO '. self::TB_CONTATO . ' (ct_ps_id, ct_telefone, ct_email, ct_whatsapp) ';
            $sql .= 'VALUES (:ct_ps_id, :ct_telefone, :ct_email, :ct_whatsapp) ;';
            $queryContato = $this->getMySQL()->getDb();
            $queryContato->beginTransaction();
            $stmt = $queryContato->prepare($sql);
            $stmt->bindParam(':ct_ps_id', $idPessoa);
            $stmt->bindParam(':ct_telefone', $telefone);
            $stmt->bindParam(':ct_email', $email);
            $stmt->bindParam(':ct_whatsapp', $whatsapp);
            $stmt->execute();
            $retorno += $stmt->rowCount();
        } catch (PDOException $e) {
            $queryPessoa->rollBack();
            $queryContato->rollBack();
            $e->getMessage();
        }
        return $retorno;
    }

      /**
     * @param $tabela
     * @param $id
     * @return string
     */
    public function delete($id)
    {
        $sql = 'DELETE FROM tb_pessoa WHERE ps_id = :id';
        if ($id) {
            $query = $this->getMySQL()->getDb();
            $query->beginTransaction();
            $stmt = $query->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $query->commit();
                return Constantes::MSG_DELETADO_SUCESSO;
            }
            $query->rollBack();
            throw new \InvalidArgumentException(Constantes::MSG_ERRO_SEM_RETORNO);
        }
        throw new \InvalidArgumentException(Constantes::MSG_ERRO_GENERICO);
    }

    
    /**
     * 
     * updateUser
     *
     * @param  mixed $id
     * @param  mixed $dados
     * @return int
     */
    public function updateUser($id_pessoa, $nome = null, $cpf = null, $dt_nascimento = null) {
        if ($id_pessoa && $nome || $cpf || $dt_nascimento) {
            $sqlPessoa  = "UPDATE ";
            $sqlPessoa .=       self::TB_PESSOA ;
            $sqlPessoa .= " SET ";
            $sqlPessoa .= $nome ? "  ps_nome = :nome " : " ";
            $sqlPessoa .= $nome && $cpf ? ", " : " " ;   
            $sqlPessoa .= $cpf ?  " ps_cpf = :cpf " : " ";
            $sqlPessoa .= $cpf && $dt_nascimento ? ", " : " ";          
            $sqlPessoa .= $dt_nascimento ? " ps_dt_nascimento = :dt_nascimento " : " ";
            $sqlPessoa .= "WHERE ";
            $sqlPessoa .= "     ps_id = :id_pessoa ";
        } else {
            return 0;
        }
        
        $queryPessoa = $this->getMySQL()->getDb();
        $queryPessoa->beginTransaction();      
        $stmt = $queryPessoa->prepare($sqlPessoa);
        $stmt->bindParam(':id_pessoa', $id_pessoa, PDO::PARAM_INT);
        
        if ($nome) {
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        }

        if ($cpf) {
            $stmt->bindParam(':cpf', $cpf);
        }

        if ($dt_nascimento) {
            $stmt->bindParam(':dt_nascimento', $dt_nascimento);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Retorna instancia da conexão
     * @return MySQL|object
     * 
     */
    public function getMySQL()
    {
        return $this->MySQL;
    }
}