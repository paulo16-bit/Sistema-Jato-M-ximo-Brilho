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
    <title>Gerenciamento de Usuários</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cadastro.css">
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Sansation&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Smooch+Sans&display=swap" rel="stylesheet">
    <style>
        
    </style>
</head>

<body>
    <div class="header">
        <div class="home-titulo">
            <button class="home-button hover" onclick="window.location.href='index_admin.php'">
                <img src="img/casa.png" alt="imagem de casinha">
            </button>
            <div class="vertical-line"></div>
            <p class="title">Gerenciamento de Usuários</p>
        </div>
    </div>

    <div class="container_usuario">
        <button class="btn-adicionar" onclick="window.location.href='admin_actions/create.php'">Adicionar Usuário</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Login</th>
                    <th>Status</th>
                    <th>Perfil</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'php_actions/connect_db.php';
                $sql = "SELECT id_usuarios, nome, usuario, status, nome_perfil FROM usuarios";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id_usuarios']}</td>
                                <td>{$row['nome']}</td>
                                <td>{$row['usuario']}</td>
                                <td>{$row['status']}</td>
                                <td>{$row['nome_perfil']}</td>
                                <td style='width: 150px;'>
                                    <button class='btn-editar' onclick=\"window.location.href='admin_actions/update.php?id_usuarios={$row['id_usuarios']}'\">Editar</button>
                                    <button class='btn-excluir' onclick=\"mostrarPopup({$row['id_usuarios']})\">Excluir</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Nenhum usuário encontrado.</td></tr>";
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
            <p>Tem certeza que deseja excluir <br> o Usuário?</p>
            <div class="popup-botoes">
                <button id="btn-nao" class="btn-popup vermelho hover" onclick="fecharPopup()">Não</button>
                <button id="btn-sim" class="btn-popup verde hover" onclick="excluirUsuario()">Sim</button>
            </div>
        </div>
    </div>

    <script>
        let usuarioId;

        function mostrarPopup(id) {
            usuarioId = id;
            document.getElementById("popup").style.display = "flex";
        }

        function fecharPopup() {
            document.getElementById("popup").style.display = "none";
        }

        function excluirUsuario() {
            window.location.href = `admin_actions/delete.php?id_usuarios=${usuarioId}`;
        }
    </script>
</body>

</html>