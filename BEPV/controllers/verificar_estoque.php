<?php
header('Content-Type: application/json'); 
require_once '../models/database.php';
require_once '../models/chamado.php'; 

$db = new Database();
$conn = $db->getConnection();


$chamado = new Chamado();
$produtos = $chamado->saida_produtos($usuario_id);
$estoque_baixo = []; 

foreach ($produtos as $produto) {
    if ($produto['totalGramas'] < 100) { 
        $estoque_baixo[] = $produto['nome'];
    }
}


echo json_encode($estoque_baixo);
?>
