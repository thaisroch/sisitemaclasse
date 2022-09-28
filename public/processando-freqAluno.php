<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit;
} else {
    $idprofessor = $_SESSION['id'];
    $idclasse = $_SESSION['idClasseEspecifica'];
}

require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Frequencia.php';
$frequencia = new Frequencia;

$frequencia->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");


//------------------------EDITAR ------------------------------
if (isset($_GET['id_up']) && !empty($_GET['id_up'])) {
    $id_update = addslashes($_GET['id_up']);
    $nome = addslashes($_POST['nome']);
    $alunoId = addslashes($_POST['alunoId[]']);

    if (!empty($nome)) {
        $disciplina->atualizarDisicplina($id_update, $nome, $idprofessor);
        header("location: disciplina-cad.php");
    }
    //---------------------------CADASTRAR----------------------------
} else {

    $statusPresenteTurma = null;
    $statusFaltaTurma = null;

    $dia = addslashes($_POST['dia']);
    $statusPresenteTurma = ($_POST['presenca']);
    $statusFaltaTurma = ($_POST['falta']);

    //if ((isset($statusPresenteTurma)) or (isset($statusFaltaTurma))) {
        dd($_POST);

        for ($i = 0; $i < count($statusPresenteTurma); $i++) { // VAI PASSAR NAS QUANTIDADE DE LINHAS
            foreach($statusPresenteTurma[$i] as $status){ // 2array
                for ($j = 0; $j < count($status); $j++) { // VAI PASSAR NAS QUANTIDADE DE LINHAS
                    foreach($status[$j] as $info){
                        echo "aqui eu tenho o id do aluno";
                        for ($k = 0; $k < count($status); $k++) { // VAI PASSAR NAS QUANTIDADE DE LINHAS
                            foreach($status[$k] as $info){
                                echo "aqui eu tenho o p oou f";

                            }
                        }
                    }
                }
            }    
            
        }
    //}
}









function dd($param)
{
    echo '<pre>';
    var_dump($param);
    echo '<pre>';
    die();
}
?>