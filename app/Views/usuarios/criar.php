<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Usuário</title>
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>
    <h1>Criar Usuário</h1>
    <form method="post" action="?route=usuarios/salvar">
        <label>Nome: <input type="text" name="nome" required></label><br><br>
        <label>Email: <input type="email" name="email" required></label><br><br>
        <label>Senha: <input type="password" name="senha" required></label><br><br>
        <label>
            Cargo:
            <select name="cargo_id" required>
                <option value="">-- Selecione --</option>
                <option value="1">Administrador</option>
                <option value="2">Técnico</option>
                <option value="3">Usuário comum</option>
                <option value="4">Funcionário</option>
            </select>
        </label><br><br>
        <button type="submit">Salvar</button>
    </form>
    <br>
    <a href="?route=usuarios/listar">Voltar para Lista</a>
</body>
</html>
