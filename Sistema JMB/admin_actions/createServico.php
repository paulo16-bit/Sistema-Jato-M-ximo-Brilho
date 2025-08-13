<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'administrador') {
    header('Location: ../login.php');
    exit();
}
include 'modomonocromatico.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeServico = $_POST['nome_servico'];
    $valor = $_POST['valor'];

    include '../php_actions/connect_db.php';

    // Inserir novo serviço
    $sql = "INSERT INTO servicos_disponiveis (nome_servico, valor) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sd", $nomeServico, $valor);

    if ($stmt->execute()) {
        $_SESSION['cadastro_sucesso'] = true;
    } else {
        echo'<script> alert("Erro ao adicionar serviço!")</script>';
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
    <title>Adicionar Serviço</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cadastro.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Sansation&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Smooch+Sans&display=swap" rel="stylesheet">
</head>

<body>
    <div class="header">
        <div class="home-titulo">
            <button class="sair-button hover" onclick="window.location.href='../gerenciarServicos.php'">
                <strong>VOLTAR</strong>
            </button>
            <div class="vertical-line"></div>
            <p class="title">Adicionar Serviço</p>
        </div>
    </div>

    <div class="container_usuario_create_edit">
        <form class="formulario" method="POST" style="display: block;">
            <div>
                <label for="nome_servico">Nome do Serviço:</label>
                <input type="text" id="nome_servico" name="nome_servico" required>
            </div><br>

            <div>
                <label for="valor">Preço:</label>
                <input type="number" id="valor" name="valor" step="0.01" min="0" placeholder="0,00" required>
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
            <strong>Serviço cadastrado com sucesso!</strong>
            <br><br>
            Deseja cadastrar um novo?
            <div class="popup-botoes">
                <button id="btn-nao" class="btn-popup vermelho hover" onclick="telaServiço()">Não</button>
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

        function telaServiço() {
            fecharPopup();
            window.location.href = "../gerenciarServicos.php";
        }
    </script>
</body>

</html>