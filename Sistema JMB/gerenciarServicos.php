<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'administrador') {
    header('Location: login.php');
    exit();
}
include 'modomonocromatico.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Serviços</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cadastro.css">
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Sansation&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Smooch+Sans&display=swap" rel="stylesheet">
</head>

<body>
    <div class="header">
        <div class="home-titulo">
            <button class="home-button hover" onclick="window.location.href='index_admin.php'">
                <img src="img/casa.png" alt="imagem de casinha">
            </button>
            <div class="vertical-line"></div>
            <p class="title">Gerenciamento de Serviços</p>
        </div>
    </div>

    <div class="container_usuario">
        <button class="btn-adicionar" onclick="window.location.href='admin_actions/createServico.php'">Adicionar Serviço</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Serviço</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'php_actions/connect_db.php';
                $sql = "SELECT id, nome_servico, valor FROM servicos_disponiveis";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $valor = number_format($row['valor'], 2, ',', '');
                        $id = $row['id'];
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['nome_servico']}</td>
                                <td>{$valor}</td>
                                <td style='width: 150px;'>
                                    <button class='btn-editar' onclick=\"window.location.href='admin_actions/updateServico.php?id={$row['id']}'\">Editar</button>
                                    <button class='btn-excluir' onclick=\"mostrarPopup({$row['id']})\">Excluir</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Nenhum serviço encontrado.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Popup de confirmação -->
    <div id="popup" class="popup-container">
        <div class="popup-content">
            <div class="cabecalho-popup">
                <img src="img/x.png" alt="botao fechar" class="btn-fechar hover" onclick="fecharPopup()">
            </div>
            <p>Tem certeza que deseja excluir <br> o serviço?</p>
            <div class="popup-botoes">
                <button id="btn-nao" class="btn-popup vermelho hover" onclick="fecharPopup()">Não</button>
                <button id="btn-sim" class="btn-popup verde hover" onclick="excluirServico()">Sim</button>
            </div>
        </div>
    </div>

    <script>
        let servicoId;

        function mostrarPopup(id) {
            servicoId = id;
            document.getElementById("popup").style.display = "flex";
        }

        function fecharPopup() {
            document.getElementById("popup").style.display = "none";
        }

        function excluirServico() {
            window.location.href = `admin_actions/deleteServico.php?id=${servicoId}`;
        }
    </script>
</body>

</html>