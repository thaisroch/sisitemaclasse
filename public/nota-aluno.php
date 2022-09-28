<?php
    session_start();
    if(!isset($_SESSION['id'])){
        header("location:index.php");
        exit;
    }else{
        $idprofessor = $_SESSION['id'];
        $id_classe = $_SESSION['idClasseEspecifica'];
    }

    require_once '..'. DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR .'models'. DIRECTORY_SEPARATOR .'Aluno.php';
    $aluno = new Aluno;
     
    require_once '..'. DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR .'models'. DIRECTORY_SEPARATOR .'Atividade.php';
    $atividade = new Atividade;

    $aluno->conectar("db_sistemadeclasse","localhost","root","DB_sistema*classe1");
    $atividade->conectar("db_sistemadeclasse","localhost","root","DB_sistema*classe1");

    include 'header.php';
    include 'menu.php';
?>
    <main>
        <h1>Lançar Nota dos Alunos das Ativides</h1>
        <section class="container-menu-opcaoes">
             <ul class="menu-opcoes">
                <li class="opcoes"><a href="classe-especifica.php" class="icone-opcao"><i class="fa fa-reply" aria-hidden="true">Voltar</i></a></li> 
                <li class="opcoes"><a href="classe-atividade.php" class="icone-opcao"><i class="fa fa fa-address-book">Cadastrar Atividade</i></a></li>
            </ul>   
        </section> 
        <section class="wrapper-lista">
                <div class="container-main">                                              
                    <section id="container-listagemEscola">         
                        <form method="POST" action="processando-notaAluno.php">                                 
                            <?php
                                $dadosAlunos = $aluno->buscarDadosAlunosDeUmaClasse($idprofessor, $id_classe);
                                if (count($dadosAlunos) > 0) { 
                                ?>  
                                    <table id="tabela-listagemEscola">
                                        <tr id="conteudoLinha">
                                            <td  colspan="2"  id="titulo">Seleciona a atividade: </td>
                                            <td id="conteudo"> 
                                                <select id="atividade"  name="atividade" required>
                                                    <option  class="tituloInputClasse"selected disabled value=""> -- Selecione --</option>
                                                    <?php
                                                    $dadosAtividade = $atividade->buscarAtividadeClasse($idprofessor, $id_classe);
                                                    for ($j = 0; $j < count($dadosAtividade); $j++) {                                
                                                        foreach ($dadosAtividade[$j] as $atividade => $valorAtividade) {
                                                            if(($atividade != "fk_tbl_professor_id4")&&($atividade != "valor")&&($atividade != "fk_tbl_classe_id2")){       
                                                            ?> <option value="
                                                                    <?php if($atividade == "id"){ echo $valorAtividade; } 
                                                                              else{ ?>"> <?php echo $valorAtividade; } ?>
                                                                </option> 
                                                            <?php  
                                                            }
                                                        }
                                                    }
                                                    ?>                                                   
                                                </select>     
                                            </td>        
                                        </tr>    
                                        <tr id="conteudoLinha">                                                
                                            <td  id="titulo">Nº</td>
                                            <td  id="titulo">Aluno</td>
                                            <td  id="titulo">Nota do Aluno</td>
                                        </tr>
                                    <?php
                                    $c = 1;
                                    for ($i = 0; $i < count($dadosAlunos); $i++) {
                                        ?><tr id="conteudoLinha"><?php
                                        foreach ($dadosAlunos[$i] as $aluno => $valorAluno) {
                                            if ($aluno == "nome")  {
                                            ?> 
                                                <td id="conteudo"><?php echo $c;?></td>
                                                <td id="conteudo"><?php echo $valorAluno;?></td>
                                                <td id="conteudo"><input type="text" id="nota" name="nota" onchange="this.value = this.value.replace(/,/g, '.')"/></td>
                                            <?php
                                            }                                                
                                        } 
                                        $c +=1;
                                    echo "</tr>";
                                } 
                            ?>
                            </table>       
                            <div class="conteudo-container-cadastroEscola">       
                                <input class="btn-linkado" type="submit" value="<?php if (isset($res)) { echo "Pesquisar"; } else { echo "Cadastrar";} ?>">
                            </div>
                        <?php 
                        }else
                            echo "<p>Nenhum aluno cadastrado nesta classe!</p>";
                        ?>
                        </form>
                    </section>
                </div>
            </section>        
    </main>           
    <?= include 'footer.php'; ?>