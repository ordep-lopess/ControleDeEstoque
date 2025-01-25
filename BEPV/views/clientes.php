<?php
session_start();
require_once '../models/database.php';
require_once '../models/chamado.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: acessar.html"); // Redireciona para a página de acesso se não estiver logado
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$chamado= new chamado();
$saida_produtos = $chamado->saida_produtos($usuario_id);

$clientes = $saida_produtos['clientes'];

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
    <style>
    h3 {
        margin-top: 150px;
    }

    /* Estilo do popup de mensagem */
    .popup-msg {
        position: fixed;
        top: 200px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        display: none;
        z-index: 9999;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .popup-msg.info {
        background-color: #17a2b8;
    }
    </style>
</head>

<body>
    <div class="menuzin"></div>
    <header>
        <div class="logo">
        <a href="../index.html"><img src="imagens/logo1.png" alt="Logo" /></a>
        </div>
    </header>
    <nav class="menu-lateral">
        <div class="btn-expandir"><i class="bi bi-list" id="btn-exp"></i></div>
        <ul>
            <li class="item-menu"><a href="inicio.php" class="nav-link"><span class="icon"><i
                            class="bi bi-house"></i></span><span class="txt-link">Inicio</span></a></li>
            <li class="item-menu ativo"><a href="clientes.php" class="nav-link"><span class="icon"><i
                            class="bi bi-person-plus"></i></span><span class="txt-link">Clientes</span></a></li>
            <li class="item-menu"><a href="produtos.php" class="nav-link"><span class="icon"><i
                            class="bi bi-box2"></i></span><span class="txt-link">Produtos</span></a></li>
            <li class="item-menu"><a href="perfil.php" class="nav-link"><span class="icon"><i
                            class="bi bi-person-circle"></i></span><span class="txt-link">Conta</span></a></li>
            <br /><br /><br /><br />
            <li class="item-menu"><a href="../controllers/logout.php" class="nav-link"><span class="icon"><i
                            class="bi bi-box-arrow-right"></i></span><span class="txt-link">Sair</span></a></li>
        </ul>
    </nav>

    <button id="openFormButton" class="btn-open-form">Cadastrar novo cliente</button>

    <div id="formPopup" class="popup" style="display: none">
        <div class="popup-content">
            <span class="close-button">×</span>
            <form id="clientForm" action="../controllers/cadastrar_cliente.php" method="POST">
                <label for="nome">
                    <h2>Cadastrar novo cliente:</h2>
                </label>
                <label for="nome">Nome do cliente:</label>
                <input type="text" id="nome" name="nome" class="form-control" required />
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" placeholder="nome@email.com" class="form-control"
                    required />
                <button type="submit" class="btn-save">Salvar</button>
            </form>
        </div>
    </div>

    <div class="container-fluid mt-5" style="padding-left: 40ch; padding-right: 3ch;">
        <div class="row g-2">
        <h2>Lista de Clientes</h2> <br>
        <?php if (count($clientes) > 0): ?>
        <?php foreach ($clientes as $cliente): ?>
            <div class="col-md-3 col-sm-6 d-flex align-items-stretch">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($cliente['nome']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($cliente['email']); ?></p>
                        <form action="../controllers/excluir_cliente.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este cliente?')">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>">
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p class="text-muted">Nenhum cliente cadastrado até o momento.</p>
            <?php endif; ?>
        </div>
        <script src="js/sla.js"></script>
        <script src="js/notificacoes.js"></script>
</body>

</html>