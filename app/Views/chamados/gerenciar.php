<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../helpers/session.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['cargo_id'] ?? null) != 1) {
    setFlashMessage("Acesso negado. Área restrita a administradores.", "danger");
    header("Location: ?route=auth/dashboard");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
        h2 {
            font-weight: 600;
            color: #333;
        }
        .card {
            border: none;
            border-radius: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        .card h5 {
            font-weight: 600;
            color: #444;
        }
        .card p {
            color: #666;
            font-size: 0.9rem;
        }
        .icon-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 12px;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>

    <div class="container mt-5">
        <button class="btn btn-outline-secondary mb-4" onclick="window.history.back();">
            <i class="bi bi-arrow-left"></i> Voltar
        </button>

        <h2 class="mb-3">Gerenciar Chamados</h2>
        <p class="text-muted">Escolha uma das opções abaixo para continuar:</p>

        <div class="row row-cols-1 row-cols-md-3 g-4 mt-3">
            <div class="col">
                <a href="?route=chamados/listar" class="text-decoration-none">
                    <div class="card h-100 text-center p-3">
                        <div class="icon-circle"><i class="bi bi-clipboard-check"></i></div>
                        <h5 class="card-title">Ver Chamados</h5>
                        <p class="card-text">Visualizar, editar e excluir chamados existentes.</p>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="?route=chamados/criar" class="text-decoration-none">
                    <div class="card h-100 text-center p-3">
                        <div class="icon-circle"><i class="bi bi-plus-circle"></i></div>
                        <h5 class="card-title">Criar Chamado</h5>
                        <p class="card-text">Registrar um novo chamado no sistema.</p>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="?route=setores/listar" class="text-decoration-none">
                    <div class="card h-100 text-center p-3">
                        <div class="icon-circle"><i class="bi bi-building"></i></div>
                        <h5 class="card-title">Gerenciar Setores</h5>
                        <p class="card-text">Cadastrar, editar ou excluir setores.</p>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="?route=tipos_chamados/listar" class="text-decoration-none">
                    <div class="card h-100 text-center p-3">
                        <div class="icon-circle"><i class="bi bi-folder2-open"></i></div>
                        <h5 class="card-title">Gerenciar Categorias</h5>
                        <p class="card-text">Criar e administrar os tipos de chamados.</p>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="?route=prioridades/listar" class="text-decoration-none">
                    <div class="card h-100 text-center p-3">
                        <div class="icon-circle"><i class="bi bi-lightning-charge-fill"></i></div>
                        <h5 class="card-title">Gerenciar Prioridades</h5>
                        <p class="card-text">Definir níveis de prioridade dos chamados.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
