<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Usuarios.php';
require_once __DIR__ . '/../helpers/session.php'; 

class UsuariosController
{
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    // Verifica se usuário logado é admin
    private function verificarAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario_id']) || ($_SESSION['cargo_id'] ?? null) != 1) {
            setFlashMessage("Acesso negado. Apenas administradores podem acessar.", "danger");
            header("Location: ?route=auth/dashboard");
            exit;
        }
    }

    // Verifica se existe algum admin
    public function existeAdministrador() {
        $query = "SELECT COUNT(*) FROM usuario WHERE cargo_id = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    // Listar usuários
    public function listar() {
        $this->verificarAdmin();

        $query = "SELECT u.id, u.nome, u.email, c.nome AS cargo 
                  FROM usuario u
                  JOIN cargo c ON u.cargo_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../Views/usuarios/listar.php';
    }

    // Formulário de criação
    public function criar() {
        // Se não existe admin, mostra criar primeiro admin sem exigir login
        if (!$this->existeAdministrador()) {
            require __DIR__ . '/../Views/usuarios/criar_primeiro_admin.php';
            exit;
        }

        $this->verificarAdmin();

        // Buscar cargos
        $query = "SELECT id, nome FROM cargo ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $cargos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Buscar setores
        $query2 = "SELECT id, nome FROM setor ORDER BY nome ASC";
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->execute();
        $setores = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../Views/usuarios/criar.php';
    }

    // Salvar primeiro admin
    public function salvarPrimeiroAdmin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuarios();
            $usuario->nome = $_POST['nome'] ?? '';
            $usuario->email = $_POST['email'] ?? '';
            $usuario->senha = $_POST['senha'] ?? '';
            $usuario->cargo_id = 1;

            if ($usuario->criar()) {
                setFlashMessage("Administrador criado com sucesso.", "success");
                header('Location: ?route=auth/loginForm');
                exit;
            } else {
                setFlashMessage("Erro ao criar administrador.", "danger");
                header('Location: ?route=usuarios/criar');
                exit;
            }
        } else {
            setFlashMessage("Requisição inválida.", "warning");
            header('Location: ?route=usuarios/criar');
            exit;
        }
    }

    // Salvar usuário normal
    public function salvar() {
    $this->verificarAdmin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario = new Usuarios();
        $usuario->nome = $_POST['nome'] ?? '';
        $usuario->email = $_POST['email'] ?? '';
        $usuario->senha = $_POST['senha'] ?? '';
        $usuario->cargo_id = $_POST['cargo_id'] ?? null;

        // Buscar nome do cargo no DB
        $stmt = $this->conn->prepare("SELECT nome FROM cargo WHERE id = :id");
        $stmt->execute(['id' => $usuario->cargo_id]);
        $cargo_nome = strtolower($stmt->fetchColumn() ?? '');

        // Só define setor se for técnico
        if ($cargo_nome === 'técnico') {
            $usuario->setor_id = $_POST['setor_id'] ?? null;
        } else {
            $usuario->setor_id = null;
        }

        try {
            if ($usuario->criar()) {
                setFlashMessage("Usuário criado com sucesso.", "success");
                header('Location: ?route=usuarios/listar');
                exit;
            }
        } catch (Exception $e) {
            setFlashMessage($e->getMessage(), "warning");
            header('Location: ?route=usuarios/criar');
            exit;
        }
    } else {
        setFlashMessage("Requisição inválida.", "warning");
        header('Location: ?route=usuarios/listar');
        exit;
    }
}



    // Editar usuário
    public function editar() {
    $this->verificarAdmin();

    $id = $_GET['id'] ?? null;
    if (!$id) {
        setFlashMessage("ID do usuário não fornecido.", "warning");
        header('Location: ?route=usuarios/listar');
        exit;
    }

    $query = "SELECT u.*, s.nome AS setor_nome
              FROM usuario u
              LEFT JOIN setor s ON u.setor_id = s.id
              WHERE u.id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $id]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dados) {
        setFlashMessage("Usuário não encontrado.", "warning");
        header('Location: ?route=usuarios/listar');
        exit;
    }

    $query2 = "SELECT id, nome FROM cargo ORDER BY nome ASC";
    $stmt2 = $this->conn->prepare($query2);
    $stmt2->execute();
    $cargos = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $query3 = "SELECT id, nome FROM setor ORDER BY nome ASC";
    $stmt3 = $this->conn->prepare($query3);
    $stmt3->execute();
    $setores = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    require __DIR__ . '/../Views/usuarios/editar.php';
}


    // Atualizar usuário
    public function atualizar() {
    $this->verificarAdmin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario = new Usuarios();
        $usuario->id = $_POST['id'] ?? null;
        $usuario->nome = $_POST['nome'] ?? '';
        $usuario->email = $_POST['email'] ?? '';
        $usuario->cargo_id = $_POST['cargo_id'] ?? null;
        $usuario->senha = !empty($_POST['senha']) ? $_POST['senha'] : null;

        // Buscar o nome do cargo no banco para definir setor
        $stmt = $this->conn->prepare("SELECT nome FROM cargo WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $usuario->cargo_id]);
        $cargo_nome = $stmt->fetchColumn() ?? '';

        // Definir setor apenas se for técnico
        if (stripos(trim($cargo_nome), 'técnico') !== false) {
            $usuario->setor_id = !empty($_POST['setor_id']) ? $_POST['setor_id'] : null;
        } else {
            $usuario->setor_id = null;
        }

        try {
            if ($usuario->atualizar()) {
                setFlashMessage("Usuário atualizado com sucesso.", "success");
                header("Location: ?route=usuarios/listar");
                exit;
            } else {
                setFlashMessage("Erro ao atualizar usuário.", "danger");
                header("Location: ?route=usuarios/editar&id={$usuario->id}");
                exit;
            }
        } catch (Exception $e) {
            setFlashMessage("Erro ao atualizar usuário: " . $e->getMessage(), "danger");
            header("Location: ?route=usuarios/editar&id={$usuario->id}");
            exit;
        }

    } else {
        setFlashMessage("Requisição inválida.", "warning");
        header('Location: ?route=usuarios/listar');
        exit;
    }
}

    // Excluir usuário
    public function excluir() {
        $this->verificarAdmin();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            setFlashMessage("ID do usuário não fornecido.", "warning");
            header('Location: ?route=usuarios/listar');
            exit;
        }

        $usuario = new Usuarios();
        if ($usuario->excluir($id)) {
            setFlashMessage("Usuário excluído com sucesso.", "success");
            header('Location: ?route=usuarios/listar');
            exit;
        } else {
            setFlashMessage("Erro ao excluir usuário. Verifique dependências.", "danger");
            header('Location: ?route=usuarios/listar');
            exit;
        }
    }
}
