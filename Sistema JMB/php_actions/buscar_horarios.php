<?php
include 'connect_db.php';

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

// Obter a data enviada via AJAX
if (isset($_GET['data'])) {
    $dataSelecionada = $_GET['data'];
    $horariosDisponiveis = gerarHorariosDisponiveis($dataSelecionada, $conn);

    // Retornar os horários em formato JSON
    echo json_encode($horariosDisponiveis);
}

$conn->close();
?>