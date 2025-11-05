<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Usuarios.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController
{
    public function loginForm()
    {
        include __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';

            $usuarioModel = new Usuarios();
            $usuario = $usuarioModel->buscarPorEmail($email);

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                if (session_status() === PHP_SESSION_NONE) session_start();

                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['cargo_id'] = $usuario['cargo_id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];

                header("Location: ?route=auth/dashboard");
                exit;
            }

            setFlashMessage("Email ou senha inválidos.", "danger");
            header("Location: ?route=auth/loginForm");
            exit;
        }

        header("Location: ?route=auth/loginForm");
        exit;
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: ?route=auth/loginForm");
        exit;
    }

    public function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: ?route=auth/loginForm");
            exit;
        }

        require __DIR__ . '/../Views/dashboard/home.php';
    }

    // ======== ESQUECI A SENHA ========

    public function esqueciSenha()
    {
        include __DIR__ . '/../Views/auth/esqueciSenha.php';
    }

    public function enviarRecuperacao()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ?route=auth/esqueciSenha");
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $usuarioModel = new Usuarios();
    $usuario = $usuarioModel->buscarPorEmail($email);

    if (!$usuario) {
        setFlashMessage("Email não encontrado.", "danger");
        header("Location: ?route=auth/esqueciSenha");
        exit;
    }

    // gera token e salva
    $token = bin2hex(random_bytes(16));
    $saved = $usuarioModel->salvarTokenRecuperacao($usuario['id'], $token);
    if (!$saved) {
        // algo deu errado salvando token
        error_log("Falha ao salvar token de recuperação para usuário id={$usuario['id']}");
        setFlashMessage("Erro interno. Tente novamente mais tarde.", "danger");
        header("Location: ?route=auth/esqueciSenha");
        exit;
    }

    // monta URL usando host atual (funciona com ngrok ou IP)
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $url = "{$scheme}://{$host}/?route=auth/redefinirSenha&token={$token}";

    // envia e-mail com PHPMailer
    try {
        require_once __DIR__ . '/../../vendor/autoload.php';

        $mail = new PHPMailer(true);
        // configuração SMTP - ajuste se necessário
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '802.427@alunos.unigran.br';        // altere
        $mail->Password = 'ycky llrd cmzk pvuu';          // altere (app password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('no-reply@manutsmart.local', 'ManutSmart');
        $mail->addAddress($email, $usuario['nome']);

        $mail->isHTML(true);
        $mail->Subject = 'Recuperação de Senha - ManutSmart';
        $mail->Body = "
            <p>Olá <strong>" . htmlspecialchars($usuario['nome']) . "</strong>,</p>
            <p>Recebemos uma solicitação para redefinir sua senha. Clique no link abaixo para criar uma nova senha:</p>
            <p><a href=\"" . htmlspecialchars($url) . "\">Redefinir minha senha</a></p>
            <p>Se você não solicitou, ignore esta mensagem.</p>
        ";

        $mail->send();

        // sucesso -> flash + redireciona para loginForm (sua view exibirá Swal)
        setFlashMessage("Email de recuperação enviado. Verifique sua caixa de entrada (ou spam).", "success");
        header("Location: ?route=auth/loginForm");
        exit;

    }  catch (Exception $e) {
    echo "<pre style='color:red; background:#fee; padding:10px;'>
    <b>Erro ao enviar e-mail:</b><br>" . htmlspecialchars($mail->ErrorInfo) . "
    </pre>";
    error_log("PHPMailer error enviarRecuperacao: " . $mail->ErrorInfo);
    exit;
}

}

    public function redefinirSenha()
    {
        // Pega o token da URL
        $token = $_GET['token'] ?? '';

        // Se não veio token, volta para login
        if (empty($token)) {
            setFlashMessage("Token inválido ou ausente.", "danger");
            header("Location: ?route=auth/loginForm");
            exit;
        }

        // Mostra a página de redefinição de senha
        include __DIR__ . '/../Views/auth/redefinirSenha.php';
    }

    public function salvarNovaSenha()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $senha_confirm = $_POST['senha_confirm'] ?? '';

            if ($senha !== $senha_confirm) {
                setFlashMessage("As senhas não conferem.", "danger");
                header("Location: ?route=auth/redefinirSenha&token=$token");
                exit;
            }

            $usuarioModel = new Usuarios();
            $usuario = $usuarioModel->buscarPorToken($token);

            if (!$usuario) {
                setFlashMessage("Token inválido ou expirado.", "danger");
                header("Location: ?route=auth/loginForm");
                exit;
            }

            $novaSenhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $usuarioModel->atualizarSenha($usuario['id'], $novaSenhaHash);

            setFlashMessage("Senha redefinida com sucesso.", "success");
            header("Location: ?route=auth/loginForm");
            exit;
        }
    }
}
