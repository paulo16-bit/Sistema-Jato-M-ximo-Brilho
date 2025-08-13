<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
    $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);
    $placa = filter_input(INPUT_POST, 'placa', FILTER_SANITIZE_STRING);
    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);

    // Verificar se todos os campos foram preenchidos
    if (!empty($cpf) && !empty($modelo) && !empty($placa) && !empty($tipo)) {
        include 'php_actions/connect_db.php';

        $sql = "SELECT id_cliente FROM cliente WHERE cpf = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_cliente = $row['id_cliente'];
        } else {
            echo "Nenhum cliente encontrado com esse CPF.";
        }
        $query = "INSERT INTO veiculo (cliente_id, modelo, placa, tipo) 
        VALUES ('$id_cliente', '$modelo', '$placa', '$tipo')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Cadastro realizado com sucesso:');</script>";
            header('Location: agendamentoServico.php');
        } else {
            echo "<script>alert('Erro ao cadastrar: " . $conn->error . "');</script>";
        }

        $stmt->close();
        $conn->close();
    }
}
