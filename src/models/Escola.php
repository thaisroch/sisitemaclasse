<?php

    //include_once("../config/Database.php");
    class Escola{

    public $msgErro = "";
    private $pdo;

        public function conectar($dbname, $host, $usuario, $senha): void
        {


            try{
                $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$usuario,$senha);
            }catch(PDOException $e){
                $this->msgErro = $e -> getMessage();
            }        
        }

    //-----------------------------------------------------------------------------------------------------------------------
        public function cadastrarEscola(string $nome, string $email, int $idprofessor): bool
        {
            $cmd = $this->pdo->prepare("SELECT e.id 
                                        FROM tbl_escola e 
                                        LEFT JOIN tbl_professor p 
                                        ON e.fk_tbl_professor_id = p.id
                                        WHERE e.email = :e AND c.fk_tbl_professor_id = :pro");           

            $cmd->bindValue(":e", $email);
            $cmd->bindValue(":pro", $idprofessor, PDO::PARAM_INT);
            $cmd->execute();
            if($cmd->rowCount() > 0) 
            {
                return false;
            }else{ 
                $cmd = $this->pdo->prepare("INSERT INTO tbl_escola (nome, email, fk_tbl_professor_id) 
                                            VALUES (:n, :e, :p)");

                $cmd->bindValue(":n", $nome);
                $cmd->bindValue(":e", $email);
                $cmd->bindValue(":p", $idprofessor, PDO::PARAM_INT);
                $cmd->execute();
                return true;
            }
        }
        //-------------------------------------------------------------------------------------------------------------------------     
        public function excluirEscola(int $id_escola, int $idprofessor): bool
        {   
            $cmd = $this->pdo->prepare("DELETE e
                                        FROM tbl_escola e
                                        LEFT JOIN tbl_professor p
                                        ON e.fk_tbl_professor_id = p.id 
                                        WHERE e.id=:id AND e.fk_tbl_professor_id=:idpro");

            $cmd->bindValue(":id", $id_escola, PDO::PARAM_INT);
            $cmd->bindValue(":idpro", $idprofessor, PDO::PARAM_INT);
            $cmd->execute();
            if($cmd->rowCount() > 0) 
            {
                return true;
            }else{    
                return false;
            
            }
        }
        //------------------------------------------------------------------------------------------------------------------------
        public function buscarDadosUmaEscola(int $id_upEscola, int $idprofessor): array
        {
            $res = array();
            $cmd = $this->pdo->prepare("SELECT e.id, e.nome, e.email
                                        FROM tbl_escola  e 
                                        INNER JOIN tbl_professor  p 
                                        ON e.fk_tbl_professor_id = p.id 
                                        WHERE e.fk_tbl_professor_id = :idPro AND e.id = :e;");

            $cmd->bindValue(":e", $id_upEscola, PDO::PARAM_INT);            
            $cmd->bindValue(":idPro", $idprofessor, PDO::PARAM_INT);            
            $cmd->execute();
            $res = $cmd->fetch(PDO::FETCH_ASSOC);
            return $res;
        }
        public function buscardadosUmaEscoladaClasse(int $id_upEscola, int $idprofessor): array
        {                   
            $res = array();
            $cmd = $this->pdo->prepare("SELECT e.id, e.nome, e.email
                                        FROM tbl_escola  e 
                                        INNER JOIN tbl_professor  p 
                                        ON e.fk_tbl_professor_id = p.id 
                                        WHERE e.fk_tbl_professor_id = :idPro AND e.id = :e;");

            $cmd->bindValue(":e", $id_upEscola, PDO::PARAM_INT);            
            $cmd->bindValue(":idPro", $idprofessor, PDO::PARAM_INT);            
            $cmd->execute();
            $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }
        //-----------------------------------------------------------------------------------------------------------------------
        public function buscarDadosEscola( int $idprofessor): array
        {
            $res = array();
            $cmd = $this->pdo->prepare("SELECT e.id, e.nome, e.email 
                                        FROM tbl_escola e 
                                        INNER JOIN tbl_professor p 
                                        ON e.fk_tbl_professor_id = p.id 
                                        WHERE e.fk_tbl_professor_id = :p");

            $cmd->bindValue(":p", $idprofessor, PDO::PARAM_INT);
            $cmd->execute();
            $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }
        
        //---------------------------------------------- -----------------------------------------------------------------------
        public function atualizarDadosEscola(int $id_update, string $nome, string $email, int $idprofessor): void
        {          
            $cmd = $this->pdo->prepare("UPDATE tbl_escola e                                       
                                        LEFT JOIN tbl_professor p 
                                        ON e.fk_tbl_professor_id = p.id 
                                        SET e.nome =:n, e.email =:e, e.fk_tbl_professor_id =:p                                        
                                        WHERE e.id =:id;");

            $cmd->bindValue(":n", $nome, PDO::PARAM_STR);
            $cmd->bindValue(":e", $email, PDO::PARAM_STR);
            $cmd->bindValue(":id", $id_update, PDO::PARAM_INT);
            $cmd->bindValue(":p", $idprofessor, PDO::PARAM_INT);
            $cmd->execute();    
        }  
        //---------------------------------------------------------------------------------------------------------------------
        public function qtdadeEscola(int $idprofessor): int 
        {
                
            $cmd =  $this->pdo->prepare("SELECT COUNT(*)     
                                         FROM tbl_escola AS escola 
                                         INNER JOIN tbl_professor AS professor ON escola.fk_tbl_professor_id = professor.id 
                                         WHERE escola.fk_tbl_professor_id = :p;");
        
            $cmd->bindValue(":p", $idprofessor);
            $cmd->execute();
            $res = $cmd->fetchColumn();
            return $res;
        }
    }
