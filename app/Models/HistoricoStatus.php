<?php

require_once __DIR__ . '/../../config/database.php';

class HistoricoStatus {
    private $conn;
    private $table = "historico_status";

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function registrar($chamado_id, $status, $observacao) {
        $query = "INSERT INTO historico_status (chamado_id, novo_status, observacao, criado_em)
                VALUES (:chamado_id, :novo_status, :observacao, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":chamado_id", $chamado_id);
        $stmt->bindParam(":novo_status", $status);
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
