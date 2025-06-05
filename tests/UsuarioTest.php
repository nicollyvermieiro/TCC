<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Models/Usuario.php';

class UsuarioTest extends TestCase
{
    private $usuario;

    protected function setUp(): void
    {
        $this->usuario = new Usuario();
    }

    public function testCriarUsuario()
    {
        $this->usuario->nome = "Teste Unit";
        $this->usuario->email = "testeunit@example.com";
        $this->usuario->senha = password_hash("123456", PASSWORD_DEFAULT);
        $this->usuario->cargo_id = 1;

        $result = $this->usuario->criar();
        $this->assertTrue($result);
    }

    public function testBuscarPorId()
    {
        $usuario = $this->usuario->buscarPorId(1); // Assuma que usuário com id 1 exista no banco
        $this->assertIsArray($usuario);
        $this->assertArrayHasKey('id', $usuario);
        $this->assertEquals(1, $usuario['id']);
    }

    // Outros testes para atualizar, excluir etc.
}
