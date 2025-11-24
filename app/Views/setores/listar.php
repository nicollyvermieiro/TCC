<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Setores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa; /* Adiciona um fundo suave à página */
        }
        .container {
            margin-top: 20px !important;
        }
        th, td {
            vertical-align: middle !important;
            text-align: center;
        }
        /* Estilos do novo cabeçalho */
        .card-header {
            background-color: #0d6efd; /* Cor azul primária do Bootstrap */
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
            display: flex;
            justify-content: space-between; /* Alinha itens nas extremidades */
            align-items: center;
        }
        .card-header .btn {
            color: #fff; /* Garante que o texto/ícone do botão seja branco */
        }
        /* 1. Invólucro para limitar a largura da tabela */
        .table-container {
            max-width: 600px; /* Define a largura máxima da tabela. Ajuste se necessário. */
            margin-left: 0;   /* Remove a centralização automática para alinhar com os botões acima */
            margin-right: auto;
        }

        /* 2. Ajusta a coluna de texto para alinhar à esquerda */
        .table td:first-child {
            text-align: left;
        }
        
        /* 3. Define uma largura fixa para a coluna de ações */
        .col-acoes {
            width: 120px; 
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <!-- Flash message -->
    <?php $flash = getFlashMessage(  ); ?>
    <?php if ($flash): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <!-- MUDANÇA: Adicionado o invólucro da tabela e o novo cabeçalho -->
        <div class="table-container">
            <div class="card shadow-sm">
                <div class="card-header">
                    <!-- Título da Tabela -->
                    <span>Lista de Setores</span>

                    <!-- Botões de Ação -->
                    <div>
                        <!-- BOTÃO DE VOLTAR CAMUFLADO -->
                        <a href="?route=chamados/gerenciar"
                           class="btn btn-primary btn-sm me-2"
                           title="Voltar ao painel">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>

                        <!-- Botão Novo Setor -->
                        <a href="?route=setores/criar" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> Novo
                        </a>
                    </div>
                </div>

                <table class="table table-bordered table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th class="col-acoes">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($setores)): ?>
                            <?php foreach ($setores as $setor): ?>
                                <tr>
                                    <td><?= htmlspecialchars($setor['nome']) ?></td>
                                    <td>
                                        <a href="?route=setores/editar&id=<?= $setor['id'] ?>" 
                                           class="btn btn-primary btn-sm"
                                           title="Editar">
                                           <i class="bi bi-pencil"></i> 
                                        </a>
                                        <button 
                                            class="btn btn-danger btn-sm"
                                            onclick="confirmarExclusao(<?= $setor['id'] ?>)"
                                            title="Excluir">
                                            <i class="bi bi-trash"></i> 
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">Nenhum setor cadastrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Função para confirmar exclusão com SweetAlert2
        function confirmarExclusao(id) {
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Deseja realmente excluir este setor?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?route=setores/excluir&id=' + id;
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
