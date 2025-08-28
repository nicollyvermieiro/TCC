<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Chamado</title>
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
        <h2>Editar Chamado</h2>

        <form method="POST" action="?route=chamados/atualizar">
            <input type="hidden" name="id" value="<?= $dados['id'] ?>">

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea name="descricao" id="descricao" class="form-control" rows="3" required><?= htmlspecialchars($dados['descricao']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="tipo_chamado_id" class="form-label">Categoria do Chamado</label>
                <select name="tipo_chamado_id" id="tipo_chamado_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($tipos as $tipo): ?>
                        <option value="<?= $tipo['id'] ?>" <?= $dados['tipo_chamado_id'] == $tipo['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tipo['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="setor_id" class="form-label">Setor Responsável</label>
                <select name="setor_id" id="setor_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($setores as $setor): ?>
                        <option value="<?= $setor['id'] ?>" <?= $dados['setor_id'] == $setor['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($setor['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="prioridade" class="form-label">Prioridade</label>
                <select name="prioridade" id="prioridade" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($prioridades as $prioridade): ?>
                        <option value="<?= $prioridade['id'] ?>" <?= $dados['prioridade'] == $prioridade['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($prioridade['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="Aberto" <?= $dados['status'] == 'Aberto' ? 'selected' : '' ?>>Aberto</option>
                    <option value="Em Andamento" <?= $dados['status'] == 'Em Andamento' ? 'selected' : '' ?>>Em Andamento</option>
                    <option value="Concluído" <?= $dados['status'] == 'Concluído' ? 'selected' : '' ?>>Concluído</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="?route=chamados/listar" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
