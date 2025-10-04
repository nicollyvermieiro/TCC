<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<?php
include __DIR__ . '/../partials/menu.php';
$flash = getFlashMessage();
$perfil = $_SESSION['cargo_id'] ?? 3; // 1=admin, 2=técnico, 3=usuário
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

<div class="container mt-4">
    <h2 class="mb-3">Histórico de Chamados Concluídos</h2>

    <!-- Formulário de filtro -->
    <form method="GET" class="row g-3 mb-3">
        <input type="hidden" name="route" value="historicoStatus/listar">

        <div class="col-md-3">
            <input type="text" name="q" class="form-control" placeholder="Pesquisar por descrição" 
                   value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        </div>

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

        <div class="col-12 text-end mt-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filtrar</button>
        </div>
    </form>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
