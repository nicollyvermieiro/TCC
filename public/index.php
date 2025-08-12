<?php
// Ativa exibição de erros para desenvolvimento
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/helpers/session.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Rota padrão
$route = $_GET['route'] ?? null;

// Se não tem rota definida
if (!$route) {
    if (isset($_SESSION['usuario_id'])) {
        $route = 'auth/dashboard';  // Usuário logado vai para dashboard
    } else {
        $route = 'auth/loginForm'; // Usuário não logado vai para login
    }
}

// Separa controller e método
$parts = explode('/', $route);
$controllerName = $parts[0] ?? 'auth';
$method = $parts[1] ?? 'loginForm';

// Monta o caminho do arquivo do controller (ex: AuthController.php)
$controllerFile = __DIR__ . '/../app/Controllers/' . ucfirst($controllerName) . 'Controller.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerClass = ucfirst($controllerName) . 'Controller';
    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();
        if (method_exists($controller, $method)) {
            $refMethod = new ReflectionMethod($controllerClass, $method);
            if ($refMethod->isPublic()) {
                $controller->$method();
            } else {
                echo "Método '$method' não é acessível.";
            }
        } else {
            echo "Método '$method' não encontrado no controller '$controllerClass'.";
        }
    } else {
        echo "Classe '$controllerClass' não encontrada.";
    }
} else {
    echo "Controller '$controllerFile' não encontrado.";
}