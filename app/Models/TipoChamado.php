<?php

require_once __DIR__ . '/../../config/database.php';

class TipoChamado {
    private $conn;
    private $table = "tipo_chamado";

    public $id;
    public $nome;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    // Listar todos os tipos de chamado
    public function listarTodos() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Criar tipo de chamado
    public function criar() {
        $query = "INSERT INTO {$this->table} (nome) VALUES (:nome)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $this->nome);
        return $stmt->execute();
    }

    // Buscar tipo por ID
    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualizar tipo de chamado
    public function atualizar() {
        $query = "UPDATE {$this->table} SET nome = :nome WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Excluir tipo de chamado
    public function excluir($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
