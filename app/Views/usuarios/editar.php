<?php require_once __DIR__ . '/../../helpers/session.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
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

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Editar Usuário</h4>
            </div>
            <div class="card-body">
                <form method="post" action="?route=usuarios/atualizar">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($dados['id']) ?>">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($dados['nome']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($dados['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha: <small>(Deixe em branco para não alterar)</small></label>
                        <input type="password" class="form-control" id="senha" name="senha" autocomplete="new-password">
                    </div>

                    <div class="mb-3">
                        <label for="cargo_id" class="form-label">Cargo:</label>
                        <select class="form-select" id="cargo_id" name="cargo_id" required>
                            <option value="">-- Selecione --</option>
                            <?php foreach($cargos as $cargo): ?>
                                <option value="<?= htmlspecialchars($cargo['id']) ?>" <?= $dados['cargo_id'] == $cargo['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cargo['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Atualizar</button>
                    <a href="?route=usuarios/listar" class="btn btn-secondary w-100 mt-2">Voltar para Lista</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</b
