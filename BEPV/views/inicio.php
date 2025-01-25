<?php
session_start();

require_once '../models/chamado.php';

//Verifica se o usuario está logado
if(!isset($_SESSION['usuario_id'])){
    //redireciona para a pagina de login
    header('Location: acessar.html');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

$chamado = new chamado();
$saida_produtos = $chamado->saida_produtos($usuario_id);

$clientes = $saida_produtos['clientes'];
$produtos = $saida_produtos['produtos'];
$saidas = $saida_produtos['saidas'];

// Lógica para verificar o estoque e gerar a notificação
$estoque_baixo = []; // Para armazenar produtos com estoque baixo (menos que 100ml)

foreach ($produtos as $produto) {
    if ($produto['totalGramas'] < 100) {
        $estoque_baixo[] = $produto['nome']; // Adiciona o nome do produto com estoque baixo
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
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
            <li class="item-menu ativo">
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
            <li class="item-menu">
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

    <div id="formPopup" class="popup" style="display: none">
        <div class="popup-content">
            <span class="close-button">×</span>
            <form id="productForm" method="post" action="../controllers/registar_saida.php">
                <label for="entryExit">
                    <h3>Lance uma saída de produto:</h3>
                </label>
                <br />
                <label for="clientName">Nome Cliente:</label>
                <select id="clientName" name="clientName" class="form-control" required>
                    <option value="">Selecione um cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                    <option value="<?php echo htmlspecialchars($cliente['nome']); ?>">
                        <?php echo htmlspecialchars($cliente['nome']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <br />
                <label for="productName">Selecionar Produto:</label>
                <select id="productName" name="productName" class="form-control" onchange="atualizarMaximo()" required>
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtos as $produto): ?>
                    <option value="<?php echo htmlspecialchars($produto['nome']); ?>"
                        data-max="<?php echo htmlspecialchars($produto['totalGramas']); ?>">
                        <?php echo htmlspecialchars($produto['nome']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <br />
                <label for="quantity">Quantidade (em gramas):</label>
                <input type="number" id="quantity" name="quantity" placeholder="em gramas" class="form-control" required />
                <br />
                <input type="hidden" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" />
                <button type="submit" class="btn-save">Salvar</button>
            </form>
        </div>
    </div>

    <button id="openFormButton" class="btn-open-form">Nova saída</button>
    <div class="container-fluid mt-5" style="padding-left: 40ch; padding-right: 3ch;">
        <div class="row g-2">
        <h2>Saídas de Produtos</h2> <br>       
        <?php if (count($saidas) > 0): ?>
            <?php foreach ($saidas as $saida): ?>
            <div class="col-md-3 col-sm-6 d-flex align-items-stretch">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($saida['produto_nome']); ?></h5>
                        <p class="card-text"><strong>Cliente:</strong>
                            <?php echo htmlspecialchars($saida['cliente_nome']); ?></p>
                        <p class="card-text"><strong>Quantidade:</strong>
                            <?php echo htmlspecialchars($saida['quantidade']); ?> ml</p>
                        <p class="card-text"><strong>Data da Saída:</strong>
                            <?php echo htmlspecialchars($saida['data']); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p class="text-muted">Nenhuma saída até o momento.
            <br>Para começar primeiro adicione um <a href="clientes.php">Cliente</a> e um <a href="
            produtos.php">Produto</a>.
            </p>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function atualizarMaximo() {
        var select = document.getElementById('productName');
        var selectedOption = select.options[select.selectedIndex];
        var maxQuantity = selectedOption.getAttribute('data-max');

        var quantityInput = document.getElementById('quantity');
        quantityInput.max = maxQuantity ? maxQuantity : 0; // Define o máximo
        quantityInput.value = ""; // Limpa o campo de quantidade quando um novo produto é selecionado
    }

    // Função para exibir as notificações de estoque baixo
    function verificarEstoque() {
        var produtosComEstoqueBaixo = <?php echo json_encode($estoque_baixo); ?>;
        if (produtosComEstoqueBaixo.length > 0) {
            // Verifica se o navegador suporta notificações
            if (Notification.permission === "granted") {
                produtosComEstoqueBaixo.forEach(function(produto) {
                    new Notification("Estoque baixo", {
                        body: "Estoque baixo no produto: " + produto,
                        icon: "icon.png" // Você pode colocar um ícone aqui se desejar
                    });
                });
            } else if (Notification.permission !== "denied") {
                Notification.requestPermission().then(function(permission) {
                    if (permission === "granted") {
                        produtosComEstoqueBaixo.forEach(function(produto) {
                            new Notification("Estoque baixo", {
                                body: "Estoque baixo no produto: " + produto,
                                icon: "icon.png"
                            });
                        });
                    }
                });
            }
        }
    }

    // Chama a função de verificação de estoque baixo ao carregar a página
    window.onload = function() {
        verificarEstoque();
    }

    // Entrada/Saída de Produtos
    document.getElementById("openFormButton").addEventListener("click", function() {
        document.getElementById("formPopup").style.display = "flex"; // Exibe o pop-up
    });

    document.querySelector(".close-button").addEventListener("click", function() {
        document.getElementById("formPopup").style.display = "none"; // Esconde o pop-up
    });

    window.addEventListener("click", function(event) {
        if (event.target == document.getElementById("formPopup")) {
            document.getElementById("formPopup").style.display = "none"; // Esconde o pop-up
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
