<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');


if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include 'modomonocromatico.php';

include 'php_actions/connect_db.php';

// Função para gerar os horários disponíveis
function gerarHorariosDisponiveis($data, $conn) {
    $horarios = [];
    $inicio = strtotime('08:00');
    $fim = strtotime('18:00');

    while ($inicio <= $fim) {
        $hora = date('H:i', $inicio);
        $sql = "SELECT id_servicos FROM servicos WHERE data = '$data' AND horario = '$hora'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $horarios[] = $hora;
        }

        $inicio = strtotime('+30 minutes', $inicio);
    }

    return $horarios;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $placa = $_POST['placa'];
    $funcionario = $_POST['funcionario'];
    $data = $_POST['data'];
    $fila = $_POST['fila'];
    $servico = $_POST['servico'];
    $horario = $_POST['horario'] ?? '';

    // Definir o horário com base no tipo de fila
    if ($fila === "Espera") {
        // Para serviços do tipo "Espera", usar o horário atual
        $horario = date('H:i'); // Captura o horário atual no formato HH:MM
    } else {
        // Para serviços do tipo "Agendamento", validar o horário selecionado
        $horariosDisponiveis = gerarHorariosDisponiveis($data, $conn);
        if (!in_array($horario, $horariosDisponiveis)) {
            $_SESSION['erro'] = "Horário indisponível. Por favor, selecione outro horário.";
            header('Location: agendamentoServico.php');
            exit();
        }
    }

    // Obtendo valor do servico
    $sql_valor = "SELECT valor FROM servicos_disponiveis WHERE nome_servico = ?";
    $stmt = $conn->prepare($sql_valor);
    $stmt->bind_param("s", $servico);
    $stmt->execute();
    $result = $stmt->get_result();
    $valor = $result->fetch_assoc()["valor"] ?? null;

    // Obtendo ID do cliente
    $sql = "SELECT id_cliente FROM cliente WHERE cpf = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $result = $stmt->get_result();
    $id_cliente = $result->fetch_assoc()["id_cliente"] ?? null;

    if (!$id_cliente) {
        die("<script>alert('Erro: Cliente não encontrado.');</script>");
    }

    // Obtendo ID do veículo
    $sql = "SELECT id_veiculo FROM veiculo WHERE placa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $placa);
    $stmt->execute();
    $result = $stmt->get_result();
    $id_veiculo = $result->fetch_assoc()["id_veiculo"] ?? null;

    if (!$id_veiculo) {
        die("<script>alert('Erro: Veículo não encontrado.');</script>");
    }

    // Inserindo o serviço
    $sql = "INSERT INTO servicos (
                cliente_id,
                veiculo_id, 
                funcionario, 
                data, 
                fila, 
                nome_servico, 
                horario,
                valor,
                stattus
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stattus = "Pendente";
    $stmt->bind_param("iisssssds", $id_cliente, $id_veiculo, $funcionario, $data, $fila, $servico, $horario, $valor, $stattus);

    if ($stmt->execute()) {
        $_SESSION['cadastro_sucesso'] = true;
    } else {
        echo "<script>alert('Erro ao cadastrar agendamento: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
// Obter a data selecionada (se houver)
$dataSelecionada = isset($_POST['data']) ? $_POST['data'] : date('Y-m-d');

// Gerar os horários disponíveis para a data selecionada
$horariosDisponiveis = gerarHorariosDisponiveis($dataSelecionada, $conn);
?>