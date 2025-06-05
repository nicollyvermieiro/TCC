<?php
// Ativa exibição de erros para desenvolvimento
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Recebe a rota da URL (ex: ?route=usuarios/listar)
$route = isset($_GET['route']) ? $_GET['route'] : 'home/index';

// Divide a rota em controller e método
list($controllerName, $method) = explode('/', $route);

// Gera o caminho do arquivo do controller
$controllerFile = __DIR__ . '/../app/Controllers/' . ucfirst($controllerName) . 'Controller.php';

// Verifica se o controller existe
if (file_exists($controllerFile)) {
    require_once $controllerFile;

    // Gera o nome da classe (ex: UsuariosController)
    $controllerClass = ucfirst($controllerName) . 'Controller';

    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();

        // Verifica se o método existe, senão chama um método padrão (ex: index)
        if (method_exists($controller, $method)) {
            $controller->$method();
        } else {
            echo "Método '$method' não encontrado no controller '$controllerClass'.";
        }
    } else {
        echo "Classe '$controllerClass' não encontrada.";
    }
} else {
    echo "Controller '$controllerFile' não encontrado.";
}
