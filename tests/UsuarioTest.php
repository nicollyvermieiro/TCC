<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Models/Usuario.php';

class UsuarioTest extends TestCase
{
    private $usuario;

    protected function setUp(): void
    {
        $this->usuario = new Usuarios();
    }

    public function testCriarUsuario()
    {
        $this->usuario->nome = "Teste Unitário";
        $this->usuario->email = "teste_unit" . uniqid() . "@example.com"; // email único garantido
        $this->usuario->senha = password_hash("123456", PASSWORD_DEFAULT);
        $this->usuario->cargo_id = 1; // certifique-se que o cargo com id 1 exista

        $result = $this->usuario->criar();
        $this->assertTrue($result, "Falha ao criar usuário. Verifique se cargo_id 1 existe.");
    }

    public function testBuscarPorId()
    {
        $this->usuario->nome = "Teste Buscar";
        $this->usuario->email = "buscar_" . uniqid() . "@example.com";
        $this->usuario->senha = password_hash("123456", PASSWORD_DEFAULT);
        $this->usuario->cargo_id = 1;
        $this->usuario->criar();

        $id = $this->usuario->id; // obtém o ID do usuário criado
        $usuario = $this->usuario->buscarPorId($id);

        $this->assertIsArray($usuario, "Usuário não retornado como array.");
        $this->assertArrayHasKey('id', $usuario);
        $this->assertEquals($id, $usuario['id']);
    }
}
