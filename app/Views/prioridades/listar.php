<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Prioridades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .container {
            margin-top: 20px !important;
        }
        table {
            font-size: 1.0rem;
        }
        th, td {
            vertical-align: middle !important;
            text-align: center;
        }
        .table {
            width: auto;
            min-width: 700px;
        }
        .btn-back {
            font-size: 1.8rem;
        }
        h2 {
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <!-- Flash message -->
    <?php $flash = getFlashMessage(); ?>
    <?php if ($flash): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <!-- Botão de voltar -->
        <a href="?route=chamados/gerenciar"
           class="text-primary fs-3 mb-2 btn-back"
           title="Voltar ao painel"
           style="text-decoration: none;">
            <i class="bi bi-arrow-left-circle"></i>
        </a>

        <!-- Título -->
        <h2 class="mb-3">Prioridades</h2>

        <!-- Botão Nova Prioridade -->
        <a href="?route=prioridades/criar" class="btn btn-primary mb-3">
            <i class="bi bi-plus-lg"></i> Nova Prioridade
        </a>

        <!-- Tabela -->
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Nível</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($prioridades)): ?>
                    <?php foreach ($prioridades as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['id']) ?></td>
                            <td><?= htmlspecialchars($p['nome']) ?></td>
                            <td><?= htmlspecialchars($p['nivel']) ?></td>
                            <td>
                                <a href="?route=prioridades/editar&id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <button 
                                    class="btn btn-danger btn-sm"
                                    onclick="confirmarExclusao(<?= $p['id'] ?>)">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhuma prioridade cadastrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Função para confirmar exclusão com SweetAlert2
        function confirmarExclusao(id) {
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Deseja realmente excluir esta prioridade?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?route=prioridades/excluir&id=' + id;
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <footer class="bg-primary text-white text-center py-1 mt-4 fixed-bottom">
        <div style="font-size: 0.8rem; opacity: 0.8;">
            &copy; 2025 ManutSmart. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
