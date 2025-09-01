<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Chamados</title>
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
                <!-- Botão voltar -->
        <button class="btn btn-secondary mb-3" onclick="window.history.back();">←</button>

        <h2>Chamados</h2>

    <a href="?route=chamados/criar" class="btn btn-success mb-3">Novo Chamado</a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Setor</th>
                    <th>Prioridade</th>
                    <th>Status</th>
                    <th>Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($chamados)): ?>
                    <?php foreach ($chamados as $chamado): ?>
                        <tr>
                            <td><?= $chamado['id'] ?></td>
                            <td><?= htmlspecialchars($chamado['descricao']) ?></td>
                            <td><?= htmlspecialchars($chamado['tipo_nome'] ?? '') ?></td>
                            <td><?= htmlspecialchars($chamado['setor_nome'] ?? '') ?></td>
                            <td><?= htmlspecialchars($chamado['prioridade_nome'] ?? '') ?></td>
                            <td><?= htmlspecialchars($chamado['status']) ?></td>
                            <td><?= htmlspecialchars($chamado['usuario_nome'] ?? '') ?></td>
                            <td>
                                <a href="?route=chamados/editar&id=<?= $chamado['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                                <a href="?route=chamados/excluir&id=<?= $chamado['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este chamado?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Nenhum chamado encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
