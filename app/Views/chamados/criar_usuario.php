<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Abrir Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

<?php
require_once __DIR__ . '/../../helpers/session.php';
$flash = getFlashMessage();
?>

<?php if ($flash): ?>
<div class="container mt-3">
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

    <div class="container mt-4">
        <h2>Abrir Chamado</h2>

        <form method="POST" action="?route=chamados/salvarUsuario" enctype="multipart/form-data">

             <?php if (!empty($_SESSION['is_guest']) && $_SESSION['is_guest'] === true): ?>
            <div class="mb-3">
                <label for="nome" class="form-label">Seu Nome (opcional)</label>
                <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite seu nome ou deixe em branco">
            </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição do Problema</label>
                <textarea name="descricao" id="descricao" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="localizacao" class="form-label">Localização</label>
                <input type="text" name="localizacao" id="localizacao" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="anexo" class="form-label">Anexar Arquivo (opcional)</label>
                <input type="file" name="anexo" id="anexo" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Registrar Chamado</button>
        </form>
    </div>
</body>
</html>
