<?php
require_once __DIR__ . '/../../config/database.php';

class Chamado {
    private $conn;
    private $table = "chamado";

    public $id, $descricao, $prioridade, $tipo_chamado_id, $usuario_id, $setor_id, $status, $data_criacao;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function criar() {
        $query = "INSERT INTO {$this->table} (descricao, prioridade, tipo_chamado_id, usuario_id, setor_id, status, data_criacao) 
                  VALUES (:descricao, :prioridade, :tipo_chamado_id, :usuario_id, :setor_id, :status, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":prioridade", $this->prioridade);
        $stmt->bindParam(":tipo_chamado_id", $this->tipo_chamado_id);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":setor_id", $this->setor_id);
        $stmt->bindParam(":status", $this->status);
        return $stmt->execute();
    }

    public function listarTodos() {
        $query = "SELECT c.*, tc.nome AS tipo_nome, s.nome AS setor_nome, u.nome AS usuario_nome
                  FROM {$this->table} c
                  LEFT JOIN tipo_chamado tc ON c.tipo_chamado_id = tc.id
                  LEFT JOIN setor s ON c.setor_id = s.id
                  LEFT JOIN usuario u ON c.usuario_id = u.id
                  ORDER BY c.data_criacao DESC";
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
        $query = "UPDATE {$this->table} SET descricao = :descricao, prioridade = :prioridade, tipo_chamado_id = :tipo_chamado_id, setor_id = :setor_id, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":prioridade", $this->prioridade);
        $stmt->bindParam(":tipo_chamado_id", $this->tipo_chamado_id);
        $stmt->bindParam(":setor_id", $this->setor_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
