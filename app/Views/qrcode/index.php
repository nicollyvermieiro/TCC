<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>QR Code - Abrir Chamado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container mt-4">
  <h2>QR Code para Abertura de Chamado</h2>
  <p>Escaneie o código abaixo para registrar um chamado:</p>
  <img src="?route=qrCode/gerar" alt="QR Code para abrir chamado" />
  <br><br>
  <button onclick="window.print()" class="btn btn-secondary">Imprimir</button>
</body>
</html>
