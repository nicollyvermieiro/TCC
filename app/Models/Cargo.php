<?php
require_once __DIR__ . '/../../config/database.php';

class Cargo {
    private $conn;
    private $table = "cargo";

    public $id;
    public $nome;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function listarTodos() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar() {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (nome) VALUES (:nome)");
        $stmt->bindParam(':nome', $this->nome);
        return $stmt->execute();
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar() {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET nome = :nome WHERE id = :id");
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function excluir($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
