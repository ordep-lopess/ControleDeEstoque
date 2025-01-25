// Função para verificar se o campo de senha está preenchido
function checkPassword() {
    const password = document.getElementById('password').value.trim();
    const btnSave = document.getElementById('btn-save');
    const btnExcluir = document.getElementById('btn-excluir');

    // Se a senha não estiver preenchida, desabilita os botões
    if (password === '') {
        btnSave.disabled = true;
        btnExcluir.disabled = true;
    } else {
        // Habilita os botões se a senha estiver preenchida
        btnSave.disabled = false;
        btnExcluir.disabled = false;
    }
}

// Adiciona um evento para verificar o campo de senha sempre que ele mudar
document.getElementById('password').addEventListener('input', checkPassword);

// Chama a função ao carregar a página para garantir que os botões começam desabilitados
window.onload = function() {
    checkPassword();
};
