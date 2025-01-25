function atualizarMaximo() {
    var select = document.getElementById('productName');
    var selectedOption = select.options[select.selectedIndex];
    var maxQuantity = selectedOption.getAttribute('data-max');

    var quantityInput = document.getElementById('quantity');
    quantityInput.max = maxQuantity ? maxQuantity : 0; // Define o máximo
    quantityInput.value = ""; // Limpa o campo de quantidade quando um novo produto é selecionado
}

// Entrada/Saída de Produtos
document.getElementById("openFormButton").addEventListener("click", function() {
    document.getElementById("formPopup").style.display = "flex"; // Exibe o pop-up
});

document.querySelector(".close-button").addEventListener("click", function() {
    document.getElementById("formPopup").style.display = "none"; // Esconde o pop-up
});

window.addEventListener("click", function(event) {
    if (event.target == document.getElementById("formPopup")) {
        document.getElementById("formPopup").style.display = "none"; // Esconde o pop-up
    }
});

