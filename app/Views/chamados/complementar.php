<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Complementar Chamado</title>
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
        <h2>Complementar Chamado</h2>

        <form method="POST" action="?route=chamados/salvarComplemento">
            <input type="hidden" name="id" value="<?= $dados['id'] ?>">

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição do Problema</label>
                <textarea name="descricao" id="descricao" class="form-control" rows="4" readonly><?= htmlspecialchars($dados['descricao']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="localizacao" class="form-label">Localização</label>
                <input type="text" name="localizacao" id="localizacao" class="form-control" value="<?= htmlspecialchars($dados['localizacao']) ?>" readonly>
            </div>

            <?php if (!empty($dados['anexos'])): ?>
                <div class="mb-3">
                    <label class="form-label">Anexos do Chamado:</label>
                    <div class="row">
                        <?php foreach ($dados['anexos'] as $anexo): ?>
                            <div class="col-md-3 mb-3 text-center">
                                <?php
                                    $caminho = htmlspecialchars($anexo['caminho']);
                                    $ext = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));
                                ?>

                               <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])): ?>
                                <a href="<?= $caminho ?>" target="_blank">
                                    <img src="<?= $caminho ?>" alt="Anexo" class="img-fluid rounded shadow-sm" style="max-height: 180px; object-fit: cover;">
                                </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="tipo_id" class="form-label">Categoria do Chamado</label>
                <select name="tipo_id" id="tipo_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($tipos as $tipo): ?>
                        <option value="<?= $tipo['id'] ?>" <?= $dados['tipo_id'] == $tipo['id'] ? 'selected' : '' ?>>
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
                <label for="prioridade_id" class="form-label">Prioridade</label>
                <select name="prioridade_id" id="prioridade_id" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($prioridades as $prioridade): ?>
                        <option value="<?= $prioridade['id'] ?>" <?= $dados['prioridade_id'] == $prioridade['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($prioridade['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="tecnico_id" class="form-label">Técnico Responsável</label>
                <select name="tecnico_id" id="tecnico_id" class="form-select" required>
                    <option value="">Selecione um técnico...</option>
                    <?php foreach ($tecnicos as $tecnico): ?>
                        <option value="<?= $tecnico['id'] ?>" <?= $dados['tecnico_id'] == $tecnico['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tecnico['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <button type="submit" class="btn btn-success">Salvar Complemento</button>
            <a href="?route=chamados/listar" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
