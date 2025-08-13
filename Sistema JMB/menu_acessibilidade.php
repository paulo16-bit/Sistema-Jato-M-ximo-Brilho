<?php
// menu_acessibilidade.php
?>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .menu-container {
        z-index: 1;
        position: fixed;
        top: 180px;
        left: 20px;
        background-color: #92bce0;
        padding: 2px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        width: 52px;
        transition: width 0.3s ease;
        overflow: hidden;
        opacity: 50%;
    }

    .menu-container img {
        width: 50px;
        height: auto;
        border-radius: 10px;
        transition: opacity 0.3s ease;
    }


    .ajuste-tela {
        opacity: 0;
        width: 50px;
        margin-left: 10px;
    }


    .menu-container:hover {
        width: 100px;
        opacity: 100%;
    }

    .menu-container .ajuste-tela {
        opacity: 1;
        width: 30px;
    }


    .menu-acessibilidade {
        z-index: 2;
        position: fixed;
        top: 80px;
        left: 20px;
        width: 300px;
        background: #4f6980;
        color: white;
        padding: 10px;
        padding-top: 30px;
        border-radius: 10px;
        display: none;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
    }

    .menu-acessibilidade h2 {
        background: #92bce0;
        padding: 10px;
        margin-left: 5px;
        margin-right: 5px;
        margin-bottom: 0px;
        margin-top: 2px;
        font-size: 18px;
        text-align: center;
        border-radius: 5px;
    }

    /* Botão de fechar */
    .fechar {
        position: absolute;
        top: 8px;
        right: 10px;
        font-size: 18px;
        cursor: pointer;
    }

    /* Opções do menu */
    .opcao {
        background: white;
        color: black;
        padding: 10px;
        margin: 5px;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background 0.3s;
    }

    .opcao:hover {
        background: #ddd;
    }

    /* Ícone de check */
    .check {
        display: none;
        color: green;
        font-weight: bold;
        position: absolute;
        top: 5px;
        right: 8px;
        font-size: 18px;
    }

    .opcao.ativo .check {
        display: inline;
    }

    /* Container dos botões de acessibilidade */
    .botoes-acessibilidade {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding: 10px;
    }

    /* Estilo dos botões */
    .botao {
        background: white;
        color: black;
        padding: 10px;
        border-radius: 10px;
        cursor: pointer;
        text-align: center;
        font-size: 16px;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease;
        position: relative;
    }

    .botao:hover {
        transform: scale(1.05);
    }

    .botao img {
        width: 50px;
        height: 50px;
        display: block;
        margin: 0 auto 5px;
        padding-bottom: 10px;
    }

    .botao img.longa {
        width: 80px;
        height: 50px;
        display: block;
        margin: 0 auto 5px;
    }

    .fonte-legivel {
        h1,
        p,
        span,
        label,
        input, button, div, a {
            font-family: Arial, Helvetica, sans-serif;
        }

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
    <title>Login</title>
    <!-- <link rel="stylesheet" href="styles_login.css"> -->
</head>
<div class="menu-container">
    <img src="img/icon-menu.png" alt="Acessibilidade">
    <img src="img/icon-hover.png" alt="Ajuste de Tela" class="ajuste-tela">
</div>

<!-- Menu de acessibilidade -->
<div class="menu-acessibilidade">
    <span class="fechar">X</span>
    <h2>ACESSIBILIDADE</h2>
    <div class="botoes-acessibilidade">
        <div class="botao" onclick="toggleOpcao(this, 'fonte-legivel')">
            <span class="check">✔</span>
            <img class="longa" src="img/fonte-legivel.png" alt="Fonte legível">
            <span>Fonte legível</span>
        </div>
        <div class="botao" onclick="toggleOpcao(this, 'monocromatico')">
            <span class="check">✔</span>
            <img class="longa" src="img/monocromatico.png" alt="Monocromático">
            <span>Monocromático</span>
        </div>
    </div>
</div>

<script>
    let menuToggle = document.querySelector('.menu-container');
    let menu = document.querySelector('.menu-acessibilidade');
    let fechar = document.querySelector('.fechar');

    // Exibe o menu ao clicar no botão
    menuToggle.addEventListener('click', function() {
        menu.style.display = 'block';
    });

    // Fecha ao clicar no "X"
    fechar.addEventListener('click', function() {
        menu.style.display = 'none';
    });

    // Fecha ao clicar fora do menu
    document.addEventListener('click', function(event) {
        let dentroDoMenu = menu.contains(event.target);
        let clicouNoBotao = menuToggle && menuToggle.contains(event.target);

        if (!dentroDoMenu && !clicouNoBotao) {
            menu.style.display = 'none';
        }
    });

    function toggleOpcao(element, classeCSS) {
        element.classList.toggle('ativo');
        document.body.classList.toggle(classeCSS);

        let check = element.querySelector('.check');
        check.style.display = element.classList.contains('ativo') ? 'inline' : 'none';
    }
</script>