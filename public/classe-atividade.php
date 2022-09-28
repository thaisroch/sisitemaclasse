<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit;
} else {
    $idprofessor = $_SESSION['id'];
    $id_classe = $_SESSION['idClasseEspecifica'];
}

require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Atividade.php';
$atividade = new Atividade;
$atividade->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");

include 'header.php';
include 'menu.php';

if (isset($_POST['nome'])) {

    //------------------------EDITAR ------------------------------
    if (isset($_GET['id_up']) && !empty($_GET['id_up'])) {
        $id_atividade = addslashes($_GET['id_up']);
        $upNome = addslashes($_POST['nome']);
        $upValor = addslashes($_POST['nota']);

        if (!empty($upNome)) {
            $atividade->atualizarAtividade($id_atividade, $upNome, $upValor, $idprofessor, $id_classe);
            header("location: classe-atividade.php");
        }
        //---------------------------CADASTRAR----------------------------
    } else {

        $nome = addslashes($_POST['nome']);
        $valor = addslashes($_POST['nota']);

        if (!empty($nome)) {
            if (!$atividade->cadastrarAtividade($nome, $valor,  $idprofessor, $id_classe)) {
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
if (isset($_GET['id_up'])) {
    $id_update = addslashes($_GET['id_up']);
    $res = $atividade->buscarAtividadeEspecificaClasse($id_update, $idprofessor);
}
?>
<main>
    <h1>Cadastrar uma nova atividade</h1>
    <section class="container-menu-opcaoes">
        <ul class="menu-opcoes">
            <li class="opcoes"><a href="nota-aluno.php" class="icone-opcao"><i class="fa fa-reply" aria-hidden="true">Voltar</i></a></li>
        </ul>
    </section>
    <section class="wrapper-lista">
        <div class="container-main">
            <section id="container-cadastroEscola">
                <form method="POST">

                    <div class="conteudo-container-cadastroEscola">
                        <label for="nome">Nome: </label>
                        <input type="text" name="nome" id="nome" value="<?php if (isset($res)) {
                                                                            echo $res['nome'];
                                                                        } ?>">
                    </div>
                    <div class="conteudo-container-cadastroEscola">
                        <label for="nota">Nota: </label>
                        <input type="text" id="nota" name="nota" value="<?php if (isset($res)) {
                                                                            echo $res['valor'];
                                                                        } ?>" onchange="this.value = this.value.replace(/,/g, '.')" <?php ?> />
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
                $dados = $atividade->buscarAtividadeClasse($idprofessor, $id_classe);
                if (count($dados) > 0) {
                ?>
                    <table id="tabela-listagemEscola">
                        <tr id="conteudoLinha">
                            <td id="titulo">Nº</td>
                            <td id="titulo">Nome</td>
                            <td id="titulo">Nota</td>
                            <td colspan="2" id="titulo"></td>
                        </tr>
                        <?php
                        $c = 1;
                        for ($i = 0; $i < count($dados); $i++) {
                        ?><tr id="conteudoLinha"><?php
                                                    foreach ($dados[$i] as $k => $v) {
                                                        if (($k != "id") and ($k != "fk_tbl_professor_id2")) {
                                                            if ($k == "nome") {
                                                    ?> <td id="conteudo"><?php echo $c; ?></td><?php
                                                                                                        }
                                                                                                            ?> <td id="conteudo"><?php echo $v; ?></td><?php
                                                                                                                                }
                                                                                                                            }
                                                                                                                                    ?>
                                <td>
                                    <a class="link-tabela-listagemEscola" id="editar" href="classe-atividade.php?id_up=<?php echo $dados[$i]['id']; ?>"><i class="fa fa-pen"></i></a>
                                    <a class="link-tabela-listagemEscola" id="excluir" href="classe-atividade.php?id_ex=<?php echo $dados[$i]['id']; ?>"><i class="fa fa-trash"></i></a>
                                </td>
                        <?php

                            $c += 1;
                            echo "</tr>";
                        }
                    } else {
                        echo "<p> Nenhuma atividade cadastrada! </p>";
                    }
                        ?>
                    </table>
            </section>
        </div>
    </section>
</main>
<?php
if (isset($_GET['id_ex'])) {
    $id_atividade = addslashes($_GET['id_ex']);
    echo "<script language='javascript'>window.location.href='classe-atividade.php';</script>";
    $atividade->excluirAtividade($id_atividade, $idprofessor);
}
?>
<?= include 'footer.php'; ?>