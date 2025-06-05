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
        ob_start();
        $this->controller->listar();
        $output = ob_get_clean();

        $this->assertStringContainsString('<table', $output, "Listar deve gerar saída HTML contendo tabela.");
    }

    public function testSalvar()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'nome' => 'Teste Controller ' . uniqid(),
            'email' => 'testecont_' . uniqid() . '@example.com',
            'senha' => '123456',
            'cargo_id' => 1
        ];

        ob_start();
        $this->controller->salvar();
        $output = ob_get_clean();

        $this->assertEmpty($output, "Salvar deve redirecionar, não deve gerar saída.");
    }
}
