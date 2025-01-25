<?php 
require_once 'database.php';

class alteracao{
    private $conn;
    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function cadastro_produto($cadastrado_id, $nome, $embalagem, $quantidade, $gramas){
        $query = "INSERT INTO produtos (cadastrado_id, nome, embalagem, quantidade, gramas) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isiii", $cadastrado_id, $nome, $embalagem, $quantidade, $gramas);
        return $stmt->execute();
    }

    public function alterar_cadastro($usuario_id, $nome, $email, $senha){
        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
        
        $query = "UPDATE cadastro SET nome = ?, email = ?, senha_hash = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $nome, $email, $senha_hash, $usuario_id);
        return $stmt->execute();
    }

    public function deletar($cadastrado_id){
        try {

            $sql_saidas_produtos = "DELETE FROM saidas_produtos WHERE cadastrado_id = ?";
            $stmt_saidas_produtos = $this->conn->prepare($sql_saidas_produtos);
            $stmt_saidas_produtos->bind_param("i", $cadastrado_id);
            $stmt_saidas_produtos->execute();

            $sql_clientes = "DELETE FROM clientes WHERE cadastrado_id = ?";
            $stmt_clientes = $this->conn->prepare($sql_clientes);
            $stmt_clientes->bind_param("i", $cadastrado_id);
            $stmt_clientes->execute();

            $sql_cadastro = "DELETE FROM cadastro WHERE id = ?";
            $stmt_cadastro = $this->conn->prepare($sql_cadastro);
            $stmt_cadastro->bind_param("i", $cadastrado_id);
            $stmt_cadastro->execute();


            $this->conn->commit();

            session_destroy();

            header("Location: ../index.html?msg=Conta excluída com sucesso.");
            exit;
        } catch (Exception $e) {

            $this->conn->rollback();
            echo json_encode(["success" => false, "message" => "Erro ao excluir conta: " . $e->getMessage()]);
            }
}

    public function saida_produto($usuario_id, $nome_cliente, $nome_produto, $gramas){
        $response = ['status' => 'error', 'message' => ''];
    
        $queryCliente = "SELECT id FROM clientes WHERE nome = ? AND cadastrado_id = ?";
        $stmtClientes = $this->conn->prepare($queryCliente);
        $stmtClientes->bind_param("si", $nome_cliente, $usuario_id);
        $stmtClientes->execute();
        $resultCliente = $stmtClientes->get_result();
        $cliente = $resultCliente->fetch_assoc();
        $stmtClientes->close();
    
        if ($cliente) {
            $cliente_id = $cliente['id'];
    
            $queryProduto = "SELECT id, gramas, quantidade, embalagem FROM produtos WHERE nome = ? AND cadastrado_id = ?";
            $stmtProdutos = $this->conn->prepare($queryProduto);
            $stmtProdutos->bind_param("si", $nome_produto, $usuario_id);
            $stmtProdutos->execute();
            $resultProdutos = $stmtProdutos->get_result();
            $produto = $resultProdutos->fetch_assoc();
            $stmtProdutos->close();
    
            if ($produto) {
                $produto_id = $produto['id'];
                $quantidade_atual = (int)$produto['quantidade'];
                $gramas_atual = (int)$produto['gramas'];
                $embalagem = (int)$produto['embalagem']; // Tamanho da embalagem em gramas
    
                if ($gramas_atual >= $gramas) {
                    $querySaida = "INSERT INTO saidas_produtos (cadastrado_id, cliente_id, produto_id, quantidade) VALUES (?, ?, ?, ?)";
                    $stmtSaida = $this->conn->prepare($querySaida);
                    $stmtSaida->bind_param("iiis", $usuario_id, $cliente_id, $produto_id, $gramas);
    
                    if ($stmtSaida->execute()) {
                        $nova_grama = $gramas_atual - $gramas;
                        $queryAtualizarProduto = "UPDATE produtos SET gramas = ? WHERE id = ?";
                        $stmtAtualizar = $this->conn->prepare($queryAtualizarProduto);
                        $stmtAtualizar->bind_param("ii", $nova_grama, $produto_id);
    
                        if ($stmtAtualizar->execute()) {
                            // Verifica se as gramas do produto chegaram a 0
                            if ($nova_grama == 0 && $quantidade_atual > 0) {
                                $nova_quantidade = $quantidade_atual - 1; // Subtrai 1 da quantidade
                                $nova_grama = $embalagem; // Reseta as gramas para o valor da embalagem
    
                                // Atualiza a quantidade e as gramas do produto
                                $queryAtualizarQuantidade = "UPDATE produtos SET quantidade = ?, gramas = ? WHERE id = ?";
                                $stmtAtualizarQuantidade = $this->conn->prepare($queryAtualizarQuantidade);
                                $stmtAtualizarQuantidade->bind_param("iii", $nova_quantidade, $nova_grama, $produto_id);
    
                                if ($stmtAtualizarQuantidade->execute()) {
                                    $response['status'] = 'success';
                                    $response['message'] = 'Saída de produto registrada, quantidade e gramas atualizadas com sucesso!';
                                } else {
                                    $response['message'] = 'Erro ao atualizar a quantidade e as gramas do produto: ' . $this->conn->error;
                                }
                                $stmtAtualizarQuantidade->close();
                            } else {
                                $response['status'] = 'success';
                                $response['message'] = 'Saída de produto registrada e quantidade de gramas atualizada com sucesso!';
                            }
                        } else {
                            $response['message'] = 'Erro ao atualizar a quantidade de gramas do produto: ' . $this->conn->error;
                        }
                        $stmtAtualizar->close();
                    } else {
                        $response['message'] = 'Erro ao registrar saída: ' . $this->conn->error;
                    }
                    $stmtSaida->close();
                } else {
                    $response['message'] = 'Erro: Estoque insuficiente para registrar a saída.';
                }
            } else {
                $response['message'] = 'Erro: Produto não encontrado.';
            }
        } else {
            $response['message'] = 'Erro: Cliente não encontrado.';
        }
    
        return $response;
    }
    

    public function excluir_produto($produto_id) {
        $sql_produto = "DELETE FROM produtos WHERE id = ?";
        $stmt_produto = $this->conn->prepare($sql_produto);
        $stmt_produto->bind_param("i", $produto_id);
        $stmt_produto->execute();
    }

    
}
?>