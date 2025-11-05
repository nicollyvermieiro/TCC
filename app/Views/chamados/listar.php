<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .card {
            max-width: 1100px;
            margin: 20px auto;
            border-radius: 12px;
        }
        .card-header {
            background-color: #0d6efd;
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .btn-primary, .btn-success {
            background-color: #0d6efd;
            border-color: #0d6efd;
            font-weight: 500;
        }
        .btn-primary:hover, .btn-success:hover {
            background-color: #0b5ed7;
            border-color: #0b5ed7;
        }
        .btn-complementar {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }
        .btn-complementar:hover {
            background-color: #157347 !important;
            border-color: #157347 !important;
        }
        .table th, .table td { vertical-align: middle; }
        .btn-sm { font-size: 0.85rem; }
        .btn-back {
            text-decoration: none;
            color: #0d6efd;
        }
        .btn-back:hover {
            color: #0a58ca;
            transform: scale(1.1);
            transition: 0.2s;
        }
        .top-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
    </style>
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

<div class="container mt-3">
    <div class="top-actions">
        <a href="?route=chamados/gerenciar"
           class="text-primary fs-3 mb-2 btn-back"
           title="Voltar ao painel"
           style="text-decoration: none;">
            <i class="bi bi-arrow-left-circle"></i>
        </a>
</div>

    <div class="card shadow-sm">
        <div class="card-header">Lista de Chamados</div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover mb-0">
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
                                            <!-- Botão complementar (verde) -->
                                            <?php if (empty($c['tipo_id']) && $c['status'] !== 'Concluído'): ?>
                                                <a href="?route=chamados/complementar&id=<?= $c['id'] ?>" 
                                                class="btn btn-success btn-sm btn-complementar" 
                                                title="Complementar chamado">
                                                    <i class="bi bi-plus-circle"></i>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-secondary btn-sm" disabled 
                                                    title="<?= $c['status'] === 'Concluído' ? 'Chamado concluído' : 'Chamado complementado' ?>">
                                                    <i class="bi bi-plus-circle"></i>
                                                </button>
                                            <?php endif; ?>

                                            <!-- Botão editar -->
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

                                            <!-- Botão excluir -->
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
                                                <button class="btn btn-secondary btn-sm" disabled title="Chamado concluído">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    <?php else: ?>
                                        <td>
                                    <?= !empty($c['criado_em']) 
                                        ? date('d/m/Y H:i', strtotime($c['criado_em'])) 
                                        : '—' ?>
                                        </td>
                                        <td><?= htmlspecialchars($c['status']) ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= ($perfil==1?8:($perfil==2?4:2)) ?>" class="text-center">
                                    Nenhum chamado encontrado.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
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
