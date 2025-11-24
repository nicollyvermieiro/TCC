<?php
$cargo_id = $_SESSION['cargo_id'] ?? 3; 
?>

<!-- Componente Offcanvas do Bootstrap -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarMenuLabel">Gerenciamento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-group list-group-flush">
            <a href="?route=chamados/listar" class="list-group-item list-group-item-action"><i class="bi bi-clipboard-check me-2"></i> Complementar Chamados</a>
            <a href="?route=historicoStatus/listar" class="list-group-item list-group-item-action"><i class="bi bi-clock-history me-2"></i> Histórico de Chamados</a>
            <?php if ($cargo_id == 1): ?>
                <a href="?route=qrCode/index" class="list-group-item list-group-item-action"><i class="bi bi-qr-code me-2"></i> Gerar QR Code</a>
                <a href="?route=setores/listar" class="list-group-item list-group-item-action"><i class="bi bi-building me-2"></i> Gerenciar Setores</a>
                <a href="?route=tipos_chamados/listar" class="list-group-item list-group-item-action"><i class="bi bi-folder2-open me-2"></i> Gerenciar Categorias</a>
                <a href="?route=prioridades/listar" class="list-group-item list-group-item-action"><i class="bi bi-lightning-charge-fill me-2"></i> Gerenciar Prioridades</a>
            <?php endif; ?>
        </ul>
    </div>
</div>
