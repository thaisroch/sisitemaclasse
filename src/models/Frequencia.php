<?php

        //include_once("../config/Database.php");
    
        class Frequencia{
    
            public $msgErro = "";
            private $pdo;
    
            public function conectar($dbname, $host, $usuario, $senha){
    
                try{
                    $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$usuario,$senha);
                }catch(PDOException $e){
                    $this->msgErro = $e -> getMessage();
                }        
            }
            //---------------------------------------------------------------------------------------------------------------------
            public function buscarDadosFrequenciaClasse(int $id_pro): array
            {
                $res = array();
                $cmd = $this->pdo->prepare("");
    
                $cmd->bindValue(":p", $id_pro, PDO::PARAM_INT); 
                $cmd->execute();
                $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
                return $res;
            }    
            //---------------------------------------------------------------------------------------------------------------------
            public function cadastrarFequencia($dia, array $statusPresenteTurma, array $statusFaltaTurma, int $idclasse)
            {
                
            }
            //---------------------------------------------------------------------------------------------------------------------
            public function atualizarFrequencia(int $id_disciplina, string $nome, int $idprofessor): void
            {                   
                $cmd = $this->pdo->prepare("");
               
                $cmd->bindValue(":d", $id_disciplina, PDO::PARAM_INT);
                $cmd->bindValue(":n", $nome, PDO::PARAM_STR);
                $cmd->bindValue(":p", $idprofessor, PDO::PARAM_INT);
                $cmd->execute();    
            }    
            //------------------------------ -------------------------------------------------------------------------------------------
            public function excluirFrequencia($id_disciplina, $idprofessor){
                $cmd =  $this->pdo->prepare("");
        
                $cmd->bindValue(":id", $id_disciplina, PDO::PARAM_INT);
                $cmd->bindValue(":idpro", $idprofessor, PDO::PARAM_INT);
                $cmd->execute();
            }
    }
