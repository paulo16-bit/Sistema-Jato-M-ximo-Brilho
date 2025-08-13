<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'administrador') {
    header('Location: ../login.php');
    exit();
}
include 'modomonocromatico.php';

if (!isset($_GET['id_usuarios'])) {
    header('Location: ../gerenciarUsuarios.php');
    exit();
}

$id = $_GET['id_usuarios'];

include '../php_actions/connect_db.php';

$sql = "SELECT nome, usuario, status, nome_perfil FROM usuarios WHERE id_usuarios = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($nome, $usuario, $status, $perfil);
$stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novoNome = $_POST['nome'];
    $novoUsuario = $_POST['usuario'];
    $novaSenha = $_POST['senha'];
    $novoStatus = $_POST['status'];
    $novoPerfil = $_POST['perfil'];

    // Atualizar usuário
    if (!empty($novaSenha)) {
        $novaSenha = md5($novaSenha);
        $sql = "UPDATE usuarios SET nome = ?, usuario = ?, senha = ?, status = ?, nome_perfil = ? WHERE id_usuarios = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $novoNome, $novoUsuario, $novaSenha, $novoStatus, $novoPerfil, $id);
    } else {
        $sql = "UPDATE usuarios SET nome = ?, usuario = ?, status = ?, nome_perfil = ? WHERE id_usuarios = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $novoNome, $novoUsuario, $novoStatus, $novoPerfil, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['cadastro_sucesso'] = true;
    } else {
        echo '<script> alert("Erro ao editar usuário!")</script>';
    }
}

$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cadastro.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Sansation&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Smooch+Sans&display=swap" rel="stylesheet">
</head>

<body>
    <div class="header">
        <div class="home-titulo">
            <button class="sair-button hover" onclick="window.location.href='../gerenciarUsuarios.php'">
                <strong>VOLTAR</strong>
            </button>
            <div class="vertical-line"></div>
            <p class="title">Editar Usuário</p>
        </div>
    </div>

    <div class="container_usuario_create_edit">
        <form class="formulario" method="POST" style="display: block;">
            <div>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>" required>
            </div><br>
            <div>
                <label for="usuario">Usuário:</label>
                <input type="text" id="usuario" name="usuario" value="<?php echo $usuario; ?>" required>
            </div><br>

            <div>
                <label for="senha">Nova Senha (deixe em branco para manter a atual):</label>
                <input type="password" id="senha" name="senha">
            </div><br>
            <div style="display: flex;">
                <div style="width: 90%;">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required style="width: 95%; height: 40px; border-radius: 50px;">
                        <option value="ativo" <?php echo ($status == 'ativo') ? 'selected' : ''; ?>>Ativo</option>
                        <option value="inativo" <?php echo ($status == 'inativo') ? 'selected' : ''; ?>>Inativo</option>
                    </select>
                </div> <br>

                <div style="width: 90%;">
                    <label for="perfil">Perfil:</label>
                    <select id="perfil" name="perfil" required style="width: 95%; height: 40px; border-radius: 50px;">
                        <option value="administrador" <?php echo ($perfil == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                        <option value="colaborador" <?php echo ($perfil == 'colaborador') ? 'selected' : ''; ?>>Colaborador</option>
                    </select>
                </div> 
            </div><br>
            <div class="botao">
                <button type="submit">Atualizar</button>
            </div>
            
        </form>
    </div>
    <div id="popup" class="popup-container">
        <div class="popup-content">
            <div class="cabecalho-popup">
                <img src="../img/x.png" alt="botao fechar" class="btn-fechar hover" id="btn-fechar" onclick="fecharPopup()">
            </div>
            <strong><br>Usuário editado com sucesso!</strong>
            <div class="popup-botoes">
                <button id="btn-sim" class="btn-popup azul hover" onclick="telaUsuario()">OK</button>
            </div>
        </div>
    </div>
    
    <script>
        <?php if (isset($_SESSION['cadastro_sucesso']) && $_SESSION['cadastro_sucesso']): ?>
            document.getElementById("popup").style.display = "flex";
            <?php unset($_SESSION['cadastro_sucesso']);
            ?>
        <?php endif; ?>

        function fecharPopup() {
            document.getElementById("popup").style.display = "none";
        }
        
        function telaUsuario() {
            fecharPopup();
            window.location.href = "../gerenciarUsuarios.php";
        }
    </script>
</body>

</html>