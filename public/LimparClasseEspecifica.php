<?php
//Limpar SESSION CLASSE ESPECIFICO do sistema.
session_start();
if (isset($_SESSION['idClasseEspecifica'])) {
    unset($_SESSION['idClasseEspecifica']);
    unset($_SESSION['nomeClasseEspecifica']);
}
?>
<script>
    localStorage.removeItem(hasVisited);
</script>