<?php

    //include_once("../config/Database.php");

    class Professor{

    public $msgErro = "";
    private $pdo;

    public function conectar($dbname, $host, $usuario, $senha){
       

        try{
            $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$usuario,$senha);
        }catch(PDOException $e){
            $this->msgErro = $e -> getMessage();
        }        
    }
    public function cadastrar(string $nome, string $email,string $senha): bool
    {
        
        $sql = $this->pdo->prepare ("SELECT id FROM tbl_professor WHERE email = :e");
        $sql->bindValue(":e",$email, PDO::PARAM_STR);
        $sql->execute();
        if($sql->rowCount() > 0){
            return false; 
        }else{  
        $sql = $this->pdo->prepare("INSERT INTO tbl_professor(nome, email, senha) VALUES (:n, :e, :s)"); 
            $sql->bindValue(":n",$nome, PDO::PARAM_STR);
            $sql->bindValue(":e",$email, PDO::PARAM_STR);
            $sql->bindValue(":s",md5($senha));
            $sql->execute();
            return true;
        }
    }  
    public function logar(string $email, string $senha): bool
    {

        $sql = $this->pdo->prepare ("SELECT id, nome from tbl_professor where email = :e AND senha = :s");
        $sql->bindValue(":e",$email, PDO::PARAM_STR); 
        $sql->bindValue(":s",md5($senha));
        $sql->execute();
        if($sql -> rowCount() > 0){
            $dado = $sql->fetch(); 
            session_start();
            $_SESSION['id'] = $dado ['id'];
            $_SESSION['professor'] = $dado ['nome'];
            return true; 
        }else{
            return false;
            
        }
    }

    public function buscardadosUmProfessor(int $idprofessor): array
    {
        $res = array();
        $cmd =$this->pdo->prepare("SELECT p.nome, p.email 
                             FROM tbl_professor AS p                             
                             WHERE p.id = :idPro;");
      
        $cmd->bindValue(":idPro", $idprofessor,  PDO::PARAM_INT);
        
        $cmd->execute();
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;        
    }

}
