<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">Criar Usuário</h1>

        <form method="post" action="?route=usuarios/salvar">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <div class="mb-3">
                <label for="cargo_id" class="form-label">Cargo:</label>
                <select class="form-select" id="cargo_id" name="cargo_id" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach($cargos as $cargo): ?>
                        <option value="<?= htmlspecialchars($cargo['id']) ?>">
                            <?= htmlspecialchars($cargo['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="?route=usuarios/listar" class="btn btn-secondary ms-2">Voltar para Lista</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
