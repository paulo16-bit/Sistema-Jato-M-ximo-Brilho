<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include 'modomonocromatico.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $complemento = $_POST['complemento'];

    include 'php_actions/connect_db.php';

    try {
        $sql = "INSERT INTO cliente (nome, cpf, email, telefone, endereco, complemento) 
                VALUES ('$nome', '$cpf', '$email', '$telefone', '$endereco', '$complemento')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['cadastro_sucesso'] = true;
            $_SESSION['cpf_cliente'] = $cpf;
        } else {
            throw new Exception("Erro ao cadastrar: " . mysqli_error($conn));
        }
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('Erro ao cadastrar: " . addslashes($e->getMessage()) . "');</script>";
    } catch (Exception $e) {
        echo "<script>alert('" . addslashes($e->getMessage()) . "');</script>";
    } finally {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cadastro.css">
    <link href="https://fonts.googleapis.com/css2?family=Sansation&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Smooch+Sans&display=swap" rel="stylesheet">
</head>

<body>
    <div class="header">
        <div class="home-titulo">
        <button class="home-button hover" onclick="window.location.href='<?php echo ($_SESSION['perfil'] == 'administrador') ? 'index_admin.php' : 'index.php'; ?>'">
                <img src="img/casa.png" alt="imagem de casinha">
            </button>
            <div class="vertical-line"></div>
            <p class="title">Cadastro de Cliente</p>
        </div>
    </div>
    <div>
        <div class="container">
            <form class="formulario" id="cadastroForm" method="POST">
                <div>
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div>
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" oninput="mascaraCPF(this)" maxlength="14" required>
                </div>

                <div>
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div>
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000" oninput="mascaraTelefone(this)" maxlength="15" required>
                </div>

                <div>
                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" required>
                </div>

                <div>
                    <label for="complemento">Complemento</label>
                    <input type="text" id="complemento" name="complemento">
                </div>

                <div class="botao" style="box-shadow: none;">
                    <button type="submit">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
    <div id="popup" class="popup-container">
        <div class="popup-content">
            <div class="cabecalho-popup">
                <img src="img/x.png" alt="botao fechar" class="btn-fechar hover" id="btn-fechar">
            </div>
            <strong>Cliente cadastrado com sucesso!</strong>
            <br><br>
            Deseja cadastrar um veículo?
            <div class="popup-botoes">
                <button id="btn-nao" class="btn-popup vermelho hover" onclick="telainicial()">Não</button>
                <button id="btn-sim" class="btn-popup verde hover" onclick="cadastrarVeiculo()">Sim</button>
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

        function telainicial() {
            fecharPopup();
            window.location.href = "index.php";
        }

        function cadastrarVeiculo() {
            fecharPopup();
            window.location.href = "cadastrarVeiculo.php";
        }

        function mascaraCPF(campo) {
            let valor = campo.value.replace(/\D/g, '');
            if (valor.length <= 11) {
                valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
                valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
                valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            campo.value = valor;
        }

        function mascaraTelefone(campo) {
    let valor = campo.value.replace(/\D/g, '');

    if (valor.length > 10) {
        valor = valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    }
    campo.value = valor;
}
    </script>

</body>

</html>