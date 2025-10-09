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
    <title>Gerar Relatório por Período</title>
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
            <h2 class="mb-0">Gerar Relatório por Período</h2>
        </div>

        <?php $flash = getFlashMessage(); if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card p-4">
            <form method="POST" action="?route=relatorios/gerarPorPeriodo">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Data Início</label>
                        <input type="date" name="inicio" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Data Fim</label>
                        <input type="date" name="fim" class="form-control" required>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success"><i class="bi bi-calendar-event"></i> Gerar</button>
                    <a href="?route=relatorios/listar" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
