<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit;
}
require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Classe.php';
$classe = new Classe;

require_once '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Escola.php';
$escola = new Escola;

$escola->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");
$classe->conectar("db_sistemadeclasse", "localhost", "root", "DB_sistema*classe1");

include 'header.php';
include 'menu.php';

?>
<main>
    <h1>Dados do Aluno</h1>
    <section class="wrapper-lista">
        <form class="formulario-cadastro-classe" action="processaCadClasse.php" method="POST">
            <div class="campoInputClasse" id="campoInputNomeClasse">
                <label for="tituloInputNomeClasse" class="tituloInputClasse">Nome: </label>
                <label for="tituloInputNomeClasse" class="tituloInputClasse">Thais Rocha</label>
            </div>
            <div class="campoInputClasse">
                <label for="tituloInputAnoClasse" class="tituloInputClasse">Classe:</label>
                <label for="tituloInputAnoClasse" class="tituloInputClasse">1 ano B</label>
            </div>

            <div class="campoInputClasse">
                <label for="tituloInputAnoClasse" class="tituloInputClasse">Nota Final:</label>
                <label for="tituloInputAnoClasse" class="tituloInputClasse">1.5</label>
            </div>

            <div class="campoInputClasse">
                <label for="tituloInputAnoClasse" class="tituloInputClasse">Quantidade de Atividade:</label>
                <label for="tituloInputAnoClasse" class="tituloInputClasse">7</label>
            </div>
            <div class="campoInputClasse" id="campoInputNomeClasse">
                <label for="tituloInputNomeClasse" class="tituloInputClasse">Nome da Atividade: </label>
                <label for="tituloInputNomeClasse" class="tituloInputClasse">Apresentação </label>
            </div>

            <div class="campoInputClasse">
                <label for="tituloInputAnoClasse" class="tituloInputClasse">Nota Atividade Apresentação:</label>
                <label for="tituloInputAnoClasse" class="tituloInputClasse">7.8</label>
            </div>

            <div class="campoInputClasse">
                <label for="tituloInputAnoClasse" class="tituloInputClasse">Frequência:</label>
                <label for="tituloInputAnoClasse" class="tituloInputClasse">9.0</label>
            </div>
            <div class="campoInputClasse">
                <label for="tituloInputAnoClasse" class="tituloInputClasse">Falta:</label>
                <label for="tituloInputAnoClasse" class="tituloInputClasse">1.0</label>
            </div>


            <div class="boxbtnGrande">

                <a href="aluno.php" class="linkBotao">Voltar</a>
                <input class="btn" type="submit" value="<?php if (isset($res)) {
                                                            echo "Atualizar";
                                                        } else {
                                                            echo "Editar";
                                                        } ?>">

            </div>

        </form>
    </section>
</main>
<?= include 'footer.php'; ?>