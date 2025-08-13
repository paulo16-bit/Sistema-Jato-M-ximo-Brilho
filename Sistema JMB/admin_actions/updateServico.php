<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'administrador') {
    header('Location: ../login.php');
    exit();
}
include 'modomonocromatico.php';

if (!isset($_GET['id'])) {
    header('Location: ../gerenciarServicos.php');
    exit();
}

$id = $_GET['id'];

include '../php_actions/connect_db.php';

$sql = "SELECT nome_servico, valor FROM servicos_disponiveis WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($nomeServico, $valor);
$stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novoNomeServico = $_POST['nome_servico'];
    $novoValor = $_POST['valor'];

    $sql = "UPDATE servicos_disponiveis SET nome_servico = ?, valor = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdi", $novoNomeServico, $novoValor, $id);

    if ($stmt->execute()) {
        $_SESSION['cadastro_sucesso'] = true;
    } else {
        echo'<script> alert("Erro ao atualizar serviço!")</script>';
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
    <title>Editar Serviço</title>
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
            <p class="title">Editar Serviço</p>
        </div>
    </div>

    <div class="container_usuario_create_edit">
        <form class="formulario" method="POST" style="display: block;">
            <div>
                <label for="nome_servico">Nome do Serviço:</label>
                <input type="text" id="nome_servico" name="nome_servico" value="<?php echo $nomeServico; ?>" required>
            </div><br>

            <div>
                <label for="valor">Preço:</label>
                <input type="number" id="valor" name="valor" step="0.01" min="0" value="<?php echo $valor; ?>" required>
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
            <strong><br>Serviço editado com sucesso!</strong>
            <div class="popup-botoes">
                <button id="btn-sim" class="btn-popup azul hover" onclick="telaServiço()">OK</button>
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