<?php

session_start();
if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit;
}else{
    $idprofessor = $_SESSION['id'];
    $id_classe = $_SESSION['idClasseEspecifica'];
}

include 'header.php';
include 'menu.php';

require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Aluno.php';
$aluno = new Aluno;

$aluno->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");

if (isset($_POST['nome'])) {

    if (isset($_GET['id_up']) && !empty($_GET['id_up'])) {
        $id_update = addslashes($_GET['id_up']);
        $nome = addslashes($_POST['nome']);
        $sexualidade = addslashes($_POST['sexo']);
        $nascimento = addslashes($_POST['data_nascimento']);

        if (!empty($nome) && !empty($nascimento)) {

            $aluno->atualizarDadosAluno($id_update, $nome, $sexualidade, $nascimento, $id_classe, $idprofessor);
            header("location: aluno-cad.php");
        }
        //---------------------------CADASTRAR----------------------------
    } else {

        $nome = addslashes($_POST['nome']);
        $sexualidade = addslashes($_POST['sexo']);
        $data = addslashes($_POST['data_nascimento']);

        if (!empty($nome)) {
            if ($aluno->cadastrarAluno($nome, $sexualidade, $data, $id_classe, $idprofessor)) {
?>
                <div class="alert showAlert">
                    <span class="fas fa-exclamation-circle"></span>
                    <span class="msg">Aluno(a) cadastrado!</span>
                    <span class="close-btn">
                        <span class="fas fa-times"></span>
                    </span>
                </div>
            <?php

            }
        } else {

            ?>
            <div class="alert showAlert">
                <span class="fas fa-exclamation-circle"></span>
                <span class="msg">Preencha todos os campos!</span>
                <span class="close-btn">
                    <span class="fas fa-times"></span>
                </span>
            </div>
<?php
        }
    }
}
if (isset($_GET['id_up'])) {
    $id_update = addslashes($_GET['id_up']);
    $res = $aluno->buscarDadosUmAluno($id_update, $id_classe, $idprofessor);
}
?>
<main>
    <h1>Cadastro de Alunos</h1>
    <section class="container-menu-opcaoes">
        <ul class="menu-opcoes">
            <li class="opcoes"><a href="classe-especifica.php" class="icone-opcao"><i class="fa fa-reply" aria-hidden="true">Voltar</i></a></li>
        </ul>
    </section>
    <section class="wrapper-lista-classe">
        <div class="container-main">
            <section id="container-cadastroAluno">
                <form class="formulario-cadastro-classe" method="POST">
                    <div class="campoInputClasse">
                        <label for="tituloInputAnoClasse" class="tituloInputClasse">Nome</label>
                        <input type="text" name="nome" class="InputClasse" id="nome" value="<?php if (isset($res)) {
                                                                                                echo $res['nome'];
                                                                                            } ?>" required>
                    </div>
                    <div class="campoRedioButtonClasse">
                        <label class="tituloInputClasse">Sexo:</label>
                        <label class="tituloInputClasse">
                            <input type="radio" class="radioButtonClasse" name="sexo" value="F" <?php if ($res['sexualidade'] == "F") { ?> checked <?php } ?> required>Feminino
                        </label>
                        </label>
                        <label class="tituloInputClasse">
                            <input type="radio" class="radioButtonClasse" name="sexo" value="M" <?php if ($res['sexualidade'] == "M") { ?> checked <?php } ?> required>Masculino
                        </label>
                    </div>
                    <div class="campoInputClasse">
                        <label for="tituloInputAnoClasse" class="tituloInputClasse">Data de Nascimento: </label>
                        <input type="date" name="data_nascimento" class="InputClasse" id="data_nascimento" value="<?php echo $res['nascimento']; ?>" required>
                    </div>
                    <div class="conteudo-container-cadastroEscola">
                        <input class="btn-linkado" type="submit" value="<?php if (isset($res)) {
                                                                            echo "Atualizar";
                                                                        } else {
                                                                            echo "Cadastrar";
                                                                        } ?>">
                    </div>
                </form>
            </section>
            <section id="container-listagemEscola">
                <?php
                $dadosaluno = $aluno->buscarDadosAlunosDeUmaClasse($idprofessor, $id_classe);
                if (count($dadosaluno) > 0) {
                ?>
                    <table id="tabela-listagemEscola">
                        <tr id="conteudoLinha">
                            <td id="titulo">Nº</td>
                            <td colspan="2" id="titulo"> Listas de Alunos </td>
                            <td colspan="3" id="titulo"> </td>
                        </tr>
                        <?php
                        $c = 1;
                        for ($i = 0; $i < count($dadosaluno); $i++) {

                        ?><tr id="conteudoLinha"><?php
                                                                    foreach ($dadosaluno[$i] as $aluno => $valorAluno) {
                                                                        if ($aluno == "nome") {
                                                                    ?> <td id="conteudo"><?php echo $c; ?></td><?php
                                                                                            ?> <td id="conteudo"><?php echo $valorAluno; ?></td><?php
                                                                                                }
                                                                                            }
                                                                                            $c += 1;
                                                                                                    ?>
                                <td>
                                    <a class="link-tabela-listagemEscola" id="editar" href="aluno-cad.php?id_up=<?php echo $dadosaluno[$i]['id']; ?>"><i class="fa fa-pen" aria-hidden="true"></i></a>
                                    <a class="link-tabela-listagemEscola" id="excluir" href="aluno-cad.php?id_ex=<?php echo $dadosaluno[$i]['id']; ?>"><i class="fa fa-trash"></i></a>
                                </td>
                        <?php
                            echo "</tr>";
                        }
                    } else {
                        echo "<p> Nenhum aluno cadastrado! </p>";
                    }
                        ?>
                    </table>
            </section>
        </div>
</main>
<?php
if (isset($_GET['id_ex'])) {
    $id_aluno = addslashes($_GET['id_ex']);
    echo "<script language='javascript'>window.location.href='aluno-cad.php';</script>";
    $aluno->excluirAluno($id_aluno, $idprofessor);
}
?>
<?= include 'footer.php'; ?>