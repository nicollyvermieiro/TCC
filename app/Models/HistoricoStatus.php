<?php

require_once __DIR__ . '/../../config/database.php';

class HistoricoStatus {
    private $conn;
    private $table = "historico_status";

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function registrar($chamado_id, $status, $observacao = null) {
        $query = "INSERT INTO {$this->table} (chamado_id, status, observacao, data_alteracao) 
                  VALUES (:chamado_id, :status, :observacao, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":chamado_id", $chamado_id);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":observacao", $observacao);
        return $stmt->execute();
    }

    public function listarTodos() {
        $query = "SELECT * FROM {$this->table} ORDER BY data_alteracao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorChamado($chamado_id) {
        $query = "SELECT * FROM {$this->table} WHERE chamado_id = :chamado_id ORDER BY data_alteracao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":chamado_id", $chamado_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
