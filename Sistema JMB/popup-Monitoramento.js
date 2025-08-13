let servico = null;

function mostrarPopup(popupId, id) {
    servico = id;
    document.getElementById(popupId).style.display = 'flex';
}

function esconderPopup(popupId) {
    document.getElementById(popupId).style.display = 'none';
}

function excluirServico() {
    if (servico) {
        window.location.href = `excluirServico.php?id=${servico}`;
    }
}

function atualizarStatus() {
    if (servico) {
        window.location.href = `atualizarStatus.php?id=${servico}`;
    }
}

document.getElementById('btn-nao').addEventListener('click', () => esconderPopup('popup-excluir'));
document.getElementById('btn-sim').addEventListener('click', excluirServico);
document.getElementById('btn-fechar').addEventListener('click', () => esconderPopup('popup-excluir'));

document.getElementById('btn-nao-proximo-status').addEventListener('click', () => esconderPopup('popup-proximo-status'));
document.getElementById('btn-sim-proximo-status').addEventListener('click', atualizarStatus);
document.getElementById('btn-fechar-proximo-status').addEventListener('click', () => esconderPopup('popup-proximo-status'));

document.querySelectorAll('.lixeira').forEach((lixeira) => {
    lixeira.addEventListener('click', (event) => {
        event.preventDefault();
        mostrarPopup('popup-excluir', lixeira.getAttribute('data-id'));
    });
});

document.querySelectorAll('.proximo-status').forEach((proximoStatus) => {
    proximoStatus.addEventListener('click', (event) => {
        event.preventDefault();
        mostrarPopup('popup-proximo-status', proximoStatus.getAttribute('data-id'));
    });
});

document.getElementById('popup-excluir').addEventListener('click', (event) => {
    if (event.target === document.getElementById('popup-excluir')) {
        esconderPopup('popup-excluir');
    }
});

document.getElementById('popup-proximo-status').addEventListener('click', (event) => {
    if (event.target === document.getElementById('popup-proximo-status')) {
        esconderPopup('popup-proximo-status');
    }
});