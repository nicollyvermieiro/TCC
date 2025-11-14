<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Login - ManutSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #004aad, #007bff);
            font-family: "Segoe UI", sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        .login-card h2 {
            color: #004aad;
            font-weight: 600;
        }
        .form-control {
            border-radius: 10px;
            padding: 10px;
        }
        .btn-primary {
            background-color: #004aad;
            border: none;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #00367e;
            transform: scale(1.03);
        }
        .text-muted a {
            color: #004aad;
            text-decoration: none;
        }
        .text-muted a:hover {
            text-decoration: underline;
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: #004aad;
            color: white;
            text-align: center;
            padding: 5px 0;
            font-size: 0.85rem;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<?php 
// include __DIR__ . '/../partials/menu.php'; // Opcional — remover para login limpo

require_once __DIR__ . '/../../helpers/session.php';
if (hasFlashMessage()):
    $flash = getFlashMessage();
?>
<div class="position-absolute top-0 start-50 translate-middle-x mt-3" style="width: 90%; max-width: 400px;">
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> shadow-sm" role="alert">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
</div>
<?php endif; ?>

<div class="login-card text-center">
    <div class="mb-3">
        <i class="bi bi-gear-fill text-primary" style="font-size: 2.5rem;"></i>
    </div>
    <h2 class="mb-4">ManutSmart</h2>
    <p class="text-muted mb-4">Acesse sua conta para continuar</p>
    
    <form method="POST" action="?route=auth/login">
        <div class="mb-3 text-start">
            <label for="email" class="form-label fw-semibold">E-mail</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="exemplo@dominio.com" required autofocus />
        </div>
        <div class="mb-3 text-start">
            <label for="senha" class="form-label fw-semibold">Senha</label>
            <input type="password" id="senha" name="senha" class="form-control" placeholder="********" required />
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-3">
            <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
        </button>
    </form>

    <div class="text-muted mt-3">
        <a href="?route=auth/esqueciSenha" class="small">Esqueci minha senha</a>
    </div>
</div>

<footer>
    &copy; 2025 <strong>ManutSmart</strong> — Gestão de Manutenção Inteligente
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
