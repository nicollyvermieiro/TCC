<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Models/Chamado.php';

class ChamadoTest extends TestCase
{
    private $pdoMock;
    private $stmtMock;
    private $chamado;

    protected function setUp(): void
    {
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetchAll')->willReturn([]);
        $this->stmtMock->method('fetch')->willReturn(false);

        $this->pdoMock = $this->createMock(PDO::class);
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);

    
        $databaseMock = $this->getMockBuilder(Database::class)
                             ->onlyMethods(['getConnection'])
                             ->getMock();
        $databaseMock->method('getConnection')->willReturn($this->pdoMock);

        $this->chamado = $this->getMockBuilder(Chamado::class)
                              ->onlyMethods(['__construct'])
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->chamado->setConnection($this->pdoMock);

        $this->chamado->descricao = "Teste";
        $this->chamado->prioridade = "Alta";
        $this->chamado->tipo_chamado_id = 1;
        $this->chamado->usuario_id = 1;
        $this->chamado->setor_id = 1;
        $this->chamado->status = "Aberto";
        $this->chamado->id = 1;
    }

    public function testCriarRetornaTrue()
    {
        $this->assertTrue($this->chamado->criar());
    }

    public function testListarTodosRetornaArray()
    {
        $this->stmtMock->method('fetchAll')->willReturn([['id' => 1]]);
        $result = $this->chamado->listarTodos();
        $this->assertIsArray($result);
        $this->assertEquals(1, $result[0]['id']);
    }

    public function testBuscarPorIdRetornaFalse()
    {
        $this->stmtMock->method('fetch')->willReturn(false);
        $result = $this->chamado->buscarPorId(1);
        $this->assertFalse($result);
    }

    public function testAtualizarRetornaTrue()
    {
        $this->assertTrue($this->chamado->atualizar());
    }

    public function testExcluirRetornaTrue()
    {
        $this->assertTrue($this->chamado->excluir(1));
    }
}
