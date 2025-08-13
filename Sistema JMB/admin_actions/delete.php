<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'administrador') {
    header('Location: ../login.php');
    exit();
}

if (!isset($_GET['id_usuarios'])) {
    header('Location: ../gerenciarUsuarios.php');
    exit();
}

$id = $_GET['id_usuarios'];

include '../php_actions/connect_db.php';

$sql = "DELETE FROM usuarios WHERE id_usuarios = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo'<script> alert("Usuário excluido com sucesso!")</script>';
} else {
    echo'<script> alert("Erro ao excluir usuário!")</script>';
}

$stmt->close();
$conn->close();
header('Location: ../gerenciarUsuarios.php');
exit();
?>