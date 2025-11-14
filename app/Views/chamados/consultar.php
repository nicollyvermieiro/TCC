<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consultar Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include __DIR__ . '/../partials/menu.php'; ?>

<div class="container mt-4" style="max-width: 600px;">
    <h2 class="mb-4 text-center">Consultar Chamado</h2>

    <!-- Formulário para inserir protocolo -->
    <form method="POST" action="?route=chamados/consultar" class="mb-4">
        <div class="input-group">
            <input type="text" id="protocoloInput" name="protocolo" class="form-control" 
                   placeholder="Digite o número do protocolo" 
                   value="<?= htmlspecialchars($_GET['protocolo'] ?? '') ?>" required>
            <button type="submit" class="btn btn-primary">Consultar</button>
        </div>
    </form>

    <?php if (!empty($_GET['protocolo'])): ?>
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-outline-secondary btn-sm" onclick="copiarProtocolo()">📋 Copiar Protocolo</button>
        </div>
    <?php endif; ?>

    <!-- Exibe erro se existir -->
    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <!-- Exibe resultado do chamado -->
    <?php if (!empty($chamado)): 
        // Ajustar horário para MS (GMT-3)
        $dt = new DateTime($chamado['criado_em'], new DateTimeZone('UTC'));
        $dt->setTimezone(new DateTimeZone('America/Campo_Grande'));
        $criadoEm = $dt->format('d/m/Y H:i:s');
        
        // Definir cores do status
        $status = $chamado['status'];
        $statusClass = match($status) {
            'Aguardando Atendimento' => 'bg-warning text-dark',
            'Em Atendimento' => 'bg-info text-white',
            'Concluído' => 'bg-success text-white',
            'Cancelado' => 'bg-danger text-white',
            default => 'bg-secondary text-white',
        };
    ?>
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-primary text-white">
                Chamado #<?= htmlspecialchars($chamado['protocolo']) ?>
            </div>
            <div class="card-body">
                <p><strong>Status:</strong> 
                    <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
                </p>
                <p><strong>Descrição:</strong> <?= htmlspecialchars($chamado['descricao']) ?></p>
                <p><strong>Localização:</strong> <?= htmlspecialchars($chamado['localizacao']) ?></p>
                <p><small class="text-muted">Criado em: <?= $criadoEm ?></small></p>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function copiarProtocolo() {
    const protocoloInput = document.getElementById('protocoloInput');
    protocoloInput.select();
    protocoloInput.setSelectionRange(0, 99999); // Para dispositivos móveis
    document.execCommand('copy');

    // SweetAlert
    Swal.fire({
        icon: 'success',
        title: 'Protocolo copiado!',
        text: protocoloInput.value,
        timer: 2000,
        showConfirmButton: false
    });
}
</script>

    <!-- <footer class="bg-primary text-white text-center py-1 mt-4 shadow-sm shadow-sm">
        <div style="font-size: 0.8rem; opacity: 0.8;">
            &copy; 2025 ManutSmart. Todos os direitos reservados.
        </div>
    </footer> -->
</body>
</html>
