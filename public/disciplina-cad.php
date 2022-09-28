<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit;
} else {
    $idprofessor = $_SESSION['id'];
}

require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Disciplina.php';
$disciplina = new Disciplina;

$disciplina->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");

include 'header.php';
include 'menu.php';

if (isset($_POST['nome'])) {

    //------------------------EDITAR ------------------------------
    if (isset($_GET['id_up']) && !empty($_GET['id_up'])) {

        if (!empty($nome) && !empty($email)) {
        }
        //---------------------------CADASTRAR----------------------------
    } else {

        $nome = addslashes($_POST['nome']);

        if (!empty($nome)) {
            if (!$disciplina->cadastrarDisciplina($nome, $idprofessor)) {
?>
                <div class="alert showAlert">
                    <span class="fas fa-exclamation-circle"></span>
                    <span class="msg">Disciplina já cadastrada!</span>
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
?>
<main>
    <h1>Cadastrar uma disciplina</h1>
    <section class="container-menu-opcaoes">
        <ul class="menu-opcoes">
            <li class="opcoes"><a href="classe-cad.php" class="icone-opcao"><i class="fa fa-reply" aria-hidden="true">Voltar</i></a></li>
        </ul>
    </section>
    <section class="wrapper-lista">
        <div class="container-main">
            <section id="container-cadastroEscola">

                <form method="POST">
                    <div class="conteudo-container-cadastroEscola">
                        <label for="nome">Nome: </label>
                        <input type="nome" name="nome" id="nome" value="<?php if (isset($res)) {
                                                                            echo $res['nome'];
                                                                        } ?>">
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
                $dados = $disciplina->buscarDadosDisciplina($idprofessor);
                if (count($dados) > 0) {
                ?>
                    <table id="tabela-listagemEscola">
                        <tr id="conteudoLinha">
                            <td colspan="2" id="titulo">Nome</td>
                        </tr>
                        <?php
                        for ($i = 0; $i < count($dados); $i++) {
                        ?><tr id="conteudoLinha"><?php
                                                                    foreach ($dados[$i] as $k => $v) {
                                                                        if (($k != "id") and ($k != "fk_tbl_professor_id")) {
                                                                    ?> <td id="conteudo"><?php echo $v; ?></td><?php
                                                                                        }
                                                                                    }
                                                                                            ?>
                                <td>
                                    <a class="link-tabela-listagemEscola" id="editar" href="disciplina-cad?id_up=<?php echo $dados[$i]['id']; ?>"><i class="fa fa-pen" aria-hidden="true"></i></a>
                                    <a class="link-tabela-listagemEscola" id="excluir" href="disciplina-cad.php?id_ex=<?php echo $dados[$i]['id']; ?>"><i class="fa fa-trash"></i></a>
                                </td>
                        <?php
                            echo "</tr>";
                        }
                    } else {
                        echo "<p> Nenhuma Escola cadastrada! </p>";
                    }
                        ?>
                    </table>
            </section>
        </div>
    </section>
</main>
<?php
if (isset($_GET['id_ex'])) {
    $id_disciplina = addslashes($_GET['id_ex']);
    if (!$disciplina->excluirDisciplina($id_disciplina, $idprofessor)) {
?>
        <div class="alert showAlert">
            <span class="fas fa-exclamation-circle"></span>
            <span class="msg">Disciplina associada a uma classe!</span>
            <span class="close-btn">
                <span class="fas fa-times"></span>
            </span>
        </div>
<?php

    } else {
        echo "<script language='javascript'>window.location.href='disciplina-cad.php';</script>";
    }
}
?>
<?= include 'footer.php'; ?>