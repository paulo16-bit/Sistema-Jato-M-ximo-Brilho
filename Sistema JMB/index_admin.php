<?php

session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'administrador') {
    header('Location: login.php');
    exit();
}

include 'modomonocromatico.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina inicial</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Sansation&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Smooch+Sans&display=swap" rel="stylesheet">
</head>

<body style="display: block;">
    <div class="header">
        <div class="home-titulo">
            <button class="sair-button hover" onclick="window.location.href='logout.php'">
                <strong>SAIR</strong>
            </button>
            <div class="vertical-line"></div>
            <p class="title">JATO MÁXIMO BRILHO</p>
        </div>
    </div>
    <div style="display: grid; grid-template-columns: 1fr 1fr;">
        <div class="navegation" style="color:black;">
            <img id="logo" src="img/logo.png" alt=""></img>
            <a href="mostrarTodos.php"><button>Monitoramento de Serviços</button></a>
            <a href="agendamentoServico.php"><button>Agendamento de Serviços</button></a>
            <a href="cadastroCliente.php"><button>Cadastro de Cliente</button></a>
            <a href="cadastrarVeiculo.php"><button>Cadastro de Veículo</button></a>
            <a href="historicoServico.php"><button>Histórico de Serviços</button></a>
            <a href="gerenciarUsuarios.php"><button>Gerenciar Usuários</button></a>
            <a href="gerenciarServicos.php"><button>Gerenciar Serviços</button></a>
        </div>
        <div class="container-main" style="min-width: 1020px; margin: 30px;">
            <div class="lista">
                <div class="cabecalho-lista">
                    <div class="coluna">Cliente</div>
                    <div class="coluna">Serviço</div>
                    <div class="coluna">Data</div>
                    <div class="coluna">Horário</div>
                    <div class="coluna">Fila</div>
                    <div class="coluna">Operador</div>
                    <div class="coluna">Status</div>
                </div>

                <?php
                include 'php_actions/connect_db.php';

                $query = "
                SELECT s.id_servicos, c.nome AS cliente, s.nome_servico, s.data, s.horario, s.fila, s.funcionario, s.stattus
                FROM servicos s
                JOIN cliente c ON s.cliente_id = c.id_cliente
                WHERE s.stattus IN ('Pendente', 'Em Andamento')
                ORDER BY s.data ASC, s.horario ASC;
            ";

                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($servico = mysqli_fetch_assoc($result)) {
                        // formatar data para dd/mm/yy.
                        $data = new DateTime($servico['data']);
                        $data = $data->format('d/m/y');
                        if ($servico['stattus'] == 'Pendente') {
                            $classeStatus = 'status-pendente';
                        } else {
                            $classeStatus = 'status-andamento';
                        }

                        echo "
                    <div class='linha-servico'>
                        <div class='coluna'>{$servico['cliente']}</div>
                        <div class='coluna'>{$servico['nome_servico']}</div>
                        <div class='coluna'>{$data}</div>
                        <div class='coluna'>{$servico['horario']}</div>
                        <div class='coluna'>{$servico['fila']}</div>
                        <div class='coluna'>{$servico['funcionario']}</div>
                        <div class='coluna $classeStatus'>{$servico['stattus']}</div>
                    </div>
                    ";
                    }
                } else {
                    echo "<div class='linha-servico'><div class='coluna' colspan='7'>Nenhum serviço</div></div>";
                }

                mysqli_close($conn);
                ?>
            </div>
        </div>
    </div>
</body>