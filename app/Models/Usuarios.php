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
    public $setor_id;

    public function __construct($db = null) {
        $this->conn = $db ?? (new Database())->getConnection();
    }

    public function listarTodos() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

     public function listarPorCargo($cargoNome) {
        $sql = "SELECT u.id, u.nome 
                FROM usuario u
                JOIN cargo c ON u.cargo_id = c.id
                WHERE c.nome = :cargoNome";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['cargoNome' => $cargoNome]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


     public function criar() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nome, email, senha, cargo_id, setor_id) 
                  VALUES (:nome, :email, :senha, :cargo_id, :setor_id)";
        $stmt = $this->conn->prepare($query);

        $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);


        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", $senhaHash);
        $stmt->bindParam(":cargo_id", $this->cargo_id);
        $stmt->bindParam(":setor_id", $this->setor_id); // NOVO

        try {
            $stmt->execute();
            $this->id = $this->conn->lastInsertId();
            return true;
        } catch (PDOException $e) {
            // Verifica se é erro de chave única (email duplicado)
            if ($e->getCode() == '23505') { // PostgreSQL: unique_violation
                throw new Exception("O email '{$this->email}' já está cadastrado.");
            } else {
                throw new Exception("Erro ao criar usuário: " . $e->getMessage());
            }
        }
    }


    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

public function atualizar() {
    $query = "UPDATE usuario SET nome = :nome, email = :email, cargo_id = :cargo_id, setor_id = :setor_id";

    if (!empty($this->senha)) {
        $query .= ", senha = :senha";
    }

    $query .= " WHERE id = :id";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':nome', $this->nome);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':cargo_id', $this->cargo_id);
    $stmt->bindParam(':setor_id', $this->setor_id);
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

    public function salvarTokenRecuperacao($id, $token)
    {
        $query = "UPDATE usuario SET token_recuperacao = :token WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function buscarPorToken($token)
    {
        $query = "SELECT * FROM usuario WHERE token_recuperacao = :token LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarSenha($id, $novaSenha)
    {
        $query = "UPDATE usuario SET senha = :senha, token_recuperacao = NULL WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':senha', $novaSenha);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }


}
