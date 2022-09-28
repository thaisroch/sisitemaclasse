<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit;
} else {
    $idprofessor = $_SESSION['id'];
}
require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Escola.php';
$escola = new Escola;

$escola->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");


if (isset($_POST['nome'])) {

    //------------------------EDITAR ------------------------------
    if (isset($_GET['id_up']) && !empty($_GET['id_up'])) {
        $id_update = addslashes($_GET['id_up']);
        $nome = addslashes($_POST['nome']);
        $email = addslashes($_POST['email']);

        if (!empty($nome) && !empty($email)) {
            $escola->atualizarDadosEscola($id_update, $nome, $email, $idprofessor);
            header("location: escola.php");
        }
        //---------------------------CADASTRAR----------------------------
    } else {

        $nome = addslashes($_POST['nome']);
        $email = addslashes($_POST['email']);

        if (!empty($nome) && !empty($email)) {
            if (!$escola->cadastrarEscola($nome, $email, $idprofessor)) {
?>
                <div class="alert showAlert">
                    <span class="fas fa-exclamation-circle"></span>
                    <span class="msg">Email já cadastrado!</span>
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
    $res = $escola->buscardadosUmaEscola($id_update, $idprofessor);
}

include 'header.php';
include 'menu.php';
?>
<main>
    <h1>Escolas</h1>
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
                        <label for="email">Email: </label>
                        <input type="email" name="email" id="email" value="<?php if (isset($res)) {
                                                                                echo $res['email'];
                                                                            } ?> ">
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
                $dados = $escola->buscarDadosEscola($idprofessor);
                if (count($dados) > 0) {
                ?>
                    <table id="tabela-listagemEscola">
                        <tr id="conteudoLinha">
                            <td id="titulo">Nome</td>
                            <td colspan="2" id="titulo">Email</td>
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
                                    <a class="link-tabela-listagemEscola" id="editar" href="escola.php?id_up=<?php echo $dados[$i]['id']; ?>"><i class="fa fa-pen" aria-hidden="true"></i></a>
                                    <a class="link-tabela-listagemEscola" id="excluir" href="escola.php?id_ex=<?php echo $dados[$i]['id']; ?>"><i class="fa fa-trash"></i></a>
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
    $id_escola = addslashes($_GET['id_ex']);
    if (!$escola->excluirEscola($id_escola, $idprofessor)) {
?>
        <div class="alert showAlert">
            <span class="fas fa-exclamation-circle"></span>
            <span class="msg">Escola associada a uma classe!</span>
            <span class="close-btn">
                <span class="fas fa-times"></span>
            </span>
        </div>
<?php

    } else {
        echo "<script language='javascript'>window.location.href='escola.php';</script>";
    }
}
?>
<?= include 'footer.php'; ?>