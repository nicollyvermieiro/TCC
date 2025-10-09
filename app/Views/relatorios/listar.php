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
    <title>Relatórios Gerados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Mantive o estilo do exemplo para consistência */
        .btn-voltar {
            background-color: #0d6efd;
            color: #fff;
            border: none;
            padding: 6px 14px;
            font-size: 0.9rem;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-voltar:hover { transform: translateY(-2px); }
        body { background-color: #f8f9fa; font-family: "Segoe UI", Tahoma, Verdana, sans-serif; }
        .card { border-radius: 12px; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <div class="container mt-5">
        <div class="d-flex align-items-center mb-3">
            <button class="btn-voltar me-3" onclick="window.history.back();"><i class="bi bi-arrow-left"></i></button>
            <h2 class="mb-0">Relatórios Gerados</h2>
        </div>

        <?php $flash = getFlashMessage(); if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="?route=relatorios/criar" class="btn btn-success"><i class="bi bi-plus-circle"></i> Novo Relatório</a>
                <a href="?route=relatorios/gerarPorPeriodo" class="btn btn-primary ms-2"><i class="bi bi-calendar-event"></i> Gerar por Período</a>
            </div>
        </div>

        <?php if (!empty($relatorios)): ?>
            <div class="card p-3">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Descrição</th>
                                <th>Data de Geração</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($relatorios as $r): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['id']) ?></td>
                                    <td><?= htmlspecialchars($r['titulo']) ?></td>
                                    <td style="max-width:400px; word-wrap:break-word;"><?= htmlspecialchars($r['descricao']) ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($r['data_geracao']))) ?></td>
                                    <td class="text-center">
                                        <a href="?route=relatorios/editar&id=<?= $r['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                        <a href="?route=relatorios/excluir&id=<?= $r['id'] ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Tem certeza que deseja excluir este relatório?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">Nenhum relatório gerado ainda.</div>
        <?php endif; ?>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
