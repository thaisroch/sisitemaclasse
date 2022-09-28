<?php
    session_start();
    if(isset($_GET['id_esp'])){
        $_SESSION['nomeClasseEspecifica'] = $_GET['nomeClasseEsp'];
        $_SESSION['idClasseEspecifica'] = $_GET['id_esp'];
    }
