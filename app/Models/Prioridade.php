<?php
require_once __DIR__ . '/../../config/database.php';

class Prioridade {
    private $conn;
    private $table = "prioridade_chamado";

    public $id;
    public $nome;
    public $nivel;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listarTodos() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY nivel ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar() {
        $query = "INSERT INTO {$this->table} (nome, nivel) VALUES (:nome, :nivel)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':nivel', $this->nivel);
        return $stmt->execute();
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar() {
        $query = "UPDATE {$this->table} SET nome = :nome, nivel = :nivel WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':nivel', $this->nivel);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function excluir($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
