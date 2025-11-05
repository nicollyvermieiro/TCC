<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Login - ManutSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<?php 
include __DIR__ . '/../partials/menu.php'; 

// Incluir sessão e mostrar flash message (se existir)
require_once __DIR__ . '/../../helpers/session.php';
if (hasFlashMessage()):
    $flash = getFlashMessage();
?>
<div class="container mt-3">
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>" role="alert">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
</div>
<?php endif; ?>

<div class="container mt-5" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Login</h2>
    <form method="POST" action="?route=auth/login">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required autofocus />
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha:</label>
            <input type="password" id="senha" name="senha" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>

    <div class="text-center mt-3">
        <a href="?route=auth/esqueciSenha" class="text-decoration-none">Esqueci minha senha</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
