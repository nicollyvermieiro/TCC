<?php
require_once __DIR__ . '/../../config/database.php';

class Chamado {
    private $conn;
    private $table = "chamado";

    public $id;
    public $descricao;
    public $usuario_id;
    public $usuario_temporario;
    public $protocolo;
    public $status;
    public $tipo_id;
    public $setor_id;
    public $tecnico_id;
    public $prioridade_id;
    public $criado_em;

    public function __construct($db = null) {
        $this->conn = $db ?? (new Database())->getConnection();
    }

    public function criar() {
        $query = "INSERT INTO {$this->table} 
                  (descricao, usuario_id, usuario_temporario, protocolo, status, tipo_id, setor_id, tecnico_id, prioridade_id) 
                  VALUES 
                  (:descricao, :usuario_id, :usuario_temporario, :protocolo, :status, :tipo_id, :setor_id, :tecnico_id, :prioridade_id)";

        $stmt = $this->conn->prepare($query);

        // Gera protocolo único (ex: 20250001)
        $this->protocolo = $this->protocolo ?? date("Y") . strtoupper(uniqid());

        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":usuario_temporario", $this->usuario_temporario);
        $stmt->bindParam(":protocolo", $this->protocolo);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":tipo_id", $this->tipo_id);
        $stmt->bindParam(":setor_id", $this->setor_id);
        $stmt->bindParam(":tecnico_id", $this->tecnico_id);
        $stmt->bindParam(":prioridade_id", $this->prioridade_id);

        return $stmt->execute();
    }

    public function listarTodos() {
        $query = "SELECT c.*, 
                         tc.nome AS tipo_nome, 
                         s.nome AS setor_nome, 
                         u.nome AS usuario_nome,
                         p.nome AS prioridade_nome
                  FROM {$this->table} c
                  JOIN tipo_chamado tc ON c.tipo_id = tc.id
                  JOIN setor s ON c.setor_id = s.id
                  JOIN prioridade_chamado p ON c.prioridade_id = p.id
                  LEFT JOIN usuario u ON c.usuario_id = u.id
                  ORDER BY c.criado_em DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar() {
        $query = "UPDATE {$this->table} 
                  SET descricao = :descricao,
                      tipo_id = :tipo_id,
                      setor_id = :setor_id,
                      prioridade_id = :prioridade_id,
                      status = :status,
                      tecnico_id = :tecnico_id
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":tipo_id", $this->tipo_id);
        $stmt->bindParam(":setor_id", $this->setor_id);
        $stmt->bindParam(":prioridade_id", $this->prioridade_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":tecnico_id", $this->tecnico_id);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function setConnection($conn) {
        $this->conn = $conn;
    }
}
