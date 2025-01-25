<?php
session_start();
require_once '../models/database.php';
require_once '../models/chamado.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: acessar.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_id = $_POST['produto_id'];
    $nome = $_POST['produto'];
    $embalagem = $_POST['embalagem'];
    $quantidade = $_POST['quantidade'];
    $gramas = $_POST['gramas'];

    $chamado = new chamado();
    $resultado = $chamado->editar_produto($produto_id, $nome, $embalagem, $quantidade, $gramas);

    if ($resultado) {
        header("Location: ../views/produtos.php?status=sucesso");
    } else {
        header("Location: ../views/produtos.php?status=erro");
    }
    exit;
}
?>
