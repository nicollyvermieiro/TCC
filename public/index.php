<?php
// Ativa exibição de erros para desenvolvimento
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/helpers/session.php';

// Pega a rota da URL, exemplo: ?route=auth/login
$route = $_GET['route'] ?? 'auth/loginForm';

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

        // Verifica se o método existe e é público
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
