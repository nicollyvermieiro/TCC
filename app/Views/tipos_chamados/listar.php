<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tipos de Chamados</title>
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
        <h1 class="mb-4">Tipos de Chamados</h1>

        <a href="?route=tipos_chamados/criar" class="btn btn-success mb-3">Novo Tipo</a>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tipos as $tipo): ?>
                    <tr>
                        <td><?= htmlspecialchars($tipo['id']) ?></td>
                        <td><?= htmlspecialchars($tipo['nome']) ?></td>
                        <td><?= htmlspecialchars($tipo['descricao']) ?></td>
                        <td>
                            <a href="?route=tipos_chamados/editar&id=<?= $tipo['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="?route=tipos_chamados/excluir&id=<?= $tipo['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente excluir?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
