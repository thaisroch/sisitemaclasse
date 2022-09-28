<?php
include "LimparClasseEspecifica.php";

if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit;
} else {
    $idprofessor = $_SESSION['id'];
}

require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Classe.php';
$classe = new Classe;

$classe->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");

include 'header.php';
include 'menu.php';
?>
<main>
    <h1>Classe</h1>
    <section class="container-menu-opcaoes">
        <ul class="menu-opcoes">
            <li class="opcoes"><a href="classe-cad.php" class="icone-opcao"><i class="fa fa-plus" aria-hidden="true">Criar classe</i></a></li>
        </ul>
    </section>
    <section class="wrapper-lista">
        <div class="container-main">
            <section id="container-listagemEscola">
                <?php
                $dadosClasse = $classe->buscarDadosClasse($idprofessor);
                if (count($dadosClasse) > 0) {
                ?>
                    <table id="tabela-listagemEscola">
                        <tr id="conteudoLinha">
                            <td id="titulo">Nº</td>
                            <td id="titulo">Listagem das Classe</td>
                            <td id="titulo">Data de Início</td>
                            <td id="titulo">Periodo</td>
                            <td id="titulo"></td>
                        </tr>
                        <?php
                        $c = 1;
                        for ($i = 0; $i < count($dadosClasse); $i++) {
                            ?><tr id="conteudoLinha"><?php
                            foreach ($dadosClasse[$i] as $k => $v) {
                                if ($k == "nome") {
                                    ?> <td id="conteudo"><?php echo $c; ?></td><?php
                                    ?> <td id="conteudo"><?php echo $v; ?></td><?php
                                }else
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
                                }
                            }
                            ?>
                                <td>
                                    <a class="link-tabela-listagemEscola" id="editar" href="classe-especifica.php?id_esp=<?php echo $dadosClasse[$i]['id'] ?>&nomeClasseEsp=<?php echo $dadosClasse[$i]['nome'] ?>"><i class="fa fa-eye"></i></a>
                                </td>
                        <?php
                            echo "</tr>";
                            $c += 1;
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
<?= include 'footer.php';

if (isset($_GET['erro'])) {

?>
    <div class="alert showAlert">
        <span class="fas fa-exclamation-circle"></span>
        <span class="msg">Esta classe não pode ser excluída, pois possui alunos!</span>
        <span class="close-btn">
            <span class="fas fa-times"></span>
        </span>
    </div>
<?php
}
