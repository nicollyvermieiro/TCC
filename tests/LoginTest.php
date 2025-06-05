<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Models/Usuario.php';

class LoginTest extends TestCase
{
    private $usuario;

    protected function setUp(): void
    {
        $this->usuario = new Usuario();
    }

    public function testLoginValido()
    {
        $email = 'usuario@example.com'; // Usuário válido
        $senha = 'senhaCorreta';

        // Supondo que você tenha um método autenticar(email, senha) no model Usuario:
        $resultado = $this->usuario->autenticar($email, $senha);

        $this->assertTrue($resultado);
    }

    public function testLoginInvalido()
    {
        $email = 'usuario@example.com';
        $senha = 'senhaErrada';

        $resultado = $this->usuario->autenticar($email, $senha);

        $this->assertFalse($resultado);
    }
}
