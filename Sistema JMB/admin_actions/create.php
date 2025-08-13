<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'administrador') {
    header('Location: ../login.php');
    exit();
}
include 'modomonocromatico.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $senha = md5($_POST['senha']);
    $status = $_POST['status'];
    $perfil = $_POST['perfil'];

    include '../php_actions/connect_db.php';

    // Verificar se o login já existe
    $sql = "SELECT id_usuarios FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo'<script> alert("Usuário já cadastrado!")</script>';
    } else {
        // Inserir novo usuário
        $sql = "INSERT INTO usuarios (nome, usuario, senha, status, nome_perfil) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nome, $usuario, $senha, $status, $perfil);

        if ($stmt->execute()) {
            $_SESSION['cadastro_sucesso'] = true;
        } else {
            echo'<script> alert("Erro ao adicionar usuário!")</script>';
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuário</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cadastro.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Sansation&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Smooch+Sans&display=swap" rel="stylesheet">
</head>

<body style="display: block;">
    <div class="header">
        <div class="home-titulo">
            <button class="sair-button hover" onclick="window.location.href='../gerenciarUsuarios.php'">
                <strong>VOLTAR</strong>
            </button>
            <div class="vertical-line"></div>
            <p class="title">Adicionar Usuário</p>
        </div>
    </div>

    <div class="container_usuario_create_edit">
        <form class="formulario" method="POST" style="display: block;">
            <div>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div><br>

            <div>
                <label for="usuario">Usuário:</label>
                <input type="text" id="usuario" name="usuario" required>
            </div><br>

            <div style="width: 100%;">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div><br>

            <div style="display: flex;">
                <div style="width: 90%;">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required style="width: 95%; height: 40px; border-radius: 50px;">
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>

                <div style="width: 90%;">
                    <label for="perfil">Perfil:</label>
                    <select id="perfil" name="perfil" required style="width: 95%; height: 40px; border-radius: 50px;">
                        <option value="administrador">Administrador</option>
                        <option value="colaborador">Colaborador</option>
                    </select>
                </div>
            </div><br>
            

            <div class="botao">
                <button type="submit">Adicionar</button>
            </div>
        </form>
    </div>

    <div id="popup" class="popup-container">
        <div class="popup-content">
            <div class="cabecalho-popup">
                <img src="../img/x.png" alt="botao fechar" class="btn-fechar hover" id="btn-fechar" onclick="fecharPopup()">
            </div>
            <strong>Usuário cadastrado com sucesso!</strong>
            <br><br>
            Deseja cadastrar um novo?
            <div class="popup-botoes">
                <button id="btn-nao" class="btn-popup vermelho hover" onclick="telaUsuario()">Não</button>
                <button id="btn-sim" class="btn-popup verde hover" onclick="fecharPopup()">Sim</button>
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