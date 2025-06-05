<?php
require_once __DIR__ . '/../../config/database.php';

class Cargo {
    private $conn;
    private $table = "cargo";

    public $id, $nome;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    // Listar todos os cargos
    public function listarTodos() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Criar um novo cargo
    public function criar() {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (nome) VALUES (:nome)");
        $stmt->bindParam(':nome', $this->nome);
        return $stmt->execute();
    }

    // Buscar um cargo pelo ID
    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualizar um cargo existente
    public function atualizar() {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET nome = :nome WHERE id = :id");
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Excluir um cargo
    public function excluir($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
