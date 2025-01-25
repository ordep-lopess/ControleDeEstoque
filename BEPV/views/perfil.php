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

$chamado = new chamado();

$dados = $chamado->dados_cadastrado($usuario_id);


$nome_salao = $dados['nome'] ;
$email = $dados['email'];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BEPV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="CSS/menu.css" />
    <link rel="stylesheet" href="CSS/perfil.css" />
</head>

<body>

    <?php if (isset($_GET['Error'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_GET['Error']); ?>
    </div>
    <?php endif; ?>

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
            <li class="item-menu">
                <a href="produtos.php" class="nav-link">
                    <span class="icon"><i class="bi bi-box2"></i></span>
                    <span class="txt-link">Produtos</span>
                </a>
            </li>
            <li class="item-menu ativo">
                <a href="perfil.php" class="nav-link">
                    <span class="icon"><i class="bi bi-person-circle"></i></span>
                    <span class="txt-link">Conta</span>
                </a>
            </li>
            <br /><br /><br /><br />
            <li class="item-menu">
                <a href="../controllers/logout.php" class="nav-link">
                    <span class="icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="txt-link">Sair</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="container" style="margin-top: 10ch">
        <h2 style="text-align: center">Alterar Dados do Perfil</h2>

        <div class="profile-section">
    <label for="profile-pic">Logo do Salão</label>
    <div class="profile-pic-container" onclick="document.getElementById('profile-pic').click();">
        <div class="default-image" id="default-image"></div>
        <img id="profile-preview" src="" alt="Pré-visualização da foto"/>
        <input type="file" id="profile-pic" accept="image/*" style="display:none;" />
    </div>
</div>

<form id="profile-form" action="../controllers/salvar_alteracao.php" method="POST">
    <label for="salon-name">Nome do Salão:</label>
    <input type="text" id="salon-name" name="nome" value="<?= htmlspecialchars($nome_salao) ?>"
        placeholder="Digite o nome do salão" autocomplete="off" required>

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>"
        placeholder="Digite seu e-mail" autocomplete="off" required>

    <label for="password">Senha (este campo é obrigatório):</label>
    <input type="password" id="password" name="senha" placeholder="Digite sua nova senha ou a atual" autocomplete="off"
        pattern=".{6,}" title="A senha deve ter pelo menos 6 caracteres" required>

    <div class="btns">
        <button type="submit" class="btn-save" id="btn-save" disabled>Salvar Alterações</button>
        <button type="button" class="btn-exc" id="btn-excluir" onclick="deleteAccount()" disabled>Excluir Conta</button>
    </div>
</form>

    <script src="js/sla.js"></script>
    <script src="js/inicio.js"></script>
    <script src="js/excluir.js"></script>
    <script src="js/password.js"></script>
</body>

</html>