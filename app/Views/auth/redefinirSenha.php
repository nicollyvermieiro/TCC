<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha - ManutSmart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .btn-success {
            font-weight: 600;
        }
    </style>
</head>
<body>

<?php
require_once __DIR__ . '/../../helpers/session.php';

$token = $_GET['token'] ?? '';
if (!$token) {
    setFlashMessage("Token inválido.", "danger");
    header("Location: ?route=auth/loginForm");
    exit;
}
?>

<div class="card p-4" style="max-width: 420px; width: 100%;">
    <h3 class="text-center mb-3 text-primary">Redefinir Senha</h3>
    <p class="text-center text-muted mb-4">Digite sua nova senha abaixo.</p>

    <form method="POST" action="?route=auth/salvarNovaSenha" autocomplete="off">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>" />
        <div class="mb-3">
            <label for="senha" class="form-label fw-semibold">Nova senha:</label>
            <input type="password" id="senha" name="senha" class="form-control" minlength="6" required />
        </div>
        <div class="mb-3">
            <label for="senha_confirm" class="form-label fw-semibold">Confirme a senha:</label>
            <input type="password" id="senha_confirm" name="senha_confirm" class="form-control" minlength="6" required />
        </div>
        <button type="submit" class="btn btn-success w-100">Redefinir senha</button>
        <div class="text-center mt-3">
            <a href="?route=auth/loginForm" class="text-decoration-none">Voltar ao login</a>
        </div>
    </form>
</div>

<?php if (hasFlashMessage()):
    $flash = getFlashMessage(); ?>
<script>
Swal.fire({
    icon: '<?= $flash['type'] === "success" ? "success" : "error" ?>',
    title: '<?= addslashes($flash['message']) ?>',
    confirmButtonColor: '#0d6efd'
});
</script>
<?php endif; ?>

</body>
</html>
