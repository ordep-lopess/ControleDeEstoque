<?php 

require_once '../models/chamado.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $chamado = new chamado();
    $usuario = $chamado->login($email, $senha);

    // Tenta realizar o login
    if ($usuario){
        session_start();
       $_SESSION['usuario_id'] = $usuario['id'];
       $_SESSION['usuario_nome'] = $usuario['nome'];
       $_SESSION['usuario_email'] = $usuario ['email'];
       header('Location: ../views/inicio.php');
    }else {

        header('Location: ../views/acessar.html?error=1');
    }


}

?>