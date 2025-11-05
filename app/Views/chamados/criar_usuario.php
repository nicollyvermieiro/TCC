<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Abrir Chamado - ManutSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6fa;
            font-family: "Segoe UI", sans-serif;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(90deg, #004aad, #007bff);
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.03);
        }
        .btn-secondary {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-secondary:hover {
            transform: scale(1.03);
        }
        .container {
            max-width: 650px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <?php
    require_once __DIR__ . '/../../helpers/session.php';
    $flash = getFlashMessage();
    ?>

    <?php if ($flash): ?>
    <div class="container mt-4">
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show shadow-sm" role="alert">
            <?= htmlspecialchars($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>

    <div class="container mt-5 mb-5">
        <div class="card">
            <div class="card-header text-center py-3">
                <h4 class="mb-0"><i class="bi bi-tools me-2"></i>Abrir Chamado</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="?route=chamados/salvarUsuario" enctype="multipart/form-data">
                    
                    <?php if (!empty($_SESSION['is_guest']) && $_SESSION['is_guest'] === true): ?>
                    <div class="mb-3">
                        <label for="nome" class="form-label"><i class="bi bi-person"></i> Seu Nome (opcional)</label>
                        <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite seu nome ou deixe em branco">
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="descricao" class="form-label"><i class="bi bi-pencil-square"></i> Descrição do Problema</label>
                        <textarea name="descricao" id="descricao" class="form-control" rows="4" required placeholder="Descreva o problema encontrado..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="localizacao" class="form-label"><i class="bi bi-geo-alt"></i> Localização</label>
                        <input type="text" name="localizacao" id="localizacao" class="form-control" placeholder="Ex: Bloco B, Sala 204" required>
                    </div>

                    <div class="mb-3">
                        <label for="anexo" class="form-label"><i class="bi bi-paperclip"></i> Anexar Arquivo (opcional)</label>
                        <input type="file" name="anexo" id="anexo" class="form-control">
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-send-check"></i> Registrar Chamado
                        </button>
                        <a href="?route=auth/dashboard" class="btn btn-secondary px-4">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
