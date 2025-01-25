<?php
session_start();
require_once '../models/database.php';
require_once '../models/chamado.php';
$db = new Database();
$conn = $db->getConnection();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: acessar.html"); // Redireciona para a página de acesso se não estiver logado
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$chamado = new chamado();
$saida_produtos = $chamado->saida_produtos($usuario_id);

$produtos = $saida_produtos['produtos'];

// Verifica se algum produto tem menos de 100ml
$estoque_baixo = [];
foreach ($produtos as $produto) {
    if ($produto['gramas'] < 100) {
        $estoque_baixo[] = $produto['nome']; // Adiciona o nome do produto com estoque baixo
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BEPV</title>
    <link rel="stylesheet" href="CSS/menu.css" />
    <link rel="stylesheet" href="CSS/popup.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>
<body>
    <div class="menuzin"></div>
    <header>
        <div class="logo">
        <a href="../index.html"><img src="imagens/logo1.png" alt="Logo" /></a>
        </div>
    </header>

    <nav class="menu-lateral">
        <div class="btn-expandir">
            <i class="bi bi-list" id="btn-exp"></i>
        </div>
        <ul>
            <li class="item-menu">
                <a href="inicio.php" class="nav-link">
                    <span class="icon"><i class="bi bi-house"></i></span>
                    <span class="txt-link">Inicio</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="clientes.php" class="nav-link">
                    <span class="icon"><i class="bi bi-person-plus"></i></span>
                    <span class="txt-link">Clientes</span>
                </a>
            </li>
            <li class="item-menu ativo">
                <a href="produtos.php" class="nav-link">
                    <span class="icon"><i class="bi bi-box2"></i></span>
                    <span class="txt-link">Produtos</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="perfil.php" class="nav-link">
                    <span class="icon"><i class="bi bi-person-circle"></i></span>
                    <span class="txt-link">Conta</span>
                </a>
            </li>
            <br><br><br><br>
            <li class="item-menu">
                <a href="../controllers/logout.php" class="nav-link">
                    <span class="icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="txt-link">Sair</span>
                </a>
            </li>
        </ul>
    </nav>

    <button id="openFormButton" class="btn-open-form">Entrada de produto</button>

    <div id="formPopup" class="popup" style="display: none">
    <div class="popup-content">
        <span class="close-button">×</span>
        <form id="productForm" action="../controllers/cadastrar_produto.php" method="POST">
            <h3>Cadastrar produto:</h3>

            <label for="produto">Nome do produto:</label>
            <input type="text" id="produto" name="produto" class="form-control" required
                value="<?php echo isset($produto['nome']) ? $produto['nome'] : ''; ?>" />

            <label for="embalagem">Quantidade do Produto (ml):</label>
            <input type="number" id="embalagem" name="embalagem" class="form-control" required
                value="<?php echo isset($produto['embalagem']) ? $produto['embalagem'] : ''; ?>" />

            <div class="d-flex">
                <div class="me-2">
                    <label for="quantidade">Unidade:</label>
                    <input type="number" id="quantidade" name="quantidade" class="form-control" style="width: 70px"
                        required value="<?php echo isset($produto['quantidade']) ? $produto['quantidade'] : ''; ?>" />
                </div>
                <div>
                    <label for="gramas">Quantidade Restante (ml):</label>
                    <input type="number" id="gramas" name="gramas" class="form-control" style="width: 200px"
                        required value="<?php echo isset($produto['gramas']) ? $produto['gramas'] : ''; ?>" />
                </div>
            </div>

            <button type="submit" class="btn-save">Salvar</button>
        </form>
    </div>
</div>


    <div class="container-fluid mt-5" style="padding-left: 40ch; padding-right: 3ch;">
        <div class="row g-2">
            <h2>Lista de Produtos</h2> <br>
        	<?php if (count($produtos) > 0): ?>
                <?php foreach ($produtos as $produto): ?>
                    <div class="col-12 col-md-4 col-sm-6 d-flex align-items-stretch">
                        <div class="card w-100">
                            <div class="card-body">
                                <h6 class="card-title"><?php echo htmlspecialchars($produto['nome']); ?></h6>
                                <p class="card-text">Embalagem: <?php echo htmlspecialchars($produto['embalagem']); ?> ml</p>
                                <p class="card-text">Quantidade: <?php echo htmlspecialchars($produto['quantidade']); ?></p>
                                <p class="card-text">Restante: <?php echo htmlspecialchars($produto['gramas']); ?> ml</p>
                                <button 
                                    class="btn btn-secondary"
                                    data-id="<?php echo $produto['id']; ?>"
                                    data-nome="<?php echo htmlspecialchars($produto['nome']); ?>"
                                    data-embalagem="<?php echo htmlspecialchars($produto['embalagem']); ?>"
                                    data-quantidade="<?php echo htmlspecialchars($produto['quantidade']); ?>"
                                    data-gramas="<?php echo htmlspecialchars($produto['gramas']); ?>"
                                    onclick="openEditForm(this)">
                                    Editar
                                </button>
                               <a href="../controllers/excluir_produto.php?excluir=<?php echo $produto['id']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                          </div>
                      </div>
                    </div>
    <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Nenhum produto cadastrado até o momento.</p>
        <?php endif; ?>

        </div>
    </div>
        <div id="editFormPopup" class="popup" style="display: none;">
            <div class="popup-content">
                <span class="close-button" onclick="closeEditForm()">×</span>
                <form id="editProductForm" action="../controllers/editar_produto.php" method="POST">
                    <input type="hidden" id="editProdutoId" name="produto_id">
                    <h3>Editar Produto:</h3>
                    <label>Nome do Produto:</label>
                    <input type="text" id="editProdutoNome" name="produto" class="form-control" required>
                    <label>Quantidade por Embalagem (ml):</label>
                    <input type="number" id="editProdutoEmbalagem" name="embalagem" class="form-control" required>
                    <label>Quantidade de Embalagens:</label>
                    <input type="number" id="editProdutoQuantidade" name="quantidade" class="form-control" required>
                    <label>Quantidade Total (ml):</label>
                    <input type="number" id="editProdutoGramas" name="gramas" class="form-control">
                    <button type="submit" class="btn btn-danger">Salvar</button>
                </form>
            </div>
        </div>
<script src="JS/produtos.js"></script>
<script src="js/sla.js"></script>
</body>
</html>
