<?php
session_start();
require_once '../models/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../views/produtos.php');
    exit();
}

if (isset($_GET['excluir'])) {
    $produto_id = $_GET['excluir'];
    $usuario_id = $_SESSION['usuario_id'];

    $db = new Database();
    $conn = $db->getConnection();

    if ($conn) {

        $stmtSaidas = $conn->prepare("DELETE FROM saidas_produtos WHERE produto_id = ? AND cadastrado_id = ?");
        $stmtSaidas->bind_param("ii", $produto_id, $usuario_id); 

        if ($stmtSaidas->execute()) {

            $query = "DELETE FROM produtos WHERE id = ? AND cadastrado_id = ?";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("ii", $produto_id, $usuario_id);

                if ($stmt->execute()) {

                    header('Location: ../views/produtos.php?Success=Produto excluído com sucesso');
                } else {

                    header('Location: ../views/produtos.php?Error=Erro ao excluir produto');
                }

                $stmt->close();
            } else {

                header('Location: ../views/produtos.php?Error=Erro ao preparar a query para excluir produto');
            }
        } else {

            header('Location: ../views/produtos.php?Error=Erro ao excluir as saídas de produtos');
        }

        $stmtSaidas->close();
    } else {

        header('Location: ../views/produtos.php?Error=Falha na conexão com o banco de dados');
    }


    $conn->close();
} else {
        header('Location: ../views/produtos.php?Error=Produto não especificado');
}

