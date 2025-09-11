<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<?php
include __DIR__ . '/../partials/menu.php';
$flash = getFlashMessage();
$perfil = $_SESSION['cargo_id'] ?? 3; // 1=admin, 2=técnico, 3=usuário
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
    <h2 class="mb-3">Chamados</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Descrição</th>
                    <?php if ($perfil == 1): ?>
                        <th>Categoria</th>
                        <th>Setor</th>
                        <th>Prioridade</th>
                        <th>Usuário</th>
                        <th>Técnico</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    <?php elseif ($perfil == 2): ?>
                        <th>Localização</th>
                        <th>Prioridade</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    <?php else: ?>
                        <th>Data</th>
                        <th>Status</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($chamados)): ?>
                    <?php foreach ($chamados as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['descricao']) ?></td>
                            <?php if ($perfil == 1): ?>
                                <td><?= htmlspecialchars($c['tipo_nome'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['setor_nome'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['prioridade_nome'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['usuario_nome'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['tecnico_nome'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['status']) ?></td>
                                <td class="text-center">
                                    <!-- Botão complementar -->
                                    <?php if (empty($c['tipo_id']) && $c['status'] !== 'Concluído'): ?>
                                        <a href="?route=chamados/complementar&id=<?= $c['id'] ?>" 
                                        class="btn btn-success btn-sm" 
                                        title="Complementar chamado">
                                            <i class="bi bi-plus-circle"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled 
                                                title="<?= $c['status'] === 'Concluído' ? 'Chamado concluído' : 'Chamado complementado' ?>">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                    <?php endif; ?>

                                    <!-- Botão editar (sempre visível) -->
                                    <?php if (empty($c['tipo_id']) && $c['status'] !== 'Concluído'): ?>
                                        <a href="?route=chamados/editar&id=<?= $c['id'] ?>" 
                                        class="btn btn-primary btn-sm" 
                                        title="Editar chamado">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled 
                                                title="<?= $c['status'] === 'Concluído' ? 'Chamado concluído' : 'Chamado complementado' ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    <?php endif; ?>

                                    <!-- Botão excluir (sempre ativo) -->
                                    <a href="?route=chamados/excluir&id=<?= $c['id'] ?>" 
                                        class="btn btn-danger btn-sm btn-excluir" 
                                        title="Excluir chamado">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                </td>
                            <?php elseif ($perfil == 2): ?>
                                <td><?= htmlspecialchars($c['localizacao']) ?></td>
                                <td><?= htmlspecialchars($c['prioridade_nome'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['tipo_nome'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['status']) ?></td>
                                <td class="text-center">
                                    <?php if ($c['status'] !== 'Concluído'): ?>
                                        <a href="?route=manutencoes/criar&id=<?= $c['id'] ?>" 
                                           class="btn btn-success btn-sm" 
                                           title="Registrar manutenção">
                                            <i class="bi bi-tools"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled 
                                                title="Chamado concluído">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            <?php else: ?>
                                <td><?= htmlspecialchars($c['criado_em']) ?></td>
                                <td><?= htmlspecialchars($c['status']) ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= ($perfil==1?8:($perfil==2?4:2)) ?>" class="text-center">Nenhum chamado encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-excluir').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const href = this.getAttribute('href');
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });
});
</script>

</body>
</html>
