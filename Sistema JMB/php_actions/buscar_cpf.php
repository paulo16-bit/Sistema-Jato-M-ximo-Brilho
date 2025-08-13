<?php
if (isset($_POST['cpf'])) {
    $cpf = $_POST['cpf'];

    include 'connect_db.php';

    $sql = "SELECT 
                c.cpf, 
                c.nome, 
                c.telefone, 
                v.placa 
            FROM 
                cliente c
            JOIN 
                veiculo v ON c.id_cliente = v.cliente_id
            WHERE 
                c.cpf LIKE ? 
            GROUP BY 
                c.cpf, c.nome, c.telefone
            LIMIT 5;";
    $stmt = $conn->prepare($sql);
    $cpfLike = $cpf . "%";
    $stmt->bind_param("s", $cpfLike);
    $stmt->execute();
    $resultado = $stmt->get_result();


    if ($resultado->num_rows > 0) {
        while ($cliente = $resultado->fetch_assoc()) {
            echo "<div class='suggestion-item' 
                    data-cpf='" . $cliente['cpf'] . "' 
                    data-nome='" . $cliente['nome'] . "' 
                    data-telefone='" . $cliente['telefone'] . "'>
                    " . $cliente['cpf'] . " - " . $cliente['nome'] . "
                  </div>";
        }
    } else {
        echo "<div class='suggestion-item'>Nenhum cliente encontrado</div>";
    }

    $stmt->close();
    $conn->close();
}
