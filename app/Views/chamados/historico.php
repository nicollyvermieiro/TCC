<?php
include __DIR__ . '/../partials/menu.php';
$flash = getFlashMessage();
?>

<?php if ($flash): ?>
<div class="container mt-4">
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<div class="container mt-4">
    <h2 class="mb-3">Histórico de Chamados</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Descrição</th>
                    <th>Localização</th>
                    <th>Status</th>
                    <th>Resolução</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($chamados)): ?>
                    <?php foreach ($chamados as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['descricao']) ?></td>
                            <td><?= htmlspecialchars($c['localizacao']) ?></td>
                            <td><?= htmlspecialchars($c['status']) ?></td>
                            <td><?= htmlspecialchars($c['resolucao']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhum chamado concluído encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
