<?php
require_once 'database.php';

class chamado{
    private $conn;
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($nome, $email, $senha_hash) {
        $query = "SELECT id FROM cadastro WHERE email = ?";
        $stmt = $this->conn->prepare($query);
    
        if (!$stmt) {
            die("Erro na preparação da consulta: " . $this->conn->error);
        }
    
        $stmt->bind_param("s", $email);
    
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {

                return false; 
            } else {

                $query = "INSERT INTO cadastro (nome, email, senha_hash) VALUES (?, ?, ?)";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("sss", $nome, $email, $senha_hash);
    
                if ($stmt->execute()) {
                    return true;
                } else {
                    return false; 
                }
            }
        } else {
            return false;
        }
    }

    public function login($email, $senha){
        $query = "SELECT id, nome, senha_hash FROM cadastro WHERE email = ?";
        $stmt = $this->conn->prepare($query);

        if(!$stmt){
            die("Erro na preparação da consulta: " . $this->conn->error);
        }

        $stmt->bind_param("s", $email);

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $usuario = $result->fetch_assoc();

                if(password_verify($senha, $usuario['senha_hash'])){
                    return $usuario; 
                }else {
                    return false; 
                }
            }else {
                return false; 
            }
        }else {
            return false; 
        }
    }

    public function cadastrar_cliente($cadastrado_id,$nome, $email){
        $query = "INSERT INTO clientes (cadastrado_id, nome, email) values (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iss", $cadastrado_id, $nome, $email);
        return $stmt->execute();
    }

    public function saida_produtos($usuario_id){
        $queryClientes = "SELECT nome, email FROM clientes WHERE cadastrado_id = ?";
        $stmtClientes = $this->conn->prepare($queryClientes);
        $stmtClientes->bind_param("i", $usuario_id);
        $stmtClientes->execute();
        $resultClientes = $stmtClientes->get_result();
        
        $clientes = [];
        while ($row = $resultClientes->fetch_assoc()){
            $clientes[] = [
                'nome' => $row['nome'],
                'email' => $row['email']
            ];
        }
        
        $stmtClientes->close();
    
        $queryProdutos = "SELECT id, nome, embalagem, quantidade, gramas FROM produtos WHERE cadastrado_id = ?";
        $stmtProdutos = $this->conn->prepare($queryProdutos);
        $stmtProdutos->bind_param("i", $usuario_id);
        $stmtProdutos->execute();
        $resultProdutos = $stmtProdutos->get_result();
    
        $produtos = [];
        while ($row = $resultProdutos->fetch_assoc()) {
            $totalGramas = $row['quantidade'] * $row['gramas'];
            $produtos[] = [
                'id' => $row['id'],
                'nome' => $row['nome'],
                'embalagem' => $row['embalagem'],
                'quantidade' => $row['quantidade'],
                'gramas' => $row['gramas'],
                'totalGramas' => $totalGramas
            ];
        }   
        $stmtProdutos->close();
    
        $querySaidas = "SELECT s.quantidade, s.data, s.cliente_nome, s.produto_nome
                        FROM saidas_bck s
                        WHERE s.cadastrado_id = ?";
        $stmtSaidas = $this->conn->prepare($querySaidas);
        $stmtSaidas->bind_param("i", $usuario_id);
        $stmtSaidas->execute();
        $resultSaidas = $stmtSaidas->get_result();
    
        $saidas = [];
        if($resultSaidas->num_rows > 0){
            while($row = $resultSaidas->fetch_assoc()){
                $saidas[] = [
                    'quantidade' => $row['quantidade'],
                    'data' => $row['data'],
                    'cliente_nome' => $row['cliente_nome'],
                    'produto_nome' => $row['produto_nome']
                ];
            }
        }
    
        $stmtSaidas->close();
    
        return [
            'clientes' => $clientes,
            'produtos' => $produtos,
            'saidas' => $saidas
        ];
    }
    

    public function dados_cadastrado($usuario_id){
        $query = "SELECT nome, email FROM cadastro WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $usuario = $result->fetch_assoc();
             
        
        return $usuario;
    }  
    
    public function editar_produto($produto_id, $nome, $embalagem, $quantidade, $gramas) {
        if (!$this->conn) {
            return false;
        }
    
        try {
            $sql = "UPDATE produtos 
                    SET nome = ?, 
                        embalagem = ?, 
                        quantidade = ?, 
                        gramas = ? 
                    WHERE id = ?";
    
            $stmt = $this->conn->prepare($sql);
            
            if ($stmt === false) {
                throw new Exception("Erro na preparação da consulta: " . $this->conn->error);
            }
    
            $stmt->bind_param('ssiii', $nome, $embalagem, $quantidade, $gramas, $produto_id);
            
            $executou = $stmt->execute();
    
            return $executou;
    
        } catch (Exception $e) {
            error_log("Erro ao editar produto: " . $e->getMessage());
            return false;
        }
    }
}
?>