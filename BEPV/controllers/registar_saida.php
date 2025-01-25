<?php 
session_start();

require_once '../models/alteracao.php';
require_once '../models/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../views/acessar.html");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$nome_cliente = $_POST['clientName'];
$nome_produto = $_POST['productName'];
$gramas = (int)$_POST['quantity'];

$alteracao = new alteracao();

$saida_produtos = $alteracao->saida_produto($usuario_id, $nome_cliente, $nome_produto, $gramas);

if ($saida_produtos['status'] === 'success') {
    header('Location: ../views/inicio.php?Success=Sucesso ao cadastrar');
    exit();
} else {
    header('Location: ../views/inicio.php?Error=' . urlencode($saida_produtos['message']));
    exit();
}