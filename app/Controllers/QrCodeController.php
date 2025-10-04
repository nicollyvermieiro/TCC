<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeController {

    // Gera e exibe o QR Code como SVG de forma totalmente limpa
    public function gerar() {
        // Desativa exibição de erros para não quebrar o SVG
        error_reporting(0);
        ini_set('display_errors', 0);

        // URL que o QR Code vai apontar
        $url = 'http://localhost/TCC/public/?route=chamados/criarUsuario';

        // Configuração do QR Code
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_MARKUP_SVG, // SVG, sem GD
            'eccLevel'   => QRCode::ECC_L,
            'scale'      => 5,
        ]);

        // Limpa qualquer buffer de saída existente
        if (ob_get_length()) ob_end_clean();
        ob_start();

        // Envia o header correto
        header('Content-Type: image/svg+xml');

        // Gera o QR Code
        echo (new QRCode($options))->render($url);

        // Limpa buffer e garante que nada mais seja enviado
        ob_end_flush();
        exit;
    }

    // Tela com o QR Code para exibir/imprimir
    public function index() {
        include __DIR__ . '/../Views/qrcode/index.php';
    }
}
