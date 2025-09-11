<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Manutenções Registradas</title>
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
        <h2>Manutenções</h2>

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" href="#pendentes" data-bs-toggle="tab">Pendentes</a></li>
            <li class="nav-item"><a class="nav-link" href="#concluidas" data-bs-toggle="tab">Concluídas</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="pendentes">
                <?php if (!empty($pendentes)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Chamado</th>
                                <th>Técnico</th>
                                <th>Status</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendentes as $m): ?>
                                <tr>
                                    <td><?= htmlspecialchars($m['id']) ?></td>
                                    <td><?= htmlspecialchars($m['chamado_descricao']) ?></td>
                                    <td><?= htmlspecialchars($m['tecnico_nome'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($m['status_chamado']) ?></td>
                                    <td><?= htmlspecialchars($m['data_registro']) ?></td>
                                    <td>
                                        <a href="?route=manutencoes/editar&id=<?= $m['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                        <a href="?route=manutencoes/excluir&id=<?= $m['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir esta manutenção?')">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">Nenhuma manutenção pendente.</div>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="concluidas">
                <?php if (!empty($concluidas)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Chamado</th>
                                <th>Técnico</th>
                                <th>Status</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($concluidas as $m): ?>
                                <tr>
                                    <td><?= htmlspecialchars($m['id']) ?></td>
                                    <td><?= htmlspecialchars($m['chamado_descricao']) ?></td>
                                    <td><?= htmlspecialchars($m['tecnico_nome'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($m['status_chamado']) ?></td>
                                    <td><?= htmlspecialchars($m['data_registro']) ?></td>
                                    <td>
                                        <a href="?route=manutencoes/editar&id=<?= $m['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                        <a href="?route=manutencoes/excluir&id=<?= $m['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir esta manutenção?')">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">Nenhuma manutenção concluída.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
