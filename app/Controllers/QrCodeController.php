<?php
require_once __DIR__ . '/../../vendor/phpqrcode/qrlib.php';

class QrCodeController
{
    private $uploadDir;
    private $baseUrl;

    public function __construct()
    {
        $this->uploadDir = __DIR__ . '/../../public/temp_qrcodes/';

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 🔹 Detecta automaticamente se ngrok está rodando
        $this->baseUrl = $this->detectarBaseUrl();
    }

    private function detectarBaseUrl()
    {
        // URL padrão local
        $urlPadrao = "http://192.168.100.26";

        // Tenta buscar o domínio ngrok (porta padrão 4040)
        $ngrokApi = @file_get_contents("http://127.0.0.1:4040/api/tunnels");

        if ($ngrokApi !== false) {
            $data = json_decode($ngrokApi, true);
            if (isset($data['tunnels'][0]['public_url'])) {
                return $data['tunnels'][0]['public_url']; // ex: https://xxx.ngrok-free.dev
            }
        }

        // Caso o ngrok não esteja ativo, usa IP local
        return $urlPadrao;
    }

    // Protege acesso apenas para administradores
    private function protegerAdministrador()
    {
        if (empty($_SESSION['usuario_id']) || $_SESSION['cargo_id'] != 1) {
            header('Location: ?route=auth/login');
            exit;
        }
    }

    // Exibe a página com o QR Code
    public function index()
    {
        $this->protegerAdministrador();

        // 🔹 Gera URL dinâmica com base no domínio correto
        $urlChamado = "{$this->baseUrl}/?route=chamados/criarUsuario";

        include __DIR__ . '/../Views/qrCode/exibir.php';
    }

    // Gera o QR Code (imagem)
    public function gerar()
    {
        $this->protegerAdministrador();

        // 🔹 Usa o mesmo domínio detectado
        $urlChamado = "{$this->baseUrl}/?route=chamados/criarUsuario";

        header('Content-Type: image/png');
        QRcode::png($urlChamado, false, QR_ECLEVEL_L, 6);
        exit;
    }
}
