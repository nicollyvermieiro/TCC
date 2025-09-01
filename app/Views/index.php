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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    
</head>
<body>
    <?php include __DIR__ . '/partials/menu.php'; ?>

    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow-lg p-4 text-center" style="max-width: 500px; border-radius: 20px;">
            <div class="icon-circle mx-auto mb-3">
                <i class="bi bi-tools"></i>
            </div>
            <h1 class="h3 fw-bold mb-3">Bem-vindo ao <span class="text-primary">ManutSmart</span>!</h1>
            <p class="text-muted">
                <?php if (!$adminCount): ?>
                    Não há nenhum administrador cadastrado.<br>
                <?php else: ?>
                    Faça login para acessar o sistema.<br>
                <?php endif; ?>
            </p>
            <?php if (!$adminCount): ?>
                <a href="?route=usuarios/criar" class="btn btn-primary w-100 mt-2">
                    <i class="bi bi-person-plus"></i> Criar Primeiro Administrador
                </a>
            <?php else: ?>
                <a href="?route=auth/loginForm" class="btn btn-success w-100 mt-2">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
