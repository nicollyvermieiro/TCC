<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Manutenção do Chamado #<?= htmlspecialchars($dados['id'] ?? '') ?></title>
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
        <h2>Registrar Manutenção do Chamado #<?= htmlspecialchars($dados['id'] ?? '') ?></h2>

        <?php if (!empty($dados)): ?>
        <form method="POST" action="?route=manutencoes/salvar">
            <input type="hidden" name="chamado_id" value="<?= htmlspecialchars($dados['id']) ?>">

            <div class="mb-3">
                <label class="form-label">Descrição do Chamado</label>
                <textarea class="form-control" rows="4" readonly><?= htmlspecialchars($dados['descricao'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Localização</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($dados['localizacao'] ?? '') ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Técnico Responsável</label>
                <input type="hidden" name="tecnico_id" value="<?= htmlspecialchars($dados['tecnico_id'] ?? '') ?>">
                <input type="text" class="form-control" value="<?= htmlspecialchars($dados['tecnico_nome'] ?? '') ?>" readonly>

            </div>

            <?php if (!empty($dados['anexos'])): ?>
            <div class="mb-3">
                <label class="form-label">Anexos do Chamado:</label>
                <ul class="list-group">
                    <?php foreach ($dados['anexos'] as $anexo): ?>
                        <li class="list-group-item">
                            <a href="<?= htmlspecialchars($anexo['caminho']) ?>" target="_blank">
                                <?= htmlspecialchars($anexo['nome_arquivo']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

            <div class="mb-3">
                <label for="descricao_servico" class="form-label">Descrição do Serviço</label>
                <textarea name="descricao_servico" id="descricao_servico" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="pecas_trocadas" class="form-label">Peças Trocadas (opcional)</label>
                <input type="text" name="pecas_trocadas" id="pecas_trocadas" class="form-control">
            </div>

            <div class="mb-3">
                <label for="observacoes" class="form-label">Observações (opcional)</label>
                <textarea name="observacoes" id="observacoes" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Registrar Manutenção</button>
            <a href="?route=chamados/listar" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
        <?php else: ?>
            <div class="alert alert-warning">Chamado não encontrado.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
