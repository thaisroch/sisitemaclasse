<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit;
} else {
    $idprofessor = $_SESSION['id'];
    $id_classe =   $_SESSION['idClasseEspecifica'];
}

require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Aluno.php';
$aluno = new Aluno;

require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Frequencia.php';
$frequencia = new Frequencia;

$aluno->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");
$frequencia->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");

include 'header.php';
include 'menu.php';

?>
<main>
    <h1>Lançar Frequência dos Alunos na Aula</h1>
    <section class="container-menu-opcaoes">
        <ul class="menu-opcoes">
            <li class="opcoes"><a href="classe-especifica.php" class="icone-opcao"><i class="fa fa-reply" aria-hidden="true">Voltar</i></a></li>
        </ul>
    </section>
    <section class="wrapper-lista">
        <div class="container-main">
            <section id="container-listagemEscola">
                <form method="POST" action="processando-freqAluno.php">
                    <?php
                    $dadosAlunos = $aluno->buscarDadosAlunosDeUmaClasse($idprofessor, $id_classe);
                    if (count($dadosAlunos) > 0) {
                    ?>
                        <div class="campoInputClasse">
                            <label for="tituloInputAnoClasse" class="titu1loInputClasse">Dia: </label>
                            <input type="date" name="dia" class="InputClasse" id="dia" value="<?php echo $res['dia']; ?>" required>
                        </div>
                        <table id="tabela-listagemEscola">
                            <tr id="conteudoLinha">
                                <td colspan="2" id="titulo">Alunos</td>
                                <td id="titulo">Presença</td>
                                <td id="titulo">Falta</td>
                            </tr>
                            <?php
                            $c = 1;
                            for ($i = 0; $i < count($dadosAlunos); $i++) {
                                ?><tr id="conteudoLinha"><?php
                                    foreach ($dadosAlunos[$i] as $aluno => $valorAluno) {
                                        if ($aluno == "nome") {
                                            ?> 
                                            <td id="conteudo"><?php echo $c; ?></td>
                                            <td id="conteudo"><?php echo $valorAluno; ?></td>
                                            <td id="conteudo"><input type="checkbox" name="presenca[<?php echo $dadosAlunos[$i]['id']; ?>][]" id="nome" value="p" <?php if (isset($res)) {
                                                                                                                                                                        if ($res['situacao'] == 'p') {
                                                                                                                                                                            echo "checked";
                                                                                                                                                                        }
                                                                                                                                                                    } ?>></td>
                                            <td id="conteudo"><input type="checkbox" name="falta[<?php echo $dadosAlunos[$i]['id']; ?>][]" id="nome" value="f" <?php if (isset($res)) {
                                                                                                                                                                    if ($res['situacao'] == 'f') {
                                                                                                                                                                        echo "checked";
                                                                                                                                                                    }
                                                                                                                                                                } ?>></td>
                                            <?php

                                        }
                                    }
                                    $c += 1;
                                    ?>
                                <?php
                                echo "</tr>";
                            }
                            ?>
                        </table>
                        <div class="conteudo-container-cadastroEscola">
                            <input class="btn-linkado" type="submit" value="<?php if (isset($res)) {
                                                                                echo "Pesquisar";
                                                                            } else {
                                                                                echo "Cadastrar";
                                                                            } ?>">
                        </div>
                    <?php
                    } else
                        echo "<p>Nenhum aluno cadastrado nesta classe!</p>";
                    ?>
                </form>
            </section>
        </div>
    </section>
</main>
<?= include 'footer.php'; ?>