<?php
session_start();
require_once '../models/alteracao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../views/acessar.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario_id = $_SESSION['usuario_id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $alteracao = new alteracao();

    if ($alteracao->alterar_cadastro($usuario_id, $nome, $email, $senha)) {

        session_unset(); 
        session_destroy(); 

        header('Location: ../views/acessar.html?Success=Cadastro alterado com sucesso');
    }else {
        header('Location: ../views/perfil.php?Error=Cadastro nao Concluido');
    }

}