<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        body { background-color: #f8f9fa; }
        .card {
            max-width: 1150px;
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
        .btn-back {
            text-decoration: none;
            color: #0d6efd;
        }
        .btn-back:hover {
            color: #0a58ca;
            transform: scale(1.1);
            transition: 0.2s;
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0b5ed7;
        }
        .table th, .table td { vertical-align: middle; }
        .top-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .form-select {
            border-radius: 8px;
        }
    </style>
</head>

<body>
<?php
include __DIR__ . '/../partials/menu.php';
$flash = getFlashMessage();
$perfil = $_SESSION['cargo_id'] ?? 3;
$usuario_id = $_SESSION['usuario_id'] ?? null;
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
        <a href="javascript:history.back()" class="fs-3 btn-back" title="Voltar para página anterior">
            <i class="bi bi-arrow-left-circle"></i>
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <i class="bi bi-clock-history"></i> Histórico de Chamados Concluídos
        </div>
        <div class="card-body p-3">
            <!-- Formulário de filtro -->
            <form method="GET" class="row g-3 mb-4">
                <input type="hidden" name="route" value="historicoStatus/listar">

                <?php if ($perfil != 2): ?>
                <div class="col-md-3">
                    <select name="setor" class="form-select">
                        <option value="">Todos os Setores</option>
                        <?php foreach ($setores as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= ($_GET['setor'] ?? '') == $s['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <?php if ($perfil != 2): ?>
                <div class="col-md-3">
                    <select name="tecnico" class="form-select">
                        <option value="">Todos os Técnicos</option>
                        <?php foreach ($tecnicos as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= ($_GET['tecnico'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <?php if ($perfil == 2): ?>
                <div class="col-md-3">
                    <select name="localizacao" class="form-select">
                        <option value="">Todas as Localizações</option>
                        <?php foreach ($localizacoes as $loc): ?>
                            <option value="<?= htmlspecialchars($loc['localizacao']) ?>" 
                                <?= ($_GET['localizacao'] ?? '') == $loc['localizacao'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($loc['localizacao']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="col-md-3">
                    <select name="categoria" class="form-select">
                        <option value="">Todas as Categorias</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($_GET['categoria'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="prioridade" class="form-select">
                        <option value="">Todas as Prioridades</option>
                        <?php foreach ($prioridades as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= ($_GET['prioridade'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
            </form>

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
                            <?php elseif ($perfil == 2): ?>
                                <th>Localização</th>
                                <th>Prioridade</th>
                                <th>Categoria</th>
                                <th>Status</th>
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
                                    <td><?= htmlspecialchars($c['novo_status']) ?></td>
                                <?php elseif ($perfil == 2): ?>
                                    <td><?= htmlspecialchars($c['localizacao']) ?></td>
                                    <td><?= htmlspecialchars($c['prioridade_nome'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($c['tipo_nome'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($c['novo_status']) ?></td>
                                <?php else: ?>
                                    <td><?= htmlspecialchars($c['criado_em']) ?></td>
                                    <td><?= htmlspecialchars($c['novo_status']) ?></td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= ($perfil==1?7:($perfil==2?4:2)) ?>" class="text-center">
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

    <footer class="bg-primary text-white text-center py-1 mt-4 fixed-bottom">
        <div style="font-size: 0.8rem; opacity: 0.8;">
            &copy; 2025 ManutSmart. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
