<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'administrador') {
    header('Location: ../login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: ../gerenciarServicos.php');
    exit();
}

$id = $_GET['id'];

include '../php_actions/connect_db.php';

$sql = "DELETE FROM servicos_disponiveis WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo'<script> alert("Usuário excluido com sucesso!")</script>';
} else {
    echo'<script> alert("Erro ao excluir usuário!")</script>';
}

$stmt->close();
$conn->close();
header('Location: ../gerenciarServicos.php');
exit();
?>