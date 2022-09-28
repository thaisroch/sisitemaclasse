<?php

//include_once("../config/Database.php");

class Classe
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

    //---------------------------------------------------------------------------------
    public function buscarDadosClasse(int $idprofessor): array
    {
        $res = array();
        $cmd =  $this->pdo->prepare(" SELECT c.id, c.nome, c.ano, c.periodo
                                   FROM tbl_classe AS c 
                                   INNER JOIN tbl_professor AS p 
                                   ON c.fk_tbl_professor_id1 = p.id 
                                   WHERE c.fk_tbl_professor_id1 = :idPro
                                   ORDER BY c.nome;");

        $cmd->bindValue(":idPro", $idprofessor, PDO::PARAM_INT);
        $cmd->execute();
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    //--------------------------------------------------------------------------------
    public function cadastrarClasse(string $nome, $ano, string $periodo, int $disciplina, int $escola, int $idprofessor): bool
    {
        $cmd = $this->pdo->prepare("SELECT c.id 
                                        FROM tbl_classe AS c 
                                        LEFT JOIN tbl_professor AS p 
                                        ON c.fk_tbl_professor_id1 = p.id 
                                        WHERE c.nome =:ns  AND c.fk_tbl_professor_id1 =:prof;");

        $cmd->bindValue(":ns", $nome, PDO::PARAM_STR);
        $cmd->bindValue(":prof", $idprofessor,  PDO::PARAM_INT);
        $cmd->execute();

        if ($cmd->rowCount() > 0) {
            return false;
        } else {
            $cmd = $this->pdo->prepare("INSERT INTO  tbl_classe(nome, ano, periodo, fk_tbl_disciplina_id, fk_tbl_professor_id1, fk_tbl_escola_id) 
                                            VALUES (:n, :ano, :p, :d, :e, :pro)");

            $cmd->bindValue(":n", $nome, PDO::PARAM_STR);
            $cmd->bindValue(":ano", $ano);
            $cmd->bindValue(":p", $periodo, PDO::PARAM_STR_CHAR);
            $cmd->bindValue(":d", $disciplina, PDO::PARAM_INT);
            $cmd->bindValue(":e", $escola, PDO::PARAM_INT);
            $cmd->bindValue(":pro", $idprofessor, PDO::PARAM_INT);
            $cmd->execute();
            return true;
        }
    }
    //-------------------------------------------------------------------------------------------------------------------------
    public function buscardadosUmaClasseEditar(int $idClasse, int $idprofessor): array
    {
        $res = array();
        $cmd = $this->pdo->prepare("SELECT c.id, c.nome, c.ano, c.periodo, c.fk_tbl_disciplina_id, c.fk_tbl_professor_id1, c.fk_tbl_escola_id 
                                        FROM tbl_classe AS c 
                                        INNER JOIN tbl_professor AS  p 
                                        ON c.fk_tbl_professor_id1 = p.id 
                                        WHERE c.fk_tbl_professor_id1 = :idPro AND c.id = :c");

        $cmd->bindValue(":c", $idClasse, PDO::PARAM_INT);
        $cmd->bindValue(":idPro", $idprofessor, PDO::PARAM_INT);

        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;
    }
    public function buscardadosUmaClasse(int $idClasse, int $idprofessor): array
    {
        $res = array();
        $cmd = $this->pdo->prepare("SELECT c.id, c.nome, c.ano, c.periodo, c.fk_tbl_disciplina_id, c.fk_tbl_professor_id1, c.fk_tbl_escola_id 
                                        FROM tbl_classe AS c 
                                        INNER JOIN tbl_professor AS  p 
                                        ON c.fk_tbl_professor_id1 = p.id 
                                        WHERE c.fk_tbl_professor_id1 = :idPro AND c.id = :c");

        $cmd->bindValue(":c", $idClasse, PDO::PARAM_INT);
        $cmd->bindValue(":idPro", $idprofessor, PDO::PARAM_INT);

        $cmd->execute();
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    //-----------------------------------------------------------------------------------------------------------------------
    public function excluirClasse(int $id_classe, int $idprofessor): bool
    {
        $cmd = $this->pdo->prepare("DELETE  c
                                        FROM tbl_classe c
                                        LEFT JOIN  tbl_professor p
                                        ON c.fk_tbl_professor_id1 = p.id 
                                        WHERE c.id=:id AND c.fk_tbl_professor_id1=:idpro;");



        $cmd->bindValue(":id", $id_classe, PDO::PARAM_INT);
        $cmd->bindValue(":idpro", $idprofessor, PDO::PARAM_INT);
        $cmd->execute();

        if ($cmd->rowCount() > 0) {
            return false;
        } else {
            return true;
        }
    }
    //-------------------------------------------------------------------------------------------------------------------------
    public function atualizarDadosClasse(int $id_update, string $nome, $ano, string $periodo, int $disciplina, int $escola, int $idprofessor): bool
    {
        $cmd = $this->pdo->prepare("SELECT c.id 
                                             FROM tbl_classe AS c
                                             LEFT JOIN tbl_professor AS p 
                                             ON c.fk_tbl_professor_id1 = p.id 
                                             WHERE c.nome = :n AND c.periodo = :p AND c.fk_tbl_escola_id = :e AND c.fk_tbl_professor_id1 = :pro;");

        $cmd->bindValue(":n", $nome, PDO::PARAM_STR);
        $cmd->bindValue(":pro", $idprofessor, PDO::PARAM_INT);
        $cmd->bindValue(":p", $periodo, PDO::PARAM_STR_CHAR);
        $cmd->bindValue(":e", $escola, PDO::PARAM_INT);
        $cmd->execute();

        if ($cmd->rowCount() > 0) {
            return false;
        } else {

            $cmd = $this->pdo->prepare("UPDATE tbl_classe c
                                        LEFT JOIN tbl_professor p
                                        ON c.fk_tbl_professor_id1 = p.id
                                        SET c.nome =:n, c.ano = :a, c.periodo = :p, c.fk_tbl_disciplina_id = :d, c.fk_tbl_escola_id = :e, c.fk_tbl_professor_id1 = :pro
                                        WHERE c.id =:up;");

            $cmd->bindValue(":up", $id_update, PDO::PARAM_INT);
            $cmd->bindValue(":n", $nome, PDO::PARAM_STR);
            $cmd->bindValue(":a", $ano);
            $cmd->bindValue(":p", $periodo, PDO::PARAM_STR_CHAR);
            $cmd->bindValue(":d", $disciplina, PDO::PARAM_INT);
            $cmd->bindValue(":pro", $idprofessor, PDO::PARAM_INT);
            $cmd->bindValue(":e", $escola, PDO::PARAM_INT);
            $cmd->execute();

            return true;
        }
    }
    //----------------------------------------------------------------------------------------------------------------------------  
    public function qtdadeClasse(int $idprofessor): int
    {
        $cmd = $this->pdo->prepare("SELECT COUNT(*)     
                                        FROM tbl_classe AS classe 
                                        INNER JOIN tbl_professor AS professor ON classe.fk_tbl_professor_id1 = professor.id 
                                        WHERE classe.fk_tbl_professor_id1 = :p;");

        $cmd->bindValue(":p", $idprofessor, PDO::PARAM_INT);
        $cmd->execute();
        $res = $cmd->fetchColumn();
        return $res;
    }
    //---------------------------------------------------------------------------------------------------------------------------     
    // ESSES DAQUI ACHO QUE NÃO PRECISAMOS MANTER EM DOIS LOCAIS


    public function buscardadosUmaDisciplina($id_disciplina, $idprofessor)
    {
        $res = array();
        $cmd = $this->pdo->prepare(" SELECT d.id, d.nome, d.fk_tbl_professor_id2
                                FROM tbl_disciplina AS d 
                                INNER JOIN tbl_professor AS  p 
                                ON d.fk_tbl_professor_id2 = p.id 
                                WHERE d.fk_tbl_professor_id2 = :idPro AND d.id = :d;");

        $cmd->bindValue(":d", $id_disciplina, PDO::PARAM_INT);
        $cmd->bindValue(":idPro", $idprofessor, PDO::PARAM_INT);

        $cmd->execute();
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    public function buscarDadosEscola($idprofessor)
    {

        $res = array();
        $cmd = $this->pdo->prepare("SELECT e.id, e.nome, e.email 
                                            FROM tbl_escola e 
                                            LEFT JOIN tbl_professor p 
                                            ON e.fk_tbl_professor_id = p.id 
                                            WHERE e.fk_tbl_professor_id = :p;");

        $cmd->bindValue(":p", $idprofessor, PDO::PARAM_INT);
        $cmd->execute();
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    public function buscarDadosDisciplina($id_pro)
    {
        $res = array();

        $cmd = $this->pdo->prepare("SELECT d.id, d.nome
                                        FROM tbl_disciplina d 
                                        LEFT JOIN tbl_professor p 
                                        ON d.fk_tbl_professor_id2 = p.id 
                                        WHERE d.fk_tbl_professor_id2 = :p;");

        $cmd->bindValue(":p", $id_pro, PDO::PARAM_INT);
        $cmd->execute();
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
}
