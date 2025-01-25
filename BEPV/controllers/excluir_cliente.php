<?php
session_start();
require_once '../models/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: acessar.html");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $db = new Database();
    $conn = $db->getConnection();

    $stmtSaidas = $conn->prepare("DELETE FROM saidas_produtos WHERE cliente_id = (SELECT id FROM clientes WHERE email = ? AND cadastrado_id = ?) LIMIT 1");
    $stmtSaidas->bind_param("si", $email, $usuario_id);

    if ($stmtSaidas->execute()) {
        $stmt = $conn->prepare("DELETE FROM clientes WHERE email = ? AND cadastrado_id = ?");
        $stmt->bind_param("si", $email, $usuario_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                header('Location: ../views/clientes.php?Success=Cliente excluído com sucesso');
            } else {
                header('Location: ../views/clientes.php?Success=Nenhum cliente encontrado com este email');
            }
        } else {
            header('Location: ../views/clientes.php?Success=Erro ao excluir cliente');
        }

        $stmt->close();
    } else {
        header('Location: ../views/clientes.php?Success=Erro ao excluir saídas de produtos');
    }

    $stmtSaidas->close();
    $conn->close();
} else {
    header('Location: ../views/clientes.php?Success=Erro ao excluir cliente');
}
