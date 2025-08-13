<?php
if (isset($_POST['cpf'])) {
    $cpf = $_POST['cpf'];

    include 'connect_db.php';
    
    $sql = "SELECT v.placa FROM veiculo v
            JOIN cliente c ON c.id_cliente = v.cliente_id
            WHERE c.cpf = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $placas = [];
    while ($row = $resultado->fetch_assoc()) {
        $placas[] = $row['placa'];
    }

    echo json_encode($placas);

    $stmt->close();
    $conn->close();
}
