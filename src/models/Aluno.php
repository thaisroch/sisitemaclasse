<?php

// include_once("../config/Database.php");

class Aluno
{

    public $msgErro = "";
    public $pdo;

    public function conectar($dbname, $host, $usuario, $senha)
    {

        try {
            $this->pdo = new PDO("mysql:dbname=" . $dbname . ";host=" . $host, $usuario, $senha);
        } catch (PDOException $e) {
            $this->msgErro = $e->getMessage();
        }
    }
    //---------------------------------------------------------------------------------------------------------------------
    public function buscarDadosAlunos($id_pro)
    {
        $res = array();
        $cmd = $this->pdo->prepare("SELECT a.id, a.nome, a.fk_tbl_classe_id1
                                    FROM tbl_aluno AS a 
                                    INNER JOIN tbl_professor AS p 
                                    ON a.fk_tbl_professor_id3 = p.id 
                                    WHERE a.fk_tbl_professor_id3 = :p");

        $cmd->bindValue(":p", $id_pro, PDO::PARAM_INT);
        $cmd->execute();
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function buscarDadosAlunosDeUmaClasse(int $id_pro, int $id_classe): array
    {
        $res = array();
        $cmd =   $this->pdo->prepare("SELECT a.id, a.nome
                                            FROM tbl_aluno AS a 
                                            INNER JOIN tbl_professor AS p 
                                            ON a.fk_tbl_professor_id3 = p.id 
                                            WHERE a.fk_tbl_professor_id3 = :p AND a.fk_tbl_classe_id1 = :c
                                            ORDER BY a.nome;");

        $cmd->bindValue(":p", $id_pro, PDO::PARAM_INT);
        $cmd->bindValue(":c", $id_classe, PDO::PARAM_INT);
        $cmd->execute();
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    //-----------------------------------------------------------------------------------------------------------------------
    public function excluirAluno(int $idaluno, int $idprofessor): void
    {
        $cmd = $this->pdo->prepare("DELETE a 
                                        FROM tbl_aluno a 
                                        LEFT JOIN  tbl_professor p 
                                        ON a.fk_tbl_professor_id3 = p.id 
                                        WHERE a.id=:al AND a.fk_tbl_professor_id3 = :idp;");


        $cmd->bindValue(":al", $idaluno, PDO::PARAM_INT);
        $cmd->bindValue(":idp", $idprofessor, PDO::PARAM_INT);
        $cmd->execute();
    }

    public function buscarDadosUmAluno(int $id_aluno, int $id_classe, int $idprofessor): array
    {

        $res = array();
        $cmd = $this->pdo->prepare("SELECT a.id, a.nome, a.sexualidade, a.nascimento
                                        FROM tbl_aluno  a 
                                        INNER JOIN tbl_professor p 
                                        ON a.fk_tbl_professor_id3 = p.id 
                                        WHERE a.fk_tbl_professor_id3 = :p AND a.fk_tbl_classe_id1 = :c AND a.id = :aluno;");

        $cmd->bindValue(":p", $idprofessor, PDO::PARAM_INT);
        $cmd->bindValue(":c", $id_classe, PDO::PARAM_INT);
        $cmd->bindValue(":aluno", $id_aluno, PDO::PARAM_INT);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    //-----------------------------------------------------------------------------------------------------------------------
    public function cadastrarAluno(string $nome, string $sexualidade,  $nascimento, int $classe, int $id_pro): bool
    {
        $cmd =  $this->pdo->prepare("SELECT a.id 
                                        FROM tbl_aluno AS a
                                        LEFT JOIN tbl_professor AS p 
                                        ON a.fk_tbl_professor_id3 = p.id 
                                        WHERE a.nome = :n AND a.fk_tbl_professor_id3 = :pro AND a.fk_tbl_classe_id1 = :c;");

        $cmd->bindValue(":n", $nome, PDO::PARAM_STR);
        $cmd->bindValue(":pro", $id_pro, PDO::PARAM_INT);
        $cmd->bindValue(":c", $classe, PDO::PARAM_INT);
        $cmd->execute();
        if ($cmd->rowCount() > 0) {
            return false;
        } else {
            $cmd = $this->pdo->prepare("INSERT INTO tbl_aluno (nome, sexualidade, nascimento, fk_tbl_classe_id1, fk_tbl_professor_id3) 
                                            VALUES (:n, :s, :nd, :c, :pro)");

            $cmd->bindValue(":n", $nome, PDO::PARAM_STR);
            $cmd->bindValue(":s", $sexualidade, PDO::PARAM_STR_CHAR);
            $cmd->bindValue(":nd", $nascimento);
            $cmd->bindValue(":c", $classe, PDO::PARAM_INT);
            $cmd->bindValue(":pro", $id_pro, PDO::PARAM_INT);
            $cmd->execute();
            return true;
        }
    }
    //-----------------------------------------------------------------------------------------------------------------------
    public function atualizarDadosAluno(int $id_aluno, string $nome, string $sexualidade, $nascimento, int $id_classe, int $idprofessor): void
    {
        $cmd = $this->pdo->prepare("UPDATE tbl_aluno a                                       
                                        LEFT JOIN tbl_professor p 
                                        ON a.fk_tbl_professor_id3 = p.id 
                                        SET a.nome =:n, a.sexualidade =:sx, a.nascimento =:dt, a.fk_tbl_classe_id1=:c, a.fk_tbl_professor_id3 =:p                                        
                                        WHERE a.id =:aluno AND a.fk_tbl_classe_id1 =:c;");

        $cmd->bindValue(":aluno", $id_aluno, PDO::PARAM_INT);
        $cmd->bindValue(":n", $nome);
        $cmd->bindValue(":sx", $sexualidade);
        $cmd->bindValue(":dt", $nascimento);
        $cmd->bindValue(":c", $id_classe, PDO::PARAM_INT);
        $cmd->bindValue(":p", $idprofessor, PDO::PARAM_INT);
        $cmd->execute();
    }

    //--------------------------------------------------------------------------------------------------------------------------

    public function qtdadeAluno(int $idprofessor): int
    {
        $cmd = $this->pdo->prepare("SELECT COUNT(*)     
                                        FROM tbl_aluno AS a 
                                        INNER JOIN tbl_professor AS p ON a.fk_tbl_professor_id3 = p.id 
                                        WHERE a.fk_tbl_professor_id3 =:p;");

        $cmd->bindValue(":p", $idprofessor);
        $cmd->execute();
        $res = $cmd->fetchColumn();
        return $res;
    }
}
