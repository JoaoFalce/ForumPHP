<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_logged']) || $_SESSION['user_logged'] !== true) {
    header("Location: /TrabalhoPHP/login");
    exit();
}

if (!isset($comment)) {
    echo "Comentário não encontrado.";
    exit();
}

// Captura possíveis mensagens de erro/sucesso
$comment_error = $_SESSION['comment_error'] ?? null;
$comment_success = $_SESSION['comment_success'] ?? null;

// Limpa mensagens para não repetir
unset($_SESSION['comment_error'], $_SESSION['comment_success']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Comentário</title>
    <link rel="stylesheet" href="/TrabalhoPHP/app/Views/css/style.css?v=1" />
</head>
<body>
    <header>
        <h1>Editar Comentário</h1>
        <p><a href="/TrabalhoPHP/post?id=<?= $comment['post_id'] ?>">Voltar ao Post</a></p>
    </header>

    <main>
        <?php if ($comment_error): ?>
            <div class="error"><?= htmlspecialchars($comment_error) ?></div>
        <?php endif; ?>

        <?php if ($comment_success): ?>
            <div class="success"><?= htmlspecialchars($comment_success) ?></div>
        <?php endif; ?>

        <form method="POST" action="/TrabalhoPHP/comentario-editar?id=<?= $comment['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
            <textarea name="content" rows="6" required><?= htmlspecialchars($comment['content']) ?></textarea>
            <button type="submit" class="btn-criar-post">Salvar Alterações</button>
        </form>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> - Fórum PHP</p>
    </footer>
</body>
</html>
