<?php
require_once __DIR__ . '/../../helpers/session.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastro do Primeiro Administrador - ManutSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <!-- Flash message -->
    <?php $flash = getFlashMessage(); ?>
    <?php if ($flash): ?>
        <div class="container mt-4">
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show shadow-sm rounded-3" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white text-center rounded-top-4">
                        <h4 class="mb-0 fw-bold">Cadastro do Primeiro Administrador</h4>
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-4 text-center text-muted">
                            Bem-vindo ao <strong>ManutSmart</strong>!<br>
                            Como ainda não há administradores, crie o primeiro para acessar o sistema.
                        </p>

                        <form method="post" action="?route=usuarios/salvarPrimeiroAdmin">
                            <!-- Nome -->
                            <div class="mb-3">
                                <label for="nome" class="form-label fw-semibold">Nome</label>
                                <input type="text" class="form-control rounded-3" id="nome" name="nome" placeholder="Digite seu nome" required />
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">E-mail</label>
                                <input type="email" class="form-control rounded-3" id="email" name="email" placeholder="exemplo@dominio.com" required />
                            </div>

                            <!-- Senha -->
                            <div class="mb-4">
                                <label for="senha" class="form-label fw-semibold">Senha</label>
                                <input type="password" class="form-control rounded-3" id="senha" name="senha" placeholder="Crie uma senha forte" required />
                            </div>

                            <input type="hidden" name="cargo_id" value="1" />

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-3 fw-bold">
                                    Cadastrar Administrador
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center text-muted small rounded-bottom-4">
                        ManutSmart &copy; <?= date("Y") ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
