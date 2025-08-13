<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include 'modomonocromatico.php';

// Configurações de paginação
$itens_por_pagina = 10; // Quantidade de itens por página
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Obtém a página atual da URL (se não estiver definida, assume a página 1)
$offset = ($pagina_atual - 1) * $itens_por_pagina; // Cálculo do offset

include 'php_actions/connect_db.php';

if (mysqli_connect_error()) {
    die("Erro na conexão: " . mysqli_connect_error());
}

if (isset($_GET['data_inicial']) && isset($_GET['data_final'])) {
    $data_inicial = $_GET['data_inicial'];
    // formatar para yyyy-mm-dd
    $partes = explode('/', $data_inicial);
    if(count($partes) === 3){
        $data_inicial = '20' . $partes[2] . '-' . $partes[1] . '-' . $partes[0];
    }
    

    $data_final = $_GET['data_final'];
    // formatar para yyyy-mm-dd
    $partes = explode('/', $data_final);
    if(count($partes) === 3){
        $data_final = '20' . $partes[2] . '-' . $partes[1] . '-' . $partes[0];
    }
} else {
    $data_inicial = '';
    $data_final = '';
}

$query = "
    SELECT c.nome AS cliente, s.nome_servico AS servico, s.data, s.horario, v.placa AS veiculo, s.funcionario AS operador, s.valor
    FROM servicos s
    JOIN cliente c ON s.cliente_id = c.id_cliente
    JOIN veiculo v ON s.veiculo_id = v.id_veiculo
    WHERE s.stattus IN ('Concluído')
";

if (!empty($data_inicial) && !empty($data_final)) {
    $query .= " AND s.data BETWEEN '$data_inicial' AND '$data_final'";
}

$query .= " ORDER BY s.data DESC, s.horario DESC LIMIT $itens_por_pagina OFFSET $offset";

$result = mysqli_query($conn, $query);

// Consulta para contar o total de registros (para calcular o número de páginas)
$query_total = "SELECT COUNT(*) AS total FROM servicos s WHERE s.stattus IN ('Concluído')";
//verifica se tem datas filtradas, caso sim, acrescenta na consulta
if (!empty($data_inicial) && !empty($data_final)) {
    $query_total .= " AND s.data BETWEEN '$data_inicial' AND '$data_final'";
}

$result_total = mysqli_query($conn, $query_total);
$row_total = mysqli_fetch_assoc($result_total);  // Recupera o resultado da consulta como um array associativo
$total_itens = $row_total['total']; // A variável $total_itens armazena o número total de registros encontrados
$total_paginas = ceil($total_itens / $itens_por_pagina); // calcula o valor total de páginas dividindo pelos itens por pagina desejado e arredondando pra cima (ceil), garantindo que sempre terá página suficiente 
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Serviços</title>
    <link rel="stylesheet" href="css/style.css">
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
            <p class="title">Histórico de Serviços</p>
        </div>

        <div class="filtro-data">
            <form method="GET" action="">
                <div class="campo-data">
                    <label for="data_inicial">Filtrar por intervalo</label>
                    <input type="text" id="data_inicial" name="data_inicial" placeholder="dd/mm/aa" value="<?php echo isset($_GET['data_inicial']) ? $_GET['data_inicial'] : ''; ?>">
                    <span class="data"> a </span>
                    <input type="text" id="data_final" name="data_final" placeholder="dd/mm/aa" value="<?php echo isset($_GET['data_final']) ? $_GET['data_final'] : ''; ?>">
                    <button type="submit" class="buscar-button hover">Filtrar</button>
                    <button type="button" class="limpar-button hover" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>'">Limpar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="container-historico">
        <div class="lista" style="margin-right: 40px; margin-left: 40px; margin-bottom: 20px;">
            <div class="cabecalho-lista">
                <div class="coluna">Cliente</div>
                <div class="coluna">Serviço</div>
                <div class="coluna">Data</div>
                <div class="coluna">Horário</div>
                <div class="coluna">Veículo</div>
                <div class="coluna">Operador</div>
                <div class="coluna">Valor</div>
            </div>

            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($servico = mysqli_fetch_assoc($result)) {
                    // formatar data para dd/mm/yy.
                    $data = new DateTime($servico['data']);
                    $data = $data->format('d/m/y');
                    $valor = number_format($servico['valor'], 2, ',', ''); //formata valor com duas casas decimais
                    echo "
                        <div class='linha-servico'>
                            <div class='coluna'>{$servico['cliente']}</div>
                            <div class='coluna'>{$servico['servico']}</div>
                            <div class='coluna'>{$data}</div>
                            <div class='coluna'>{$servico['horario']}</div>
                            <div class='coluna'>{$servico['veiculo']}</div>
                            <div class='coluna'>{$servico['operador']}</div>
                            <div class='coluna'>R\${$valor}</div>
                        </div>
                    ";
                }
            } else {
                echo "<div class='linha-servico'><div class='coluna' colspan='7'>Nenhum serviço encontrado.</div></div>";
            }
            ?>
        </div>



        <div class="paginacao">
            <!-- Verifica se a página atual é maior que 1 para exibir o link de "Anterior" -->
            <?php if ($pagina_atual > 1) { ?>
                <a href="?pagina=<?php echo $pagina_atual - 1; ?>&data_inicial=<?php echo isset($_GET['data_inicial']) ? $_GET['data_inicial'] : ''; ?>&data_final=<?php echo isset($_GET['data_final']) ? $_GET['data_final'] : ''; ?>" class="botao-paginacao hover">Anterior</a>
            <?php } ?>

            <!-- Cria os links de páginas numeradas -->
            <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
                <!-- O link da página recebe a classe "ativo" se a página atual for igual ao número da página -->
                <a href="?pagina=<?php echo $i; ?>&data_inicial=<?php echo isset($_GET['data_inicial']) ? $_GET['data_inicial'] : ''; ?>&data_final=<?php echo isset($_GET['data_final']) ? $_GET['data_final'] : ''; ?>" class="botao-paginacao hover <?php echo ($i == $pagina_atual) ? 'ativo' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php } ?>

            <!-- Verifica se a página atual é menor que o total de páginas para exibir o link de "Próximo" -->
            <?php if ($pagina_atual < $total_paginas) { ?>
                <a href="?pagina=<?php echo $pagina_atual + 1; ?>&data_inicial=<?php echo isset($_GET['data_inicial']) ? $_GET['data_inicial'] : ''; ?>&data_final=<?php echo isset($_GET['data_final']) ? $_GET['data_final'] : ''; ?>" class="botao-paginacao hover">Próximo</a>
            <?php } ?>
    </div>

    </div>
</body>

</html>

<?php
mysqli_close($conn);
?>