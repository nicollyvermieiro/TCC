<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cargo_id = $_SESSION['cargo_id'] ?? null;
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Usuário';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - ManutSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card-option {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 12px;
            border: 1px solid #e5e5e5;
        }
        .card-option:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 12px;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <main class="container mt-5">
        <h1 class="mb-4">Bem-vindo(a), <?= htmlspecialchars($usuario_nome); ?></h1>

        <?php if ($cargo_id == 1): ?>
            <!-- ADMIN -->
            <h4 class="mb-3">Funções Administrativas</h4>
            <div class="row row-cols-1 row-cols-md-3 g-4 mt-2">
                <div class="col">
                    <a href="?route=usuarios/listar" class="text-decoration-none text-dark">
                        <div class="card card-option h-100 text-center p-4">
                            <div class="card-icon"><i class="bi bi-people-fill"></i></div>
                            <h5>Gerenciar Usuários</h5>
                            <p>Crie, edite e exclua usuários.</p>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="?route=chamados/gerenciar" class="text-decoration-none text-dark">
                        <div class="card card-option h-100 text-center p-4">
                            <div class="card-icon"><i class="bi bi-tools"></i></div>
                            <h5>Gerenciar Chamados</h5>
                            <p>Controle todos os chamados.</p>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="?route=relatorios/listar" class="text-decoration-none text-dark">
                        <div class="card card-option h-100 text-center p-4">
                            <div class="card-icon"><i class="bi bi-bar-chart-fill"></i></div>
                            <h5>Relatórios</h5>
                            <p>Gere relatórios de manutenção.</p>
                        </div>
                    </a>
                </div>
            </div>

        <?php elseif ($cargo_id == 3): ?>
            <!-- USUÁRIO COMUM -->
            <h4 class="mb-3">Ações Disponíveis</h4>
            <div class="row row-cols-1 row-cols-md-2 g-4 mt-2">
                <div class="col">
                    <a href="?route=chamados/criarUsuario" class="text-decoration-none text-dark">
                        <div class="card card-option h-100 text-center p-4">
                            <div class="card-icon"><i class="bi bi-plus-circle-fill"></i></div>
                            <h5>Abrir Chamado</h5>
                            <p>Registre um novo chamado de manutenção.</p>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="?route=chamados/listar" class="text-decoration-none text-dark">
                        <div class="card card-option h-100 text-center p-4">
                            <div class="card-icon"><i class="bi bi-list-task"></i></div>
                            <h5>Meus Chamados</h5>
                            <p>Acompanhe o status dos seus chamados.</p>
                        </div>
                    </a>
                </div>
            </div>

        <?php elseif ($cargo_id == 2): ?>
    <!-- TÉCNICO -->
    <h4 class="mb-3">Chamados de Manutenção</h4>

    <div class="row row-cols-1 row-cols-md-2 g-4 mt-2">
        <div class="col">
            <a href="?route=chamados/listar" class="text-decoration-none text-dark">
                <div class="card card-option h-100 text-center p-4">
                    <div class="card-icon"><i class="bi bi-wrench-adjustable-circle-fill"></i></div>
                    <h5>Meus Chamados</h5>
                    <p>Visualize e atualize o status dos chamados atribuídos.</p>
                </div>
            </a>
        </div>

        <div class="col">
            <a href="?route=historicoStatus/listar" class="text-decoration-none text-dark">
                <div class="card card-option h-100 text-center p-4">
                    <div class="card-icon"><i class="bi bi-clock-history"></i></div>
                    <h5>Histórico de Chamados</h5>
                    <p>Consulte os chamados já concluídos.</p>
                </div> 
            </a>
        </div>
    </div>
<?php endif; ?>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <footer class="bg-primary text-white text-center py-1 mt-4 fixed-bottom">
        <div style="font-size: 0.8rem; opacity: 0.8;">
            &copy; 2025 ManutSmart
        </div>
    </footer>

</body>
</html>
