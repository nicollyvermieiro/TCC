<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../partials/menu.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>QR Code - Abrir Chamado | ManutSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            max-width: 600px;
            margin: 40px auto;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #0d6efd;
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            text-align: center;
        }
        .card-body {
            text-align: center;
        }
        .qr-container img {
            max-width: 280px;
            margin: 20px 0;
            border: 4px solid #e9ecef;
            border-radius: 8px;
            padding: 8px;
            background-color: #fff;
        }
        .btn-print {
            background-color: #198754;
            border-color: #198754;
        }
        .btn-print:hover {
            background-color: #157347;
            border-color: #157347;
        }
        @media print {
            nav, .btn-voltar, .btn-print {
                display: none;
            }
            body {
                background-color: #fff;
            }
            .card {
                box-shadow: none;
                border: none;
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header">
                <i class="bi bi-qr-code"></i> QR Code para Abertura de Chamado
            </div>
            <div class="card-body">
                <p class="mb-3 text-muted">Escaneie o QR Code abaixo para registrar um novo chamado sem precisar de login.</p>

                <div class="qr-container">
                    <img src="?route=qrCode/gerar" alt="QR Code para abrir chamado" class="img-fluid" />
                </div>

                <div class="d-flex justify-content-center gap-2 mt-4">
                    <a href="javascript:history.back()" class="btn btn-outline-primary btn-voltar">
                        <i class="bi bi-arrow-left-circle"></i> Voltar
                    </a>
                    <button onclick="window.print()" class="btn btn-print text-white">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
