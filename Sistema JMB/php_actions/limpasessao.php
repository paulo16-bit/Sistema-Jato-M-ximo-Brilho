<?php
session_start();

unset($_SESSION['cpf_cliente']);

if ($_SESSION['perfil'] == 'administrador') {
    header('Location: ../index_admin.php');
} else{
    header("Location: index.php");
}
exit();
?>