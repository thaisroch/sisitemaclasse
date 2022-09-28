<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit;
}else{
    $idprofessor = $_SESSION['id'];
}

include 'header.php';
include 'menu.php';

require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Aluno.php';
$aluno = new Aluno;

$aluno->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");

?>
<main>
    <h1>Alunos</h1>
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
                        <input class="btn-linkado" type="submit" value="Pesquisar">
                    </div>
                </form>
            </section>
            <section id="container-listagemEscola">
                <?php
                $dadosAlunos = $aluno->buscarDadosAlunos($idprofessor);
                if (count($dadosAlunos) > 0) {
                ?>
                    <table id="tabela-listagemEscola">
                        <tr id="conteudoLinha">
                            <td colspan="1" id="titulo">Nº</td>
                            <td colspan="1" id="titulo">Alunos</td>
                            <td colspan="1" id="titulo">Classe</td>
                            <td colspan="1" id="titulo"></td>
                        </tr>
                        <?php
                        $c = 1;
                        for ($i = 0; $i < count($dadosAlunos); $i++) {
                        ?><tr id="conteudoLinha"><?php
                                                                    foreach ($dadosAlunos[$i] as $k => $v) {
                                                                        if ($k == "nome") {
                                                                    ?> <td id="conteudo"><?php echo $c; ?></td><?php
                                                                                            ?> <td id="conteudo"><?php echo $v; ?></td><?php
                                                                                        }
                                                                                        if ($k == "fk_tbl_classe_id1") {
                                                                                            ?> <td id="conteudo"><?php echo $v; ?></td><?php
                                                                                        }
                                                                                    }
                                                                                    $c += 1;
                                                                                            ?>
                                <td>
                                    <a class="link-tabela-listagemEscola" id="editar" href="aluno-especifico.php?id_ex=<?php echo $dados[$i]['id']; ?>"><i class="fa fa-eye"></i></a>
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
    </section>
</main>
<?= include 'footer.php'; ?>