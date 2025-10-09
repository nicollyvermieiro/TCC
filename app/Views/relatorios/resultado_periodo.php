<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../helpers/session.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['cargo_id'] ?? null) != 1) {
    setFlashMessage("Acesso negado. Área restrita a administradores.", "danger");
    header("Location: ?route=auth/dashboard");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado do Relatório</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .btn-voltar { background-color: #0d6efd; color:#fff; border-radius:12px; padding:6px 14px; }
        body { background-color: #f8f9fa; font-family: "Segoe UI", Tahoma, Verdana, sans-serif; }
        .card { border-radius: 12px; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <div class="container mt-5">
        <div class="d-flex align-items-center mb-3">
            <button class="btn-voltar me-3" onclick="window.history.back();"><i class="bi bi-arrow-left"></i></button>
            <h2 class="mb-0">Resultado do Relatório</h2>
        </div>

        <?php $flash = getFlashMessage(); if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($dados)): ?>
            <div class="card p-3">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Chamado</th>
                                <th>Setor</th>
                                <th>Tipo</th>
                                <th>Técnico</th>
                                <th>Status</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dados as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['id']) ?></td>
                                    <td><?= htmlspecialchars($item['setor_nome'] ?? $item['setor']) ?></td>
                                    <td><?= htmlspecialchars($item['tipo_nome'] ?? $item['tipo']) ?></td>
                                    <td><?= htmlspecialchars($item['tecnico_nome'] ?? $item['tecnico']) ?></td>
                                    <td><?= htmlspecialchars($item['status']) ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($item['criado_em'] ?? $item['data_abertura'] ?? ''))) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <a href="?route=relatorios/filtro_periodo" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
                <a href="?route=relatorios/listar" class="btn btn-outline-primary">Relatórios Salvos</a>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">Nenhum chamado encontrado no período informado.</div>
            <div class="mt-3">
                <a href="?route=relatorios/filtro_periodo" class="btn btn-secondary">Voltar</a>
            </div>
        <?php endif; ?>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
