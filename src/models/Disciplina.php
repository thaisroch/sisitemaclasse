<?php

// include_once("../config/Database.php");

class Disciplina
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
    public function buscarDadosDisciplina(int $id_pro): array
    {
        $res = array();
        $cmd = $this->pdo->prepare("SELECT d.id, d.nome
                              FROM tbl_disciplina d 
                              INNER JOIN tbl_professor p 
                              ON d.fk_tbl_professor_id2 = p.id 
                              WHERE d.fk_tbl_professor_id2 = :p
                              ORDER BY d.nome;");

        $cmd->bindValue(":p", $id_pro, PDO::PARAM_INT);
        $cmd->execute();
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    //-----------------------------------------------------------------------------------------------------------------------
    public function cadastrarDisciplina(string $nome, int $id_pro): bool
    {
        $cmd = $this->pdo->prepare("SELECT d.id 
                                   FROM tbl_disciplina  d 
                                   INNER JOIN tbl_professor p 
                                   ON d.fk_tbl_professor_id2 = p.id 
                                   WHERE d.nome = :n AND d.fk_tbl_professor_id2 = :pro");

        $cmd->bindValue(":n", $nome, PDO::PARAM_STR);
        $cmd->bindValue(":pro", $id_pro, PDO::PARAM_INT);
        $cmd->execute();
        if ($cmd->rowCount() > 0) {
            return false;
        } else {
            $cmd = $this->pdo->prepare("INSERT INTO tbl_disciplina (nome, fk_tbl_professor_id2) 
                                      VALUES (:n, :pro)");

            $cmd->bindValue(":n", $nome, PDO::PARAM_STR);
            $cmd->bindValue(":pro", $id_pro, PDO::PARAM_INT);
            $cmd->execute();
            return true;
        }
    }
    public function atualizarDisicplina(int $id_disciplina, string $nome, int $idprofessor): void
    {
        $cmd = $this->pdo->prepare("");

        $cmd->bindValue(":d", $id_disciplina, PDO::PARAM_INT);
        $cmd->bindValue(":n", $nome, PDO::PARAM_STR);
        $cmd->bindValue(":p", $idprofessor, PDO::PARAM_INT);
        $cmd->execute();
    }
    //-------------------------------------------------------------------------------------------------------------------------        
    public function excluirDisciplina(int $id_disciplina, int $idprofessor): bool
    {
        $cmd = $this->pdo->prepare("DELETE  d
                                    FROM tbl_disciplina d
                                    INNER JOIN  tbl_professor p
                                    ON d.fk_tbl_professor_id2 = p.id 
                                    WHERE d.id=:id and d.fk_tbl_professor_id2=:idpro;");


        $cmd->bindValue(":id", $id_disciplina, PDO::PARAM_INT);
        $cmd->bindValue(":idpro", $idprofessor, PDO::PARAM_INT);
        $cmd->execute();
        if ($cmd->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
