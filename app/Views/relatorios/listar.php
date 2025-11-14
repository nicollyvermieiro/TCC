<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../helpers/session.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['cargo_id'] ?? null) != 1) {
    setFlashMessage("Acesso negado. Área restrita a administradores.", "danger");
    header("Location: ?route=auth/dashboard");
    exit;
}

$setores  = $setores ?? [];
$tecnicos = $tecnicos ?? [];
$relatorios = $relatorios ?? [];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatórios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .btn-voltar {
            color: #0d6efd;
            font-size: 1.8rem;
            text-decoration: none;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .btn-voltar:hover {
            transform: translateX(-3px);
            color: #0b5ed7;
        }

        body {
            background-color: #f8f9fa;
            font-family: "Segoe UI", Tahoma, Verdana, sans-serif;
        }

        .card {
            border-radius: 12px;
        }

        .small-muted {
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <div class="container mt-4">
        <div class="d-flex flex-column align-items-start mb-4">
            <a href="?route=auth/dashboard"
                class="text-primary fs-3 mb-2 btn-back"
                title="Voltar ao painel"
                style="text-decoration: none;">
                    <i class="bi bi-arrow-left-circle"></i>
            </a>
            <h2 class="fw-semibold text-dark mt-2">Relatórios</h2>
        </div>

        <?php $flash = getFlashMessage(); if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
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

                        <div class="text-muted text-center mt-2 small">
                            Após gerar, será exibida a tabela de resultados com opções de exportação.
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 col-md-7">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="mb-1"><i class="bi bi-archive"></i> Relatórios Gerados</h5>
                            <div class="small-muted">Histórico de relatórios salvos (se houver)</div>
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
                                            $inicio = !empty($r['periodo_inicio']) ? date('d/m/Y', strtotime($r['periodo_inicio'])) : '—';
                                            $fim = !empty($r['periodo_fim']) ? date('d/m/Y', strtotime($r['periodo_fim'])) : '—';
                                            $geracao = !empty($r['data_geracao']) ? date('d/m/Y H:i', strtotime($r['data_geracao'])) : '—';
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($r['id']) ?></td>
                                            <td><?= htmlspecialchars($r['tipo']) ?></td>
                                            <td><?= htmlspecialchars($inicio) ?> até <?= htmlspecialchars($fim) ?></td>
                                            <td><?= htmlspecialchars($r['usuario_nome'] ?? '—') ?></td>
                                            <td><?= htmlspecialchars($geracao) ?></td>
                                            <td class="text-center d-flex justify-content-center gap-1">
                                                <button class="btn btn-sm btn-danger btn-excluir" 
                                                        data-id="<?= $r['id'] ?>" 
                                                        title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <a href="?route=relatorios/exportarPdf&id=<?= $r['id'] ?>" 
                                                   class="btn btn-sm btn-outline-danger" title="Exportar PDF" target="_blank">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a>
                                                <a href="?route=relatorios/exportarExcel&id=<?= $r['id'] ?>" 
                                                   class="btn btn-sm btn-outline-success" title="Exportar Excel" target="_blank">
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

<script>
document.querySelectorAll('.btn-excluir').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;

        Swal.fire({
            title: 'Tem certeza?',
            text: "Esta ação excluirá o relatório permanentemente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `?route=relatorios/excluir&id=${id}`;
            }
        });
    });
});
</script>

    <footer class="bg-primary text-white text-center py-1 mt-4 shadow-sm shadow-sm">
        <div class="container small">
            <strong>ManutSmart</strong> — Software para gestão de manutenção inteligente<br>
            Desenvolvido por <strong>Nicolly Vermieiro Ferreira</strong> — UNIGRAN | 2025<br>
        </div>
        <br>
        <div style="font-size: 0.8rem; opacity: 0.8;">
            &copy; 2025 ManutSmart. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
