<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Controllers/UsuariosController.php';

class UsuariosControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        $this->controller = new UsuariosController();
    }

    public function testListar()
    {
        // Simula a chamada do método listar
        ob_start(); // Captura a saída
        $this->controller->listar();
        $output = ob_get_clean();

        $this->assertStringContainsString('<table', $output); // Supondo que listar exiba uma tabela HTML
    }

    // Como não temos views por enquanto, pode testar o fluxo simples dos métodos

    public function testSalvar()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'nome' => 'Teste Controller',
            'email' => 'testecont@example.com',
            'senha' => '123456',
            'cargo_id' => 1
        ];

        ob_start();
        $this->controller->salvar();
        $output = ob_get_clean();

        $this->assertEmpty($output); // Redireciona, então não deve ter saída
    }
}
