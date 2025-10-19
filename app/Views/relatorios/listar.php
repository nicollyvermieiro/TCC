<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../helpers/session.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['cargo_id'] ?? null) != 1) {
    setFlashMessage("Acesso negado. Área restrita a administradores.", "danger");
    header("Location: ?route=auth/dashboard");
    exit;
}

// Variáveis opcionais que o controller pode passar
$setores  = $setores ?? [];   // array de ['id'=>'','nome'=>'']
$tecnicos = $tecnicos ?? [];  // array de ['id'=>'','nome'=>'']
$relatorios = $relatorios ?? []; // array de relatórios já gerados


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatórios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
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
        .small-muted { font-size:0.9rem; color:#666; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <div class="container mt-5">
        <div class="d-flex align-items-center mb-3">
            <button class="btn-voltar me-3" onclick="window.history.back();"><i class="bi bi-arrow-left"></i></button>
            <h2 class="mb-0">Relatórios</h2>
        </div>

        <?php $flash = getFlashMessage(); if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Formulário de geração (lado esquerdo) -->
            <div class="col-12 col-md-5">
                <div class="card p-4 h-100">
                    <h5 class="mb-3"><i class="bi bi-calendar-event"></i> Gerar Relatório</h5>
                    <p class="small-muted">Selecione o período e (opcional) filtre por setor, status ou técnico.</p>

                    <form method="POST" action="?route=relatorios/gerarPorPeriodo">
                        <div class="mb-3">
                            <label class="form-label">Data Início</label>
                            <input type="date" name="inicio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Data Fim</label>
                            <input type="date" name="fim" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Setor (opcional)</label>
                            <select name="setor" class="form-select">
                                <option value="">Todos os Setores</option>
                                <?php foreach ($setores as $s): ?>
                                    <option value="<?= htmlspecialchars($s['id']) ?>"><?= htmlspecialchars($s['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status (opcional)</label>
                            <select name="status" class="form-select">
                                <option value="">Todos os Status</option>
                                <?php foreach ($statusList as $st): ?>
                                    <option value="<?= htmlspecialchars($st) ?>"><?= htmlspecialchars($st) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Técnico (opcional)</label>
                            <select name="tecnico" class="form-select">
                                <option value="">Todos os Técnicos</option>
                                <?php foreach ($tecnicos as $t): ?>
                                    <option value="<?= htmlspecialchars($t['id']) ?>"><?= htmlspecialchars($t['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex gap-2 justify-content-center mt-3">
                            <button type="submit" class="btn btn-success"><i class="bi bi-file-earmark-text"></i> Gerar</button>
                            <a href="?route=relatorios/listar" class="btn btn-outline-secondary">Limpar</a>
                        </div>

                        <div class="text-muted text-center mt-2 small">Após gerar, será exibida a tabela de resultados com opções de exportação.</div>
                    </form>
                </div>
            </div>

            <!-- Lista de relatórios gerados / histórico (lado direito) -->
            <div class="col-12 col-md-7">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="mb-1"><i class="bi bi-archive"></i> Relatórios Gerados</h5>
                            <div class="small-muted">Histórico de relatórios salvos (se houver)</div>
                        </div>
                        <div>
                            <!-- aqui futuramente pode ter botão para exportar todos -->
                        </div>
                    </div>

                    <?php if (!empty($relatorios)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tipo</th>
                                        <th>Período</th>
                                        <th>Gerado Por</th>
                                        <th>Data de Geração</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($relatorios as $r): ?>
                                        <?php
                                            // Garantir que não quebre se alguma data estiver nula
                                            $inicio = !empty($r['periodo_inicio']) ? date('d/m/Y', strtotime($r['periodo_inicio'])) : '—';
                                            $fim    = !empty($r['periodo_fim']) ? date('d/m/Y', strtotime($r['periodo_fim'])) : '—';
                                            $geracao = !empty($r['data_geracao']) ? date('d/m/Y H:i', strtotime($r['data_geracao'])) : '—';
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($r['id']) ?></td>
                                            <td><?= htmlspecialchars($r['tipo']) ?></td>
                                            <td><?= htmlspecialchars($inicio) ?> até <?= htmlspecialchars($fim) ?></td>
                                            <td><?= htmlspecialchars($r['usuario_nome'] ?? '—') ?></td>
                                            <td><?= htmlspecialchars($geracao) ?></td>
                                            <td class="text-center d-flex justify-content-center gap-1">
                                                <!-- Botão Excluir -->
                                                <a href="?route=relatorios/excluir&id=<?= $r['id'] ?>" 
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Tem certeza que deseja excluir este relatório?')"
                                                title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </a>

                                                <!-- Botão Exportar PDF -->
                                                <a href="?route=relatorios/exportarPdf&id=<?= $r['id'] ?>" 
                                                class="btn btn-sm btn-outline-danger"
                                                title="Exportar PDF" target="_blank">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a>

                                                <!-- Botão Exportar Excel -->
                                                <a href="?route=relatorios/exportarExcel&id=<?= $r['id'] ?>" 
                                                class="btn btn-sm btn-outline-success"
                                                title="Exportar Excel" target="_blank">
                                                    <i class="bi bi-file-earmark-excel"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center mb-0">Nenhum relatório gerado ainda.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
