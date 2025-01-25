<?php 

session_start();

require_once '../models/chamado.php';

if (!isset($_SESSION['usuario_id'])){
    header('Location ../views/acessar.html');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cadastrado_id = $_SESSION['usuario_id'];

    $chamado = new chamado();
    $result = $chamado->cadastrar_cliente($cadastrado_id, $nome, $email);

    if ($result) {
        header('Location: ../views/clientes.php?Success=Cliente cadastrado com sucesso');
    }else {
        header('Location: ../views/clientes.php?Error=Erro ao cadastrar Cliente');
    }
}

?>