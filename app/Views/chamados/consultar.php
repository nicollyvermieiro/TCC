<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consultar Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include __DIR__ . '/../partials/menu.php'; ?>

<div class="container mt-4" style="max-width: 600px;">
    <h2 class="mb-4 text-center">Consultar Chamado</h2>

    <!-- Formulário para inserir protocolo -->
    <form method="POST" action="?route=chamados/consultar" class="mb-4">
        <div class="input-group">
            <input type="text" name="protocolo" class="form-control" 
                   placeholder="Digite o número do protocolo" 
                   value="<?= htmlspecialchars($_GET['protocolo'] ?? '') ?>" required>
            <button type="submit" class="btn btn-primary">Consultar</button>
        </div>
    </form>

    <!-- Exibe erro se existir -->
    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <!-- Exibe resultado do chamado -->
    <?php if (!empty($chamado)): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                Chamado #<?= htmlspecialchars($chamado['protocolo']) ?>
            </div>
            <div class="card-body">
                <p><strong>Status:</strong> 
                    <span class="badge bg-info text-dark"><?= htmlspecialchars($chamado['status']) ?></span>
                </p>
                <p><small class="text-muted">Criado em: <?= htmlspecialchars($chamado['criado_em']) ?></small></p>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
