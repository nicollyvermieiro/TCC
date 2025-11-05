<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Tipo de Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <!-- Flash message -->
    <?php $flash = getFlashMessage(); ?>
    <?php if ($flash): ?>
        <div class="container mt-4">
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container mt-4">
        <h2 class="mb-4">Criar Tipo de Chamado</h2>

        <form method="post" action="?route=tipos_chamados/salvar">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea class="form-control" id="descricao" name="descricao"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="?route=tipos_chamados/listar" class="btn btn-secondary ms-2">Voltar para Lista</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
