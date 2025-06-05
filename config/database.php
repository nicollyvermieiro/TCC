<?php
class Database {
    private $host = "localhost";
    private $port = "5432";
    private $db_name = "manutsmart";
    private $username = "postgres";
    private $password = "postgres";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Erro ao conectar: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
