<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tipos de Chamados</title>
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
            min-width: 750px;
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
        <h2 class="mb-3">Categorias de Chamados</h2>

        <!-- Botão Nova Categoria -->
        <a href="?route=tipos_chamados/criar" class="btn btn-primary mb-3">
            <i class="bi bi-plus-lg"></i> Nova Categoria
        </a>

        <!-- Tabela -->
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tipos)): ?>
                    <?php foreach($tipos as $tipo): ?>
                        <tr>
                            <td><?= htmlspecialchars($tipo['id']) ?></td>
                            <td><?= htmlspecialchars($tipo['nome']) ?></td>
                            <td><?= htmlspecialchars($tipo['descricao']) ?></td>
                            <td>
                                <a href="?route=tipos_chamados/editar&id=<?= $tipo['id'] ?>" 
                                   class="btn btn-primary btn-sm">
                                   <i class="bi bi-pencil"></i> Editar
                                </a>
                                <button 
                                    class="btn btn-danger btn-sm"
                                    onclick="confirmarExclusao(<?= $tipo['id'] ?>)">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhuma categoria cadastrada.</td>
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
                text: 'Deseja realmente excluir esta categoria de chamado?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?route=tipos_chamados/excluir&id=' + id;
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
