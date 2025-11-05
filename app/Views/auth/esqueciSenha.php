<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - ManutSmart</title>
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
        .btn-warning {
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="card p-4" style="max-width: 420px; width: 100%;">
    <h3 class="text-center mb-3 text-primary">Recuperar Senha</h3>
    <p class="text-center text-muted mb-4">Informe o e-mail cadastrado para receber o link de redefinição.</p>

    <form method="POST" action="?route=auth/enviarRecuperacao" autocomplete="off">
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email cadastrado:</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="exemplo@dominio.com" required autofocus />
        </div>
        <button type="submit" class="btn btn-warning w-100">Enviar link de recuperação</button>
        <div class="text-center mt-3">
            <a href="?route=auth/loginForm" class="text-decoration-none">Voltar ao login</a>
        </div>
    </form>
</div>

<?php

require_once __DIR__ . '/../../helpers/session.php';
if (hasFlashMessage()):
    $flash = getFlashMessage();
?>
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
