<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Models/Usuario.php';

class LoginTest extends TestCase
{
    private $usuario;
    private $email;
    private $senha;

    protected function setUp(): void
    {
        $this->usuario = new Usuario();
        $this->email = 'testelogin_' . uniqid() . '@example.com';
        $this->senha = 'senhaTeste123';

        $this->usuario->nome = 'Usuário Login Teste';
        $this->usuario->email = $this->email;
        $this->usuario->senha = password_hash($this->senha, PASSWORD_DEFAULT);
        $this->usuario->cargo_id = 1;
        $this->usuario->criar();
    }

    public function testLoginValido()
    {
        $resultado = $this->usuario->autenticar($this->email, $this->senha);
        $this->assertTrue($resultado, "Login válido falhou.");
    }

    public function testLoginInvalido()
    {
        $resultado = $this->usuario->autenticar($this->email, 'senhaErrada');
        $this->assertFalse($resultado, "Login com senha errada retornou true.");
    }
}
