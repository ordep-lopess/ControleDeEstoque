document.addEventListener("DOMContentLoaded", function () {
    var openFormButton = document.getElementById("openFormButton");
    var formPopup = document.getElementById("formPopup");
    var closeButton = document.querySelector(".close-button");

    formPopup.style.display = "none";

    openFormButton.addEventListener("click", function () {
        formPopup.style.display = "flex"; 
        document.getElementById('productForm').reset(); 
    });

    closeButton.addEventListener("click", function () {
        formPopup.style.display = "none"; 
    });

    function calcularQuantidadeRestante() {
        var quantidade = parseFloat(document.getElementById('quantidade').value) || 0;
        var embalagem = parseFloat(document.getElementById('embalagem').value) || 0;
        var gramas = quantidade * embalagem;
        document.getElementById('gramas').value = gramas.toFixed(2); 
    }

    document.getElementById('quantidade').addEventListener('input', calcularQuantidadeRestante);
    document.getElementById('embalagem').addEventListener('input', calcularQuantidadeRestante);
});

function openEditForm(button) {
    console.log('Botão Editar foi clicado'); 
    
    var id = button.getAttribute("data-id");
    var nome = button.getAttribute("data-nome");
    var embalagem = button.getAttribute("data-embalagem");
    var quantidade = button.getAttribute("data-quantidade");
    var gramas = button.getAttribute("data-gramas");

    document.getElementById("editProdutoId").value = id;
    document.getElementById("editProdutoNome").value = nome;
    document.getElementById("editProdutoEmbalagem").value = embalagem;
    document.getElementById("editProdutoQuantidade").value = quantidade;
    document.getElementById("editProdutoGramas").value = gramas;

    document.getElementById("editFormPopup").style.display = "flex";

}

function closeEditForm() {
    document.getElementById("editFormPopup").style.display = "none";
}