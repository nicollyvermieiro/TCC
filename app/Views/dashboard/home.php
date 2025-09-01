<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - ManutSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
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
        <h1 class="mb-4">Bem-vindo(a), <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?></h1>

        <?php if (isset($_SESSION['cargo_id']) && $_SESSION['cargo_id'] == 1): ?>
            <h4 class="mb-3">Funções Administrativas</h4>
            <p class="text-muted">Escolha uma das opções abaixo:</p>

            <div class="row row-cols-1 row-cols-md-3 g-4 mt-2">
                <div class="col">
                    <a href="?route=usuarios/listar" class="text-decoration-none text-dark">
                        <div class="card card-option h-100 d-flex flex-column align-items-center justify-content-center p-4">
                            <div class="card-icon"><i class="bi bi-people-fill"></i></div>
                            <h5 class="card-title">Gerenciar Usuários</h5>
                            <p class="card-text text-center">Crie, edite e exclua usuários do sistema.</p>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="?route=chamados/gerenciar" class="text-decoration-none text-dark">
                        <div class="card card-option h-100 d-flex flex-column align-items-center justify-content-center p-4">
                            <div class="card-icon"><i class="bi bi-tools"></i></div>
                            <h5 class="card-title">Gerenciar Chamados</h5>
                            <p class="card-text text-center">Cadastre, edite e exclua chamados e configurações.</p>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="?route=relatorios/gerar" class="text-decoration-none text-dark disabled" tabindex="-1" aria-disabled="true">
                        <div class="card card-option h-100 d-flex flex-column align-items-center justify-content-center p-4">
                            <div class="card-icon"><i class="bi bi-bar-chart-fill"></i></div>
                            <h5 class="card-title">Relatórios</h5>
                            <p class="card-text text-center">Função em desenvolvimento.</p>
                        </div>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <p class="text-muted">Ainda não há funções disponíveis para o seu perfil.</p>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
