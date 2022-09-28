<?php

$host = "localhost";
$usuario = "root";
$senha = "DB_sistema*classe1'";
$banco = "db_sistemadeclasse";

    try{
        $pdo = new pdo("mysql:host=$host.dbname=" . $banco, $usuario, $senha);
            echo "conexão feita com sucesso.";
    }catch(PDOException $e){
        echo "Erro: Conexão". $e->getMessage();
    }
        
    



