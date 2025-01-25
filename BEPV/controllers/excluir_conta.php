<?php
session_start();
require_once '../models/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? null;

if (!$email) {
    echo "Erro: Nenhum email fornecido.";
    exit();
}

$db = new Database();
$conn = $db->getConnection();

if ($conn) {

    $stmt = $conn->prepare("SELECT id FROM cadastro WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($cadastrado_id);
    $stmt->fetch();
    $stmt->close();

    if ($cadastrado_id) {

        $stmtSaidasProdutos = $conn->prepare("DELETE FROM saidas_produtos WHERE cadastrado_id = ?");
        $stmtSaidasProdutos->bind_param("i", $cadastrado_id);
        $stmtSaidasProdutos->execute();
        $stmtSaidasProdutos->close();

        $stmtsaidasbck = $conn->prepare("DELETE FROM saidas_bck WHERE cadastrado_id = ?");
        $stmtsaidasbck->bind_param("i", $cadastrado_id);
        $stmtsaidasbck->execute();
        $stmtsaidasbck->close();

        $stmtclientes = $conn->prepare("DELETE FROM clientes WHERE cadastrado_id = ?");
        $stmtclientes->bind_param("i", $cadastrado_id);
        $stmtclientes->execute();
        $stmtclientes->close();

        $stmtclientes = $conn->prepare("DELETE FROM produtos WHERE cadastrado_id = ?");
        $stmtclientes->bind_param("i", $cadastrado_id);
        $stmtclientes->execute();
        $stmtclientes->close();

        $stmtcadastro = $conn->prepare("DELETE FROM cadastro WHERE id = ?");
        $stmtcadastro->bind_param("i", $cadastrado_id);
        $stmtcadastro->execute();
        $stmtcadastro->close();

        session_destroy();

        echo "Conta excluída com sucesso!";
    } else {
        echo "Erro: Usuário não encontrado.";
    }

    $conn->close();
} else {
    echo "Erro ao conectar ao banco de dados.";
}
?>
