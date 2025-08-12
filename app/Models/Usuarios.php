<?php
require_once __DIR__ . '/../../config/database.php';

class Usuarios {
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

        if (password_get_info($this->senha)['algo'] === 0) {
            $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);
        } else {
            $senhaHash = $this->senha;
        }

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", $senhaHash);
        $stmt->bindParam(":cargo_id", $this->cargo_id);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar() {
        $query = "UPDATE " . $this->table_name . " SET nome = :nome, email = :email, cargo_id = :cargo_id";
        
        if (!empty($this->senha)) {
            $query .= ", senha = :senha";
        }

        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':cargo_id', $this->cargo_id);
        $stmt->bindParam(':id', $this->id);

        if (!empty($this->senha)) {
            $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);
            $stmt->bindParam(':senha', $senhaHash);
        }

        return $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function autenticar($email, $senha) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $this->id = $usuario['id'];
            $this->nome = $usuario['nome'];
            $this->email = $usuario['email'];
            $this->cargo_id = $usuario['cargo_id'];
            return true;
        }
        return false;
    }

    public function buscarPorEmail($email) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
