<?php

require_once __DIR__ . '/../../config/database.php';

class AnexoChamado {
    private $conn;
    private $table = "anexo_chamado";

    public $id, $chamado_id, $caminho_arquivo;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function salvar($chamado_id, $caminho_arquivo) {
        $query = "INSERT INTO {$this->table} (chamado_id, caminho_arquivo) VALUES (:chamado_id, :caminho)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':chamado_id', $chamado_id);
        $stmt->bindParam(':caminho', $caminho_arquivo);
        return $stmt->execute();
    }

    public function listarPorChamado($chamado_id) {
        $query = "SELECT * FROM {$this->table} WHERE chamado_id = :chamado_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':chamado_id', $chamado_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluir($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
