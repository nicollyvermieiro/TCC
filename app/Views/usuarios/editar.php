<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
</head>
<body>
    <?php include __DIR__ . '/../partials/menu.php'; ?>
    <h1>Editar Usuário</h1>
    <form method="post" action="?route=usuarios/atualizar">
        <input type="hidden" name="id" value="<?= htmlspecialchars($dados['id']) ?>">
        
        <label>Nome:
            <input type="text" name="nome" value="<?= htmlspecialchars($dados['nome']) ?>" required>
        </label><br><br>
        
        <label>Email:
            <input type="email" name="email" value="<?= htmlspecialchars($dados['email']) ?>" required>
        </label><br><br>
        
        <label>Senha: (Deixe em branco para não alterar)
            <input type="password" name="senha" autocomplete="new-password">
        </label><br><br>
        
        <label>Cargo:
            <select name="cargo_id" required>
                <option value="">-- Selecione --</option>
                <option value="1" <?= $dados['cargo_id'] == 1 ? 'selected' : '' ?>>Administrador</option>
                <option value="2" <?= $dados['cargo_id'] == 2 ? 'selected' : '' ?>>Técnico</option>
                <option value="3" <?= $dados['cargo_id'] == 3 ? 'selected' : '' ?>>Usuário comum</option>
                <option value="4" <?= $dados['cargo_id'] == 4 ? 'selected' : '' ?>>Funcionário</option>
            </select>
        </label><br><br>
        
        <button type="submit">Atualizar</button>
    </form>
    <br>
    <a href="?route=usuarios/listar">Voltar para Lista</a>
</body>
</html>
