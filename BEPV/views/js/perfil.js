function confirmDelete() {
    if (confirm('Você tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.')) {
        window.location.href = 'controllers/deletar_conta.php';
    }
}

const profilePicInput = document.getElementById("profile-pic");
const profilePreview = document.getElementById("profile-preview");
const defaultImage = document.getElementById("default-image");

// Exibe a imagem selecionada como prévia
profilePicInput.addEventListener("change", function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            profilePreview.setAttribute("src", e.target.result);
            profilePreview.style.display = "block"; // Mostra a imagem
            defaultImage.style.display = "none"; // Esconde a imagem padrão
        };
        reader.readAsDataURL(file);
    }
});

// Manipula o envio do formulário
document.getElementById("profile-form").addEventListener("submit", function(event) {
    // Remover o preventDefault para permitir que o formulário seja enviado
    const salonName = document.getElementById("salon-name").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    // Alertar os dados para depuração
    alert(`Dados Salvos:\nNome do Salão: ${salonName}\nEmail: ${email}\nSenha: ${password}`);
});