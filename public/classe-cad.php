<?php
session_start();
    if(!isset($_SESSION['id'])){
        header("location:index.php");
        exit;
    }else{
        $idprofessor = $_SESSION['id'];
    }

   include 'header.php'; 
   include 'menu.php';   

    require_once '..'. DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR .'models'. DIRECTORY_SEPARATOR .'Classe.php';
    $classe = new Classe;      
    
    $classe->conectar("db_sistemadeclasse","localhost","root","DB_sistema*classe1");       
         
    if(isset($_POST['nome'])){ // Ao clicar no botão para cadastrar ou editar
        
        // ------------------------EDITAR ------------------------------
         if(isset($_GET['id_up']) && !empty($_GET['id_up']))
         {
             $id_update = addslashes($_GET['id_up']); 
             $upNome = addslashes( $_POST['nome']);
             $upAno = addslashes( $_POST['ano']);
             $upPeriodo = addslashes( $_POST['classePeriodo']);
             $upDisciplinaClasse = addslashes( $_POST['disciplinaClasse']);    
             $upEscolaClasse = addslashes( $_POST['escolaClasse']);   

             if(!empty($upNome) && !empty($upAno))
             {
                //echo  "$id_update<br> $nome,<br> $ano,<br>  $periodo, <br> $disciplinaClasse,<br>   $escolaClasse,<br>  $idprofessor";             
                if(!$classe->atualizarDadosClasse($id_update, $upNome, $upAno, $upPeriodo, $upDisciplinaClasse, $upEscolaClasse, $idprofessor)){
                    
                }else{
                    header("location: classe.php");
                }
             }
         //---------------------------CADASTRAR----------------------------
         }else{
 
             $nome = addslashes( $_POST['nome']);
             $ano = addslashes( $_POST['ano']);
             $periodo = addslashes( $_POST['classePeriodo']);
             $disciplinaClasse = addslashes( $_POST['disciplinaClasse']);   
             $escolaClasse = addslashes( $_POST['escolaClasse']);   
 
             if(!empty($nome))
             {
                // echo  "$nome,<br> $ano,<br>  $periodo, <br> $disciplinaClasse,<br>   $escolaClasse,<br>  $idprofessor";             
                if($classe->cadastrarClasse($nome, $ano, $periodo, $disciplinaClasse,  $escolaClasse, $idprofessor))
                {
                    header("location: classe.php");
                }else{
                    ?>
                        <div class="alert showAlert">
                            <span class="fas fa-exclamation-circle"></span>
                            <span class="msg">Já existe classe com este nome!</span>
                            <span class="close-btn">
                                <span class="fas fa-times"></span>
                            </span>
                        </div>
                    <?php
                   
                }          
                            
             }else{
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

    if(isset($_GET['id_up'])){
        $id_classe =  $_SESSION['idClasseEspecifica'];             
        $idprofessor = $_SESSION['id'];
        $res = $classe->buscardadosUmaClasseEditar($id_classe, $idprofessor);
    }
    ?>
    <main>
        <h1>Cadastrar um nova classe</h1>
        <section class="container-menu-opcaoes">
            <ul class="menu-opcoes">
                <li class="opcoes"><a href="classe.php" class="icone-opcao"><i class="fa fa-reply" aria-hidden="true">Voltar </i></a></li>                
                <li class="opcoes"><a href="disciplina-cad.php" class="icone-opcao"><i class="fa fa-plus">Disciplina </i></a></li>
            </ul>   
        </section>
        <section class="wrapper-lista">
        <!-- Início do formulário -->
            <form class="formulario-cadastro-classe" method="POST">
                    <div class="campoInputClasse" id="campoInputNomeClasse">
                        <label for="tituloInputNomeClasse" class="tituloInputClasse">Nome</label>
                        <input type="text" class="InputClasse" name="nome" id="InputNomeClasse"  value="<?php if (isset($res)) { echo $res['nome']; } ?>"required>
                    </div>
                  
                    <div class="campoInputClasse">
                            <label for="tituloInputAnoClasse" class="tituloInputClasse">Data de Início: </label>
                            <input type="date" name="ano" class="InputClasse" id="ano"  value="<?php echo $res['ano'];?>"required>
                    </div> 
                <div class="campoRedioButtonClasse">
                    <label class="tituloInputClasse">Qual é o periodo?</label>
                    <label class="tituloInputClasse">
                        <input type="radio" class="radioButtonClasse" name="classePeriodo"  value="m" <?php if($res['periodo'] == "m"){ ?> checked <?php }?> required>Manhã
                    </label>
                    <label class="tituloInputClasse">
                        <input type="radio" class="radioButtonClasse" name="classePeriodo" value="t" <?php if($res['periodo'] == "t"){ ?> checked <?php }?> required>Tarde    
                    </label>
                    <label class="tituloInputClasse">
                        <input type="radio" class="radioButtonClasse" name="classePeriodo" value="n" <?php if($res['periodo'] == "n"){ ?> checked <?php }?> required>Noite
                    </label>
                </div>
                <div class="campoSelectEscolaClasse">
                    <label class="tituloInputClasse" for="disciplinaClasse">Disciplina</label>
                    <select id="disciplina" name="disciplinaClasse" >
                        <option  class="tituloInputClasse"selected value="
                        <?php 
                        if(isset($res)){
                        
                            $dadosDisciplinaUp =  $classe -> buscarDadosDisciplina($idprofessor);
                            for ($i = 0; $i < count($dadosDisciplinaUp); $i++) {                                
                                foreach ($dadosDisciplinaUp[$i] as $disciplina => $valordisciplina) {
                                    if ($dadosDisciplinaUp[$i]['id'] == $res['fk_tbl_disciplina_id']){
                                        echo $dadosDisciplinaUp[$i]['id'];   ?>"> <?php
                                        echo $dadosDisciplinaUp[$i]['nome']; ?></option> <?php                                                 
                                    }               
                                }   
                            }
                        }else{
                           ?>"> -- Selecione --</option><?php 
                        }                        
                  
                            $dadosDisciplina =  $classe -> buscarDadosDisciplina($idprofessor);
                            for ($i = 0; $i < count($dadosDisciplina); $i++) {                                
                                foreach ($dadosDisciplina[$i] as $disciplina => $valordisciplina) {                                              
                                    if($disciplina != "fk_tbl_professor_id2"){    
                                    ?> <option value="<?php if($disciplina == "id"){ echo $valordisciplina; } 
                                                             else{ ?>"> <?php echo $valordisciplina; } ?>
                                        </option>
                                    <?php
                                    } //fim do foreach
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="campoSelectEscolaClasse">
                    <label class="tituloInputClasse" for="escolaClasse">Escola</label>
                    <select id="escola"  name="escolaClasse" >
                    <option  class="tituloInputClasse"selected value="
                        <?php 
                        if(isset($res)){
                          
                            $dadosEscolaUp =  $classe -> buscarDadosEscola($idprofessor);
                            for ($i = 0; $i < count($dadosEscolaUp); $i++) {                                
                                foreach ($dadosEscolaUp[$i] as $escola => $valorescola) {
                                    if ($dadosEscolaUp[$i]['id'] == $res['fk_tbl_escola_id']){
                                        echo $dadosEscolaUp[$i]['id'];   ?>"> <?php
                                        echo $dadosEscolaUp[$i]['nome']; ?></option> <?php                                                 
                                    }               
                                }   
                            }
                        }else{
                           ?>"> -- Selecione --</option><?php 
                        }                       
                            $dadosEscola =  $classe -> buscarDadosEscola($idprofessor);
                            for ($i = 0; $i < count($dadosEscola); $i++) {                                
                                foreach ($dadosEscola[$i] as $escola => $valorescola) {
                                    if($escola != "email"){
                                        ?> <option value="<?php if($escola == "id"){ echo $valorescola; } 
                                                                else{ ?>"> <?php echo $valorescola; } ?>
                                            </option> 
                                        <?php  
                                    }
                                }
                            }//fim do foreach
                        ?>           
                    </select>                
                </div>             
                <div class="conteudo-container-cadastroEscola">       
                    <input class="btn-linkado" type="submit" value="<?php if (isset($res)) { echo "Atualizar"; } else { echo "Cadastrar";} ?>">
                </div>              
            </form>
        </section>
    </main>
    <?=include 'footer.php'; ?>