<?php
require_once __DIR__ . '/../../config/database.php';

class Usuario {
    private $conn;
    private $table_name = "usuario";

    public $id;
    public $nome;
    public $email;
    public $senha;
    public $cargo_id;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarTodos() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function criar() {
        $query = "INSERT INTO " . $this->table_name . " (nome, email, senha, cargo_id) VALUES (:nome, :email, :senha, :cargo_id)";
        $stmt = $this->conn->prepare($query);

        $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", $senhaHash);
        $stmt->bindParam(":cargo_id", $this->cargo_id);

        return $stmt->execute();
    }
}
