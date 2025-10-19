<?php
require_once __DIR__ . '/../../config/database.php';

class RelatorioGerado {
    private $conn;
    private $table = "relatorio_gerado";

    public $id;
    public $tipo;
    public $gerado_por;
    public $periodo_inicio;
    public $periodo_fim;
    public $data_geracao;

     public function __construct($db = null) {
        $this->conn = $db ?? (new Database())->getConnection();
    }


    // Criar novo registro de relatório gerado
    public function criar() {
        $query = "INSERT INTO {$this->table} 
                    (tipo, gerado_por, periodo_inicio, periodo_fim, data_geracao)
                  VALUES (:tipo, :gerado_por, :periodo_inicio, :periodo_fim, :data_geracao)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":gerado_por", $this->gerado_por);
        $stmt->bindParam(":periodo_inicio", $this->periodo_inicio);
        $stmt->bindParam(":periodo_fim", $this->periodo_fim);
        $stmt->bindParam(":data_geracao", $this->data_geracao);
        return $stmt->execute();
    }

    // Listar todos os relatórios com nome do usuário
    public function listarTodos() {
        $query = "SELECT r.*, u.nome AS usuario_nome 
                  FROM {$this->table} r
                  LEFT JOIN usuario u ON u.id = r.gerado_por
                  ORDER BY r.data_geracao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Excluir relatório
    public function excluir($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Buscar relatório por ID
    public function buscarPorId($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
