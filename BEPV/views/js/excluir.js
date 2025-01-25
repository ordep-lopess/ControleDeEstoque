function deleteAccount() {
    if (confirm("Tem certeza de que deseja excluir sua conta? Esta ação não pode ser desfeita.")) {
        const email = document.getElementById('email').value;

        // Envia uma requisição para excluir a conta
        fetch('../controllers/excluir_conta.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Exibe a resposta da exclusão
            if (data.includes("sucesso")) {
                window.location.href = "../index.html"; // Redireciona para o login após exclusão
            }
        })
        .catch(error => console.error("Erro ao excluir conta:", error));
    }
}