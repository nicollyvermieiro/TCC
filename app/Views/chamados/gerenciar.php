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
          .btn-voltar {
        background-color: #0d6efd;
        color: #fff;
        border: none;
        padding: 6px 14px;
        font-size: 0.9rem;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .btn-voltar:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        cursor: pointer;
    }

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
     <div class="d-flex align-items-center mb-3">
        <button class="btn-voltar me-3" onclick="window.history.back();">
            <i class="bi bi-arrow-left"></i> 
        </button>
        <h2 class="mb-0">Gerenciar Chamados</h2>
    </div>
        <p class="text-muted">Escolha uma das opções abaixo para continuar:</p>

        <div class="row row-cols-1 row-cols-md-3 g-4 mt-3">
            <div class="col">
                <a href="?route=chamados/listar" class="text-decoration-none">
                    <div class="card h-100 text-center p-3">
                        <div class="icon-circle"><i class="bi bi-clipboard-check"></i></div>
                        <h5 class="card-title">Complementar Chamados</h5>
                        <p class="card-text">Visualizar, complementar, editar e excluir chamados.</p>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="?route=historicoStatus/listar" class="text-decoration-none">
                    <div class="card h-100 text-center p-3">
                        <div class="icon-circle"><i class="bi bi-clock-history"></i></div>
                        <h5 class="card-title">Histórico de Chamados</h5>
                        <p class="card-text">Consulte os chamados já concluídos.</p>
                    </div> 
                </a>
            </div>

            <div class="col">
                <a href="?route=qrCode/index" class="text-decoration-none">
                    <div class="card h-100 text-center p-3">
                        <div class="icon-circle"><i class="bi bi-qr-code"></i></div>
                        <h5 class="card-title">Gerar QR Code</h5>
                        <p class="card-text">Imprimir QR Code para usuário temporário registrar chamados.</p>
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
