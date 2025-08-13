<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include 'modomonocromatico.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'];
    $modelo = $_POST['modelo'];
    $placa = $_POST['placa'];
    $tipo = $_POST['tipo'];

    include 'php_actions/connect_db.php';

    try {
        // Verifica se o cliente existe
        $sql = "SELECT id_cliente FROM cliente WHERE cpf = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_cliente = $row['id_cliente'];
        } else {
            throw new Exception("Nenhum cliente encontrado com esse CPF.");
        }

        // Insere o veículo
        $query = "INSERT INTO veiculo (cliente_id, modelo, placa, tipo) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $id_cliente, $modelo, $placa, $tipo);

        if ($stmt->execute()) {
            $_SESSION['cadastro_sucesso'] = true;
        } else {
            throw new Exception("Erro ao cadastrar veículo: " . $stmt->error);
        }
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('Erro ao cadastrar: " . addslashes($e->getMessage()) . "');</script>";
    } catch (Exception $e) {
        echo "<script>alert('" . addslashes($e->getMessage()) . "');</script>";
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Veículo</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cadastro.css">
    <link href="https://fonts.googleapis.com/css2?family=Sansation&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Smooch+Sans&display=swap" rel="stylesheet">
</head>

<body>
    <div class="header">
        <div class="home-titulo">
            <button class="home-button hover" onclick="window.location.href = 'php_actions/limpasessao.php'">
                <img src="img/casa.png" alt="imagem de casinha">
            </button>
            <div class="vertical-line"></div>
            <p class="title">Cadastro de Veículo</p>
        </div>
    </div>
    <div>
        <div class="container">
            <form class="formulario" id="cadastroForm" method="POST">
                <div>
                    <label for="cpf">CPF do Cliente</label>
                    <input type="text" id="cpf" name="cpf" oninput="mascaraCPF(this)" maxlength="14" required 
                    value="<?php echo isset($_SESSION['cpf_cliente']) ? $_SESSION['cpf_cliente'] : ''; ?>" >
                </div>

                <div>
                    <label for="modelo">Modelo</label>
                    <input type="text" id="modelo" name="modelo" required>
                </div>

                <div>
                    <label for="placa">Placa</label>
                    <input type="text" id="placa" name="placa" required>
                </div>

                <div>
                    <label for="tipo">Tipo de Veículo</label>
                    <select id="tipo" name="tipo" style="width: 380px; height: 40px; border-radius: 50px; font-family: 'Sansation', sans-serif;">
                        <option value="">Selecione um tipo</option>
                        <option value="Carro">Carro</option>
                        <option value="Moto">Moto</option>
                        <option value="Van">Van</option>
                        <option value="SUV">SUV</option>
                        <option value="Caminhonete">Caminhonete</option>
                    </select>
                </div>

                <div class="botao" style="box-shadow: none;">
                    <button type="submit">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
    <div id="popup-veiculo" class="popup-container">
        <div class="popup-content">
            <div class="cabecalho-popup">
                <img src="img/x.png" alt="botao fechar" class="btn-fechar hover" id="btn-fechar">
            </div>
            <strong>Veiculo cadastrado com sucesso!</strong>
                <br><br>
                Deseja cadastrar um novo veículo?
            <div class="popup-botoes">
                <button id="btn-nao" class="btn-popup vermelho hover" onclick="window.location.href = 'php_actions/limpasessao.php'">Não</button>
                <button id="btn-sim" class="btn-popup verde hover" onclick="cadastrarVeiculo()">Sim</button>
            </div>
        </div>
    </div>

    <script>
        <?php if (isset($_SESSION['cadastro_sucesso']) && $_SESSION['cadastro_sucesso']): ?>
            document.getElementById("popup-veiculo").style.display = "flex";
            <?php unset($_SESSION['cadastro_sucesso']);
            ?>
        <?php endif; ?>

        function fecharPopup() {
            document.getElementById("popup-veiculo").style.display = "none";
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
    </script>

</body>

</html>