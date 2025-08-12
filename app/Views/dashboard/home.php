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
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <main class="container mt-5">
        <h1 class="mb-4">Bem-vindo(a), <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?>!</h1>

        <?php if (isset($_SESSION['cargo_id']) && $_SESSION['cargo_id'] == 1): // Só para admin ?>
            <h3>Funções Administrativas</h3>

            <div class="row row-cols-1 row-cols-md-3 g-4 mt-3">
                <div class="col">
                    <a href="?route=usuarios/listar" class="text-decoration-none">
                        <div class="card text-bg-primary h-100">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-people mb-3" viewBox="0 0 16 16">
                                  <path d="M5.5 7a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm5 0a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                                  <path d="M2 13s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H2z"/>
                                </svg>
                                <h5 class="card-title">Gerenciar Usuários</h5>
                                <p class="card-text text-center">Crie, edite e exclua usuários do sistema.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Espaço para adicionar outros cards futuramente, por exemplo: -->

                <div class="col">
                    <a href="?route=chamados/listar" class="text-decoration-none disabled" tabindex="-1" aria-disabled="true">
                        <div class="card text-bg-secondary h-100">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-tools mb-3" viewBox="0 0 16 16">
                                  <path d="M1 0 5 4l2-2-4-4H1zM8.5 6a3 3 0 1 0-5.86 1.18l-3.69 3.7a.5.5 0 0 0 0 .71l4.37 4.37a.5.5 0 0 0 .7 0l3.69-3.7A3 3 0 0 0 8.5 6z"/>
                                </svg>
                                <h5 class="card-title">Gerenciar Chamados</h5>
                                <p class="card-text text-center">Função em desenvolvimento.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="?route=relatorios/gerar" class="text-decoration-none disabled" tabindex="-1" aria-disabled="true">
                        <div class="card text-bg-secondary h-100">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-file-earmark-text mb-3" viewBox="0 0 16 16">
                                  <path d="M5 12.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                                  <path d="M4 1h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2z"/>
                                </svg>
                                <h5 class="card-title">Relatórios</h5>
                                <p class="card-text text-center">Função em desenvolvimento.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        <?php else: ?>
            <p>Ainda não há funções disponíveis para o seu perfil.</p>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
