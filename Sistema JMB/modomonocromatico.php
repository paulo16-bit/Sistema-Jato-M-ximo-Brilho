<style>
    .menu-container {
        z-index: 1;
        position: fixed;
        top: 500px;
        left: 10px;
        background-color: #92bce0;
        padding: 2px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        width: 48px;
        transition: width 0.3s ease;
        overflow: hidden;
        opacity: 50%;
        cursor: pointer;
    }

    .menu-container img {
        width: 50px;
        height: auto;
        border-radius: 10px;
        transition: opacity 0.3s ease;
    }

    .menu-container:hover {
        opacity: 100%;
    }
 
    /* Modo monocromático */
    .monocromatico {
        filter: grayscale(100%);
    }
</style>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<div class="menu-container" id="botaoMonocromatico">
    <img src="img/monocromatico3.png" alt="monocromatico">
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
            let botao = document.getElementById("botaoMonocromatico");

            if (!botao) {
                console.error("Erro: Elemento #botaoMonocromatico não encontrado.");
                return;
            }

            // Aplica o modo monocromático se estiver salvo
            if (localStorage.getItem("monocromatico") === "sim") {
                document.body.classList.add("monocromatico");
            }

            // Ativa/desativa ao clicar
            botao.addEventListener("click", function () {
                let isActive = document.body.classList.toggle("monocromatico");
                let status = isActive ? "sim" : "nao";
                localStorage.setItem("monocromatico", status);
                console.log("Modo monocromático:", status);
            });
        });

        
</script>