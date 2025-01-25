<?php
require_once '../models/chamado.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);


    $chamado = new chamado();


    if ($chamado->create($nome, $email, $senha)) {
        header('Location: ../views/acessar.html');
        exit;
    } else {
        header("Location: cadastrar_usuario.php?erro=email_existente");
        exit;
    }
}