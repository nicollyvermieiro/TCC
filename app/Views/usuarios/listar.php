<!DOCTYPE html>
<html>
<head>
    <title>Lista de Usuários</title>
    <meta charset="UTF-8">
</head>
<body>
    
    <?php include __DIR__ . '/../partials/menu.php'; ?>
    <h1>Usuários</h1>
    <a href="?route=usuarios/criar">Criar Novo Usuário</a><br><br>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Cargo</th>
                <th>Ações</th> <!-- Nova coluna para os botões -->
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($usuarios)): ?>
                <?php foreach($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['id']) ?></td>
                        <td><?= htmlspecialchars($usuario['nome']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= htmlspecialchars($usuario['cargo']) ?></td>
                        <td>
                            <a href="?route=usuarios/editar&id=<?= $usuario['id'] ?>">Editar</a> |
                            <a href="?route=usuarios/excluir&id=<?= $usuario['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Nenhum usuário encontrado.</td></tr> <!-- Ajustado colspan para 5 -->
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
