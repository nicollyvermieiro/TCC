<?php require_once __DIR__ . '/../../helpers/session.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Lista de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { max-width: 900px; margin: 50px auto; border-radius: 12px; }
        .card-header {
            background-color: #0d6efd; /* Azul padrão ManutSmart */
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .btn-primary, .btn-success {
            background-color: #0d6efd;
            border-color: #0d6efd;
            font-weight: 500;
        }
        .btn-primary:hover, .btn-success:hover {
            background-color: #0b5ed7;
            border-color: #0b5ed7;
        }
        .btn-secondary { font-weight: 500; }
        .table th, .table td { vertical-align: middle; }
        .btn-sm { font-size: 0.85rem; }
        .d-flex-space { display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <?php $flash = getFlashMessage(); ?>
    <?php if ($flash): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container mt-5">
        <!-- Cabeçalho com botão voltar -->
        <div class="d-flex justify-content-between mb-3">
            <div class="top-actions">
                <a href="?route=auth/dashboard"
                class="text-primary fs-3 mb-2 btn-back"
                title="Voltar ao painel"
                style="text-decoration: none;">
                    <i class="bi bi-arrow-left-circle"></i>
                </a>
            </div>
            <a href="?route=usuarios/criar" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Novo Usuário
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header d-flex-space">
                <span>Usuários Cadastrados</span>
            </div>
            <div class="card-body p-3">
                <table class="table table-striped table-bordered table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Cargo</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['id']) ?></td>
                                <td><?= htmlspecialchars($usuario['nome']) ?></td>
                                <td><?= htmlspecialchars($usuario['email']) ?></td>
                                <td><?= htmlspecialchars($usuario['cargo']) ?></td>
                                <td class="text-center">
                                    <a href="?route=usuarios/editar&id=<?= $usuario['id'] ?>" 
                                        class="btn btn-sm btn-warning" title="Editar usuário">
                                        <i class="bi bi-pencil-square"></i> 
                                    </a>
                                    <a href="?route=usuarios/excluir&id=<?= $usuario['id'] ?>" 
                                        class="btn btn-sm btn-danger btn-excluir" 
                                        title="Excluir usuário">
                                        <i class="bi bi-trash"></i> 
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(empty($usuarios)): ?>
                            <tr>
                                <td colspan="5" class="text-center">Nenhum usuário cadastrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.querySelectorAll('.btn-excluir').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            Swal.fire({
                title: 'Tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
    </script>   
    
    <footer class="bg-primary text-white text-center py-1 mt-4 fixed-bottom">
        <div style="font-size: 0.8rem; opacity: 0.8;">
            &copy; 2025 ManutSmart. Todos os direitos reservados.
        </div>
    </footer>

</body>
</html>
