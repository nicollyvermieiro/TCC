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
        body {
            background-color: #f8f9fa;
            font-family: "Segoe UI", Tahoma, Verdana, sans-serif;
        }
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
        .card { border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
        th { white-space: nowrap; }
        td { vertical-align: middle; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>


<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-bar-chart"></i> Resultado do Relatório
                </h4>

                <div>
                    <a href="?route=relatorios/listar" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>

            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Descrição</th>
                        <th>Localização</th>
                        <th>Setor</th>
                        <th>Tipo</th>
                        <th>Técnico</th>
                        <th>Status</th>
                        <th>Prioridade</th>
                        <th>Data de Abertura</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dados)): ?>
                        <?php foreach ($dados as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['id']) ?></td>
                                <td><?= htmlspecialchars($item['descricao']) ?></td>
                                <td><?= htmlspecialchars($item['localizacao']) ?></td>
                                <td><?= htmlspecialchars($item['setor']) ?></td>
                                <td><?= htmlspecialchars($item['tipo']) ?></td>
                                <td><?= htmlspecialchars($item['tecnico']) ?></td>
                                <td><?= htmlspecialchars($item['status']) ?></td>
                                <td><?= htmlspecialchars($item['prioridade']) ?></td>
                                <td><?= htmlspecialchars($item['data']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">Nenhum chamado encontrado para o período selecionado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                <a href="?route=relatorios/exportarPdf&periodo_inicio=<?= $inicio ?>&periodo_fim=<?= $fim ?>&setor=<?= $setor ?>&status=<?= $status ?>&tecnico=<?= $tecnico ?>" class="btn btn-outline-danger btn-sm me-1">
                    <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
                </a>

                <a href="?route=relatorios/exportarCsv&periodo_inicio=<?= $inicio ?>&periodo_fim=<?= $fim ?>&setor=<?= $setor ?>&status=<?= $status ?>&tecnico=<?= $tecnico ?>" class="btn btn-outline-success btn-sm me-1">
                    <i class="bi bi-filetype-csv"></i> Exportar CSV
                </a>

                <a href="?route=relatorios/exportarExcel&periodo_inicio=<?= $inicio ?>&periodo_fim=<?= $fim ?>&setor=<?= $setor ?>&status=<?= $status ?>&tecnico=<?= $tecnico ?>" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                </a>

            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
