<?php
session_start();
require 'php_actions/connect_db.php'; // Arquivo de conexão com o banco

/* CASO O USUÁRIO TENTE SAIR E VOLTAR PELAS SETAS */
if (isset($_SESSION['usuario'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Busca o usuário no banco de dados
    $sql = "SELECT usuario, senha, nome_perfil, status FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $dados = $resultado->fetch_assoc();

        
        if ($dados['status'] === 'inativo') {
            $erro = "Seu acesso foi desativado. Contate o administrador.";
        }
        else if ($dados['senha'] === md5($senha)) {
            $_SESSION['usuario'] = $dados['usuario'];
            $_SESSION['perfil'] = $dados['nome_perfil'];

            // Redireciona conforme o perfil do usuário
            if ($dados['nome_perfil'] == "administrador") {
                header("Location: index_admin.php"); // Página do Administrador
            } else if($dados['nome_perfil'] == "colaborador"){
                header("Location: index.php"); // Página do Colaborador
            }
            exit();
        } else {
            $erro = "Usuário ou senha incorretos.";
        }
    } else {
        $erro = "Usuário ou senha incorretos.";
    }
}

include 'modomonocromatico.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles_login.css">
</head>
<body>

    <!-- Cabeçalho -->
    <header class="header">
        <img src="img/logolavajato.jpg" alt="Logo" class="logo">
        <div class="title-container">
            <hr>
            <h1>JATO MÁXIMO BRILHO</h1>
            <hr>
        </div>
    </header>

    <!-- Container do login -->
    <section class="container">
        <h2>LOGIN</h2>
        
        <form method="POST">
            <label for="usuario">Usuário</label>
            <input type="text" name="usuario" required>
            
            <label for="senha">Senha</label>
            <input type="password" name="senha" required>

            <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
            
            <div class="button-container">
                <button type="submit">Entrar</button>
            </div>
        </form>
    </section>

    <footer class="footer">
        <div></div>
        <div></div>
    </footer>

    <script>
    
    document.addEventListener("DOMContentLoaded", function () {
        let erro = document.querySelector(".erro");
        if (erro) {
            document.querySelector("input[name='usuario']").value = "";
            document.querySelector("input[name='senha']").value = "";
        }
    });
    </script>

</body>
</html>