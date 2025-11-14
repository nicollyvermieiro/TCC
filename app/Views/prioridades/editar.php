<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Prioridade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

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
        <h1 class="mb-4">Editar Prioridade</h1>

        <form method="post" action="?route=prioridades/atualizar">
            <input type="hidden" name="id" value="<?= htmlspecialchars($dados['id']) ?>">

            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($dados['nome']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="nivel" class="form-label">Nível (1 = mais urgente, 5 = menos urgente):</label>
                <input type="number" class="form-control" id="nivel" name="nivel" value="<?= htmlspecialchars($dados['nivel']) ?>" min="1" max="5" required>
            </div>

            <button type="submit" class="btn btn-success">Atualizar</button>
            <a href="?route=prioridades/listar" class="btn btn-secondary ms-2">Voltar para Lista</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <footer class="bg-primary text-white text-center py-1 mt-4 shadow-sm fixed-bottom">
        <div style="font-size: 0.8rem; opacity: 0.8;">
            &copy; 2025 ManutSmart. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
