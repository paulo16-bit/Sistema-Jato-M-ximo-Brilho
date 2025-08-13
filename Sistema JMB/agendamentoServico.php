<?php
    include 'php_actions/processar_agendamento.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Serviço</title>
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
            <p class="title">Agendamento de Serviço</p>
        </div>
    </div>
    <div>
        <div class="container">
            <form class="agendamento" id="cadastroForm" method="POST">
                <div style="position: relative;">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" autocomplete="off" required>
                    <div id="cpf-suggestions" class="suggestions-box"></div>
                </div>

                <div>
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div>
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" placeholder="(  ) ____-____" required>
                </div>

                <div>
                    <label for="placa">Placa</label>
                    <select id="placa" name="placa" style="width: 210px; height: 40px; border-radius: 50px;">
                        <option value=""></option>
                    </select>
                    <button type="button" style="background-color: #4CAF50; border: none; border-radius: 10px; width: 35px; height: 35px; font-size: 1.3em;"
                        onclick="mostarPopupVeiculo()" title="Cadastrar novo veículo">
                        <span>+</span>
                    </button>
                </div>

                <div>
                    <label for="funcionario">Funcionário</label>
                    <input type="text" id="funcionario" name="funcionario" required>
                </div>

                <div>
                    <label for="data">Data</label>
                    <input type="date" id="data" name="data" min="<?php echo date("Y-m-d")?>" value="<?php echo $dataSelecionada; ?>" required>
                </div>

                <div>
                    <label for="fila">Fila</label>
                    <div class="fila">
                        <input type="radio" id="fila-espera" name="fila" value="Espera" onchange="alternarInputDropdown()"> Espera
                        <input type="radio" id="fila-agendamento" name="fila" value="Agendamento" checked onchange="alternarInputDropdown()"> Agendamento
                    </div>
                </div>

                <div>
                    <label for="servico">Serviço</label>
                    <select id="servico" name="servico" style="width: 240px; height: 40px; border-radius: 50px;">
                        <option value="">Selecione um serviço</option>
                        <?php
                        include 'php_actions/connect_db.php';
                        $sql = "SELECT nome_servico, valor FROM servicos_disponiveis";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['nome_servico']}'>{$row['nome_servico']} - R$ {$row['valor']}</option>";
                            }
                        } else {
                            echo "<option value=''>Nenhum serviço disponível</option>";
                        }

                        $conn->close();
                        ?>
                    </select>
                </div>

                <div id="dropdown-agendamento">
                    <label for="horario">Horário</label>
                    <select id="horario-agendamento" name="horario" style="width: 240px; height: 40px; border-radius: 50px;">
                        <option value="">Selecione um horário</option>
                        <?php foreach ($horariosDisponiveis as $hora): ?>
                            <option value="<?php echo $hora; ?>"><?php echo $hora; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="botao" style="grid-column: span 3; box-shadow: none;">
                    <button type="submit">Cadastrar</button>
                </div>
            </form>
        </div>
        <div id="popup" class="popup-container">
            <div class="popup-content">
                <div class="cabecalho-popup">
                    <img src="img/x.png" alt="botao fechar" class="btn-fechar hover" id="btn-fechar">
                </div>
                <strong>Agendamento feito com sucesso!</strong>
                <br>
                Deseja fazer um novo agendamento?
                <div class="popup-botoes">
                    <button id="btn-nao" class="btn-popup vermelho hover" onclick="window.location.href = 
                    '<?php echo ($_SESSION['perfil'] == 'administrador') ? 'index_admin.php' : 'index.php'; ?>'">Não</button>
                    <button id="btn-sim" class="btn-popup verde hover" onclick="agendamento()">Sim</button>
                </div>
            </div>
        </div>
        <div id="popup-veiculo" class="popup-container">
            <div style="width: 700px; height: auto;" class="popup-content">
                <div class="cabecalho-popup">
                    <img src="img/x.png" alt="botao fechar" class="btn-fechar hover" id="btn-fechar" onclick="fecharPopupVeiculo()">
                </div>
                <form class="formulario" id="cadastroForm" method="POST" action="popupCadastrarVeiculo.php">
                    <div>
                        <label for="cpf">CPF do Cliente</label>
                        <input type="text" id="cpf" name="cpf" value="<?php echo isset($_SESSION['cpf_cliente']) ? $_SESSION['cpf_cliente'] : ''; ?>" required>
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
                        <select id="tipo" name="tipo" style="width: 310px; height: 40px; border-radius: 50px;">
                            <option value="">Selecione um tipo</option>
                            <option value="Carro">Carro</option>
                            <option value="Moto">Moto</option>
                            <option value="Van">Van</option>
                            <option value="SUV">SUV</option>
                            <option value="Caminhonete">Caminhonete</option>
                        </select>
                    </div>

                    <div class="botao">
                        <button type="submit">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        <?php if (isset($_SESSION['cadastro_sucesso']) && $_SESSION['cadastro_sucesso']): ?>
            document.getElementById("popup").style.display = "flex";
            <?php unset($_SESSION['cadastro_sucesso']);
            ?>
        <?php endif; ?>
        // Buscar placas do cliente
        $(document).ready(function() {
            $("#cpf").on("input", function() {
                var cpf = $(this).val();
                if (cpf.length >= 3) {
                    $.ajax({
                        url: "php_actions/buscar_cpf.php",
                        type: "POST",
                        data: {
                            cpf: cpf
                        },
                        success: function(data) {
                            $("#cpf-suggestions").html(data).show();
                        }
                    });
                } else {
                    $("#cpf-suggestions").hide();
                }
            });

            $(document).on("click", ".suggestion-item", function() {
                var cpf = $(this).data("cpf");
                var nome = $(this).data("nome");
                var telefone = $(this).data("telefone");

                $("#cpf").val(cpf);
                $("#nome").val(nome);
                $("#telefone").val(telefone);
                $("#cpf-suggestions").hide();

                // Buscar placas do cliente
                $.ajax({
                    url: "php_actions/buscar_placas.php",
                    type: "POST",
                    data: {
                        cpf: cpf
                    },
                    dataType: "json",
                    success: function(placas) {
                        var placaDropdown = $("#placa");
                        placaDropdown.empty(); // Limpar opções anteriores
                        if (placas.length > 0) {
                            placas.forEach(function(placa) {
                                placaDropdown.append('<option value="' + placa + '">' + placa + '</option>');
                            });
                        } else {
                            placaDropdown.append('<option value="">Nenhuma placa cadastrada</option>');
                        }
                    }
                });
            });

            $(document).click(function(event) {
                if (!$(event.target).closest("#cpf, #cpf-suggestions").length) {
                    $("#cpf-suggestions").hide();
                }
            });
        });

        // Atualizar horários disponíveis ao mudar a data
        $(document).ready(function() {
            $("#data").on("change", function() {
                var data = $(this).val();

                // Mostra mensagem de carregamento
                $("#horario-agendamento").html("<option value=''>Carregando horários...</option>");

                // Faz uma requisição AJAX para buscar horários ocupados
                $.ajax({
                    url: "php_actions/buscar_horarios.php",
                    type: "GET",
                    data: {
                        data: data
                    },
                    dataType: "json",
                    success: function(horariosDisponiveis) {
                        var dropdown = $("#horario-agendamento");
                        dropdown.empty();
                        dropdown.append("<option value=''>Selecione um horário</option>");

                        horariosDisponiveis.forEach(function(hora) {
                            dropdown.append("<option value='" + hora + "'>" + hora + "</option>");
                        });
                    },
                    error: function() {
                        alert("Erro ao buscar horários disponíveis.");
                    }
                });
            });
        });

        // Funções para popups
        function fecharPopup() {
            document.getElementById("popup").style.display = "none";
        }

        function mostarPopupVeiculo() {
            document.getElementById("popup-veiculo").style.display = "flex";
        }

        function fecharPopupVeiculo() {
            document.getElementById("popup-veiculo").style.display = "none";
        }

        function agendamento() {
            fecharPopup();
            window.location.href = "agendamentoServico.php";
        }

        function alternarInputDropdown() {
            const filaEspera = document.getElementById("fila-espera");
            const horarioAgendamento = document.getElementById("horario-agendamento");

            if (filaEspera.checked) {
                horarioAgendamento.disabled = true; // Desabilita o campo de horário
            } else {
                horarioAgendamento.disabled = false; // Habilita o campo de horário
            }
        }
    </script>
</body>

</html>