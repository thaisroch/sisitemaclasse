<?php

include "session_classe.php";

if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit;
} else {
    $id_classe = $_SESSION['idClasseEspecifica'];
    $idprofessor = $_SESSION['id'];
}

require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Classe.php';
$classe = new Classe;

require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Escola.php';
$escola = new Escola;

require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Aluno.php';
$aluno = new Aluno;

require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Professor.php';
$professor = new Professor;

$escola->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");
$classe->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");
$aluno->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");
$professor->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");

include 'header.php';
include 'menu.php';

?>
<main>
    <h1> <?php echo "Dados da Classe {$_SESSION['nomeClasseEspecifica']}"; ?></h1>

    <section class="container-menu-opcaoes">
        <ul class="menu-opcoes">
            <li class="opcoes"><a href="classe.php" class="icone-opcao"><i class="fa fa-reply" aria-hidden="true">Voltar</i></a></li>
            <li class="opcoes"><a href="classe-cad.php?id_up=<?php echo $id_classe ?>" class="icone-opcao"><i class="fa fa-pen" aria-hidden="true">Editar</i></a></li>
            <li class="opcoes"><a href="classe-especifica.php?id_ex=<?php echo $id_classe ?>" class="icone-opcao"><i class="fa fa-trash">Excluir</i></a></li>
            <li class="opcoes"><a href="aluno-cad.php" class="icone-opcao"><i class="fa fa-user-plus" aria-hidden="true">Turma</i></a></li>
            <li class="opcoes"><a href="relatorios.php" class="icone-opcao"><i class="far fa-folder-open">Relatórios</i></a></li>
            <li class="opcoes"><a href="frequencia-aluno.php" class="icone-opcao"><i class="fas fa-chalkboard-teacher">Lançar Frequencia</i></a></li>
            <li class="opcoes"><a href="nota-aluno.php" class="icone-opcao"><i class="fa fa-audio-description">Lançar nota</i></a></li>
        </ul>
    </section>
    <section class="wrapper-lista-classe">

        <?php
        $dadosClasse = $classe->buscardadosUmaClasse($id_classe, $idprofessor);
        if (count($dadosClasse) > 0) {
        ?>
            <table id="tabela-listagemEscola">
                <tr id="conteudoLinha">
                    <td id="titulo">Nome</td>
                    <td id="titulo">Ano</td>
                    <td id="titulo">Periodo</td>
                    <td id="titulo">Disciplina</td>
                    <td id="titulo">Professor</td>
                    <td id="titulo">Escola</td>
                    <td id="titulo"></td>
                </tr>
                <?php
                $c = 0;
                for ($i = 0; $i < count($dadosClasse); $i++) {
                ?><tr id="conteudoLinha"><?php
                    foreach ($dadosClasse[$i] as $k => $v) {
                        if ($k != "id") {
                            if ($k == "ano") {
                                $dia = $v;
                                ?> <td id="conteudo"><?php echo $dia; ?></td><?php
                            } else       
                            if ($k == "periodo") {
                                switch ($v) {
                                    case "m":
                                ?> <td id="conteudo"><?php echo "Manhã"; ?></td><?php
                                            break;
                                        case "t":
                                            ?> <td id="conteudo"><?php echo "Tarde"; ?></td><?php
                                            break;
                                        case "n":
                                            ?> <td id="conteudo"><?php echo "Noite"; ?></td><?php
                                            break;
                                    }
                            }else                     
                            if ($k == "fk_tbl_disciplina_id") {
                                $idprofessor = $_SESSION['id'];
                                $dadosDiscina = $classe->buscardadosUmaDisciplina($v, $idprofessor);
                                for ($j = 0; $j < count($dadosClasse); $j++) {
                                    foreach ($dadosDiscina[$j] as $disciplina => $valorDisciplina) {
                                        if ($disciplina == 'nome') {
                                        ?> <td id="conteudo"><?php echo $valorDisciplina; ?></td><?php
                                        }
                                    }
                                }
                            } else
                            if ($k == "fk_tbl_professor_id1") {
                                $dadosProfessor = $professor->buscardadosUmProfessor($v);
                                for ($j = 0; $j < count($dadosProfessor); $j++) {
                                    foreach ($dadosProfessor[$j] as $professor => $valorProfessor) {
                                        if ($professor == 'nome') {
                                            ?> <td id="conteudo"><?php echo $valorProfessor; ?></td><?php
                                        }
                                    }
                                }
                            } else
                            if ($k == "fk_tbl_escola_id") {
                                $idprofessor = $_SESSION['id'];
                                $dadosEscola = $escola->buscardadosUmaEscoladaClasse($v, $idprofessor);
                                for ($j = 0; $j < count($dadosEscola); $j++) {
                                    foreach ($dadosEscola[$j] as $escola => $valorEscola) {
                                        if ($escola == 'nome') {
                                            ?> <td id="conteudo"><?php echo $valorEscola; ?></td><?php
                                        }
                                    }
                                }
                            } else {
                                        ?> <td id="conteudo"><?php echo $v; ?></td><?php
                            }
                        }
                        $c += 1;
                    }                         

                    echo "</tr>";
                } 
                } else {
                    echo "<p> ERRO NOS DADOS</p>";
                }
                ?>
            </table>
    </section>
    <section id="container-listagemEscola">
        <?php
        $dadosAlunos = $aluno->buscarDadosAlunosDeUmaClasse($idprofessor, $id_classe);
        if (count($dadosAlunos) > 0) {
        ?>
            <table id="tabela-listagemEscola">
                <tr id="conteudoLinha">
                    <td id="titulo">Nº</td>
                    <td colspan="3" id="titulo">Lista de Alunos</td>
                </tr>
                <?php
                $c = 1;
                for ($i = 0; $i < count($dadosAlunos); $i++) {
                    ?><tr id="conteudoLinha"><?php
                    foreach ($dadosAlunos[$i] as $aluno => $valorAluno) {
                        if ($aluno == "nome") {
                            ?> <td id="conteudo"><?php echo $c; ?></td><?php
                            ?> <td id="conteudo"><?php echo $valorAluno; ?></td><?php
                        }
                    } 
                    $c += 1;
                    echo "</tr>";
                }
                ?>
            </table>
            <?php
        } else {
            echo "<p> Nenhum aluno cadastrado! </p>";
        }
        ?>
    </section>
</main>
<?php
if (isset($_GET['id_ex'])) {
    if (!$classe->excluirClasse($id_classe, $idprofessor)) {
        echo "<script language='javascript'>window.location.href='classe.php';</script>";
    } else {
        echo "<script language='javascript'>window.location.href='classe.php?erro=1';</script>";
    }
}

include 'footer.php'; ?>