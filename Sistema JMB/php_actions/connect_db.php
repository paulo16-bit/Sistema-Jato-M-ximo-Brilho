<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "brilho_maximo";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }
?>