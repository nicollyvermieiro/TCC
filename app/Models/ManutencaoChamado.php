<?php
require_once __DIR__ . '/../../config/database.php';

class ManutencaoChamado {
    private $conn;
    private $table = "manutencao_chamado";

    public $id, $descricao, $data_manutencao, $tecnico_id, $chamado_id;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function criar() {
        $query = "INSERT INTO {$this->table} (descricao, data_manutencao, tecnico_id, chamado_id) 
                  VALUES (:descricao, :data_manutencao, :tecnico_id, :chamado_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":data_manutencao", $this->data_manutencao);
        $stmt->bindParam(":tecnico_id", $this->tecnico_id);
        $stmt->bindParam(":chamado_id", $this->chamado_id);
        return $stmt->execute();
    }

    public function listarTodos() {
        $query = "SELECT m.*, u.nome AS tecnico_nome, c.descricao AS chamado_descricao
                  FROM {$this->table} m
                  LEFT JOIN usuario u ON m.tecnico_id = u.id
                  LEFT JOIN chamado c ON m.chamado_id = c.id
                  ORDER BY m.data_manutencao DESC";
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
        $query = "UPDATE {$this->table} SET descricao = :descricao, data_manutencao = :data_manutencao, tecnico_id = :tecnico_id, chamado_id = :chamado_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":data_manutencao", $this->data_manutencao);
        $stmt->bindParam(":tecnico_id", $this->tecnico_id);
        $stmt->bindParam(":chamado_id", $this->chamado_id);
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
