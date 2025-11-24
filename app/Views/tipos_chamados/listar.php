<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Categorias de Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px !important;
        }
        th, td {
            vertical-align: middle !important;
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
        /* --- CÓDIGO NOVO PARA AJUSTAR O LAYOUT --- */
        /* 1. Invólucro para limitar a largura da tabela */
        .table-container {
            max-width: 900px; /* Largura um pouco maior para acomodar a descrição */
            margin-left: 0;
            margin-right: auto;
        }

        /* 2. Alinha o texto das células à esquerda para melhor leitura */
        .table td {
            text-align: left;
        }
        
        /* 3. Define larguras fixas para as colunas menores */
        .col-nome {
            width: 150px;
        }
        .col-acoes {
            width: 120px; 
            text-align: center; /* Centraliza apenas o conteúdo da coluna de ações */
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
                    <span>Categorias de Chamados</span>

                    <!-- Botões de Ação -->
                    <div>
                        <!-- BOTÃO DE VOLTAR CAMUFLADO -->
                        <a href="?route=chamados/gerenciar"
                           class="btn btn-primary btn-sm me-2"
                           title="Voltar ao painel">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>

                        <!-- Botão Nova Categoria -->
                        <a href="?route=tipos_chamados/criar" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> Nova
                        </a>
                    </div>
                </div>

                <table class="table table-bordered table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="col-nome">Nome</th>
                            <th>Descrição</th>
                            <th class="col-acoes">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tipos)): ?>
                            <?php foreach($tipos as $tipo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($tipo['nome']) ?></td>
                                    <td><?= htmlspecialchars($tipo['descricao']) ?></td>
                                    <td class="text-center">
                                        <a href="?route=tipos_chamados/editar&id=<?= $tipo['id'] ?>" 
                                           class="btn btn-primary btn-sm"
                                           title="Editar">
                                           <i class="bi bi-pencil"></i> 
                                        </a>
                                        <button 
                                            class="btn btn-danger btn-sm"
                                            onclick="confirmarExclusao(<?= $tipo['id'] ?>)"
                                            title="Excluir">
                                            <i class="bi bi-trash"></i> 
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">Nenhuma categoria cadastrada.</td>
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

    <footer class="bg-primary text-white text-center py-1 mt-4 fixed-bottom">
        <div style="font-size: 0.8rem; opacity: 0.8;">
            &copy; 2025 ManutSmart. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
