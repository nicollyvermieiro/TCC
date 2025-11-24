<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .card { margin: 20px auto; border-radius: 12px; }
        .card-header {
            background-color: #0d6efd;
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .table th, .table td { vertical-align: middle; }
        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.3rem;
            flex-wrap: nowrap;
        }
        .col-status { width: 140px; }
        .col-acoes { width: 130px; }
    </style>
</head>

<body>
<?php
include __DIR__ . '/../partials/menu.php';

$flash = getFlashMessage( );
$perfil = $_SESSION['cargo_id'] ?? 1;
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
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Lista de Chamados</span>
            
            <!-- MUDANÇA AQUI: de "btn-light" para "btn-primary" -->
            <a href="?route=auth/dashboard" class="btn btn-primary btn-sm">
                <i class="bi bi-arrow-left"></i> 
            </a>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover mb-0">
                    <thead class="table-dark">
                        <!-- O conteúdo da sua tabela permanece o mesmo -->
                        <tr>
                            <th>Descrição</th>
                            <?php if ($perfil == 1): ?>
                                <th>Categoria</th>
                                <th>Setor</th>
                                <th>Prioridade</th>
                                <th>Usuário</th>
                                <th>Técnico</th>
                                <th class="col-status">Status</th>
                                <th class="text-center col-acoes">Ações</th>
                            <?php elseif ($perfil == 2): ?>
                                <th>Localização</th>
                                <th>Prioridade</th>
                                <th>Categoria</th>
                                <th class="col-status">Status</th>
                                <th class="text-center col-acoes">Ações</th>
                            <?php else: ?>
                                <th>Data</th>
                                <th class="col-status">Status</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- O conteúdo do corpo da sua tabela permanece o mesmo -->
                        <?php if (!empty($chamados)): ?>
                            <?php foreach ($chamados as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['descricao']) ?></td>
                                    <?php if ($perfil == 1): ?>
                                        <td><?= htmlspecialchars($c['tipo_nome'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($c['setor_nome'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($c['prioridade_nome'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($c['usuario_nome'] ?: ($c['usuario_temporario'] ?? 'Visitante')) ?></td>
                                        <td><?= htmlspecialchars($c['tecnico_nome'] ?? 'Não atribuído') ?></td>
                                        <td><?= htmlspecialchars($c['status']) ?></td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <?php if (empty($c['tipo_id']) && $c['status'] !== 'Concluído'): ?>
                                                    <a href="?route=chamados/complementar&id=<?= $c['id'] ?>" class="btn btn-success btn-sm" title="Complementar chamado"><i class="bi bi-plus-circle"></i></a>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary btn-sm" disabled title="<?= $c['status'] === 'Concluído' ? 'Chamado concluído' : 'Chamado já complementado' ?>"><i class="bi bi-plus-circle"></i></button>
                                                <?php endif; ?>
                                                <a href="?route=chamados/editar&id=<?= $c['id'] ?>" class="btn btn-primary btn-sm" title="Editar chamado"><i class="bi bi-pencil-square"></i></a>
                                                <a href="?route=chamados/excluir&id=<?= $c['id'] ?>" class="btn btn-danger btn-sm btn-excluir" title="Excluir chamado"><i class="bi bi-trash"></i></a>
                                            </div>
                                        </td>
                                    <?php elseif ($perfil == 2): ?>
                                        <td><?= htmlspecialchars($c['localizacao']) ?></td>
                                        <td><?= htmlspecialchars($c['prioridade_nome'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($c['tipo_nome'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($c['status']) ?></td>
                                        <td class="text-center">
                                            <?php if ($c['status'] !== 'Concluído'): ?>
                                                <a href="?route=manutencoes/criar&id=<?= $c['id'] ?>" class="btn btn-success btn-sm" title="Registrar manutenção"><i class="bi bi-tools"></i></a>
                                            <?php else: ?>
                                                <button class="btn btn-secondary btn-sm" disabled title="Chamado concluído"><i class="bi bi-check-circle"></i></button>
                                            <?php endif; ?>
                                        </td>
                                    <?php else: ?>
                                        <td><?= !empty($c['criado_em']) ? date('d/m/Y H:i', strtotime($c['criado_em'])) : '—' ?></td>
                                        <td><?= htmlspecialchars($c['status']) ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= ($perfil==1?8:($perfil==2?5:3)) ?>" class="text-center">Nenhum chamado encontrado.</td>
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
document.querySelectorAll('.btn-excluir' ).forEach(btn => {
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
