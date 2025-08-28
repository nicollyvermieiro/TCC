<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Chamado</title>
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
        <h2>Criar Chamado</h2>

        <form method="POST" action="?route=chamados/salvar">
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea name="descricao" id="descricao" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="tipo_chamado_id" class="form-label">Categoria do Chamado</label>
                <select name="tipo_chamado_id" id="tipo_chamado_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($tipos as $tipo): ?>
                        <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="setor_id" class="form-label">Setor Responsável</label>
                <select name="setor_id" id="setor_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($setores as $setor): ?>
                        <option value="<?= $setor['id'] ?>"><?= htmlspecialchars($setor['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="prioridade" class="form-label">Prioridade</label>
                <select name="prioridade" id="prioridade" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($prioridades as $prioridade): ?>
                        <option value="<?= $prioridade['id'] ?>"><?= htmlspecialchars($prioridade['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="?route=chamados/listar" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
