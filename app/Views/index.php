<?php
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/Controllers/UsuariosController.php';

$usuariosController = new UsuariosController();
$adminCount = $usuariosController->existeAdministrador() ? 1 : 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Bem-vindo - ManutSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <?php include __DIR__ . '/partials/menu.php'; ?>

    <div class="container mt-5 text-center">
        <h1>Seja bem-vindo ao ManutSmart!</h1>
        <p class="mt-3">
            <?php if (!$adminCount): ?>
                Não há nenhum administrador cadastrado.<br>
                <a href="?route=usuarios/criar" class="btn btn-primary mt-3">Criar Primeiro Administrador</a>
            <?php else: ?>
                Faça login para acessar o sistema.<br>
                <a href="?route=auth/loginForm" class="btn btn-success mt-3">Login</a>
            <?php endif; ?>
        </p>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
