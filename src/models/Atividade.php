<?php

//include_once("../config/Database.php");

class Atividade
{

    public $msgErro = "";
    private $pdo;

    public function conectar($dbname, $host, $usuario, $senha)
    {

        try {
            $this->pdo = new PDO("mysql:dbname=" . $dbname . ";host=" . $host, $usuario, $senha);
        } catch (PDOException $e) {
            $this->msgErro = $e->getMessage();
        }
    }
    //---------------------------------------------------------------------------------------------------------------------
    public function buscarAtividadeEspecificaClasse(int $id_atividade, int $id_pro): array
    {

        $res = array();
        $cmd = $this->pdo->prepare("SELECT a.id, a.nome, a.valor
                                        FROM tbl_atividade a 
                                        INNER JOIN tbl_professor p 
                                        ON a.fk_tbl_professor_id4 = p.id 
                                        WHERE a.fk_tbl_professor_id4 = :p AND a.id = :ativ;");

        $cmd->bindValue(":ativ", $id_atividade, PDO::PARAM_INT);
        $cmd->bindValue(":p", $id_pro, PDO::PARAM_INT);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    //---------------------------------------------------------------------------------------------------------------------
    public function buscarAtividadeClasse(int $id_pro, int $classe): array
    {
        $res = array();
        $cmd = $this->pdo->prepare("SELECT a.id, a.nome, a.valor
                                        FROM tbl_atividade AS a 
                                        INNER JOIN tbl_professor AS p 
                                        ON a.fk_tbl_professor_id4 = p.id 
                                        WHERE a.fk_tbl_professor_id4 = :p AND a.fk_tbl_classe_id2 = :c;");

        $cmd->bindValue(":p", $id_pro, PDO::PARAM_INT);
        $cmd->bindValue(":c", $classe, PDO::PARAM_INT);
        $cmd->execute();
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    //-----------------------------------------------------------------------------------------------------------------------
    public function cadastrarAtividade(string $nome, float $valor, int $idprofessor, int $id_classe): bool
    {
        $cmd = $this->pdo->prepare("SELECT a.id 
                                        FROM tbl_atividade AS a
                                        LEFT JOIN tbl_professor AS p 
                                        ON a.fk_tbl_professor_id4 = p.id
                                        WHERE a.nome = :n AND a.fk_tbl_professor_id4 = :pro AND a.fk_tbl_classe_id2 = :c;");

        $cmd->bindValue(":n", $nome, PDO::PARAM_STR);
        $cmd->bindValue(":pro", $idprofessor, PDO::PARAM_INT);
        $cmd->bindValue(":c", $id_classe, PDO::PARAM_INT);
        $cmd->execute();
        if ($cmd->rowCount() > 0) {
            return false;
        } else {
            $cmd = $this->pdo->prepare("INSERT INTO tbl_atividade (nome, valor, fk_tbl_professor_id4, fk_tbl_classe_id2) 
                                            VALUES (:n, :v, :pro, :c)");

            $cmd->bindValue(":n", $nome, PDO::PARAM_STR);
            $cmd->bindValue(":v", $valor, PDO::PARAM_STR);
            $cmd->bindValue(":pro", $idprofessor, PDO::PARAM_INT);
            $cmd->bindValue(":c", $id_classe, PDO::PARAM_INT);
            $cmd->execute();
            return true;
        }
    }
    public function atualizarAtividade(int $up_atividade, string $nome, float $valor, int $idprofessor, int $id_classe): void
    {
        $cmd = $this->pdo->prepare("UPDATE tbl_atividade a                                       
                                        LEFT JOIN tbl_professor p 
                                        ON a.fk_tbl_professor_id4 = p.id 
                                        SET a.nome =:n, a.valor =:v, a.fk_tbl_professor_id4 =:p, a.fk_tbl_classe_id2 =:c                                        
                                        WHERE a.id =:ativ;");

        $cmd->bindValue(":ativ", $up_atividade, PDO::PARAM_INT);
        $cmd->bindValue(":n", $nome, PDO::PARAM_STR);
        $cmd->bindValue(":v", $valor);
        $cmd->bindValue(":p", $idprofessor, PDO::PARAM_INT);
        $cmd->bindValue(":c", $id_classe, PDO::PARAM_INT);
        $cmd->execute();
    }

    //------------------------------ -------------------------------------------------------------------------------------------       
    public function excluirAtividade(int $id_atividade, int $idprofessor): void
    {
        $cmd = $this->pdo->prepare("DELETE  a
                                        FROM tbl_atividade a
                                        LEFT JOIN  tbl_professor p
                                        ON a.fk_tbl_professor_id4 = p.id 
                                        WHERE a.id=:id AND a.fk_tbl_professor_id4=:idpro;");

        $cmd->bindValue(":id", $id_atividade, PDO::PARAM_INT);
        $cmd->bindValue(":idpro", $idprofessor, PDO::PARAM_INT);
        $cmd->execute();
    }
}
