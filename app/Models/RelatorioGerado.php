<?php

require_once __DIR__ . '/../../config/database.php';

class RelatorioGerado {
    private $conn;
    private $table = "relatorio_gerado";

    public $id;
    public $titulo;
    public $descricao;
    public $data_geracao;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function criar() {
        $query = "INSERT INTO {$this->table} (titulo, descricao, data_geracao) VALUES (:titulo, :descricao, :data_geracao)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":data_geracao", $this->data_geracao);
        return $stmt->execute();
    }

    public function atualizar() {
        $query = "UPDATE {$this->table} SET titulo = :titulo, descricao = :descricao, data_geracao = :data_geracao WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":data_geracao", $this->data_geracao);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function listarTodos() {
        $query = "SELECT * FROM {$this->table} ORDER BY data_geracao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
