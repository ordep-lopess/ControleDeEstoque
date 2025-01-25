document.getElementById("openFormButton").addEventListener("click", function() {
    document.getElementById("formPopup").style.display = "flex";
});

document.querySelector(".close-button").addEventListener("click", function() {
    document.getElementById("formPopup").style.display = "none";
});

window.addEventListener("click", function(event) {
    if (event.target == document.getElementById("formPopup")) {
        document.getElementById("formPopup").style.display = "none";
    }
});

document.querySelector('.close-button').addEventListener('click', closePopup);

function closePopup() {
    var popup = document.querySelector('.popup');
    popup.style.display = 'none';
}

document.querySelectorAll('.close-button').forEach(function(button) {
    button.addEventListener('click', closePopup);
});


var btnExp = document.querySelector("#btn-exp");
var menuSite = document.querySelector(".menu-lateral");

btnExp.addEventListener("click", function() {
    menuSite.classList.toggle("expandir");
});