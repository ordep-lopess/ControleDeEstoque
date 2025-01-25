<?php

session_start();

require_once '../models/alteracao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../views/produtos.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cadastrado_id = $_SESSION['usuario_id'];
    $id = $_POST['produto_id'];
    $nome = $_POST['produto'];
    $embalagem = $_POST['embalagem'];
    $quantidade = $_POST['quantidade'];
    $gramas = $_POST['gramas'];

    $alteracao = new alteracao();


        if($alteracao->cadastro_produto($cadastrado_id, $nome, $embalagem, $quantidade, $gramas)){
            header('Location: ../views/produtos.php?Success=Produto cadastrado com sucesso');
        }else {
            header('Location: ../views/produtos.php?Error=Erro ao cadastrar Produto');
        }   
    
}

?>