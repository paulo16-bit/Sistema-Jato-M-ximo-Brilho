<?php
include 'php_actions/connect_db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM servicos WHERE id_servicos = $id";
    mysqli_query($conn, $query);
}

mysqli_close($conn);

if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: mostrarTodos.php");
}
exit();
