<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$post_error = $_SESSION['post_error'] ?? null;
$post_success = $_SESSION['post_success'] ?? null;

unset($_SESSION['post_error'], $_SESSION['post_success']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Post</title>
    <link rel="stylesheet" href="/TrabalhoPHP/app/Views/css/style.css?v=1" />
</head>
<body>
    <header>
        <h1>Editar Post</h1>
    </header>

    <main>
        <div style="background-color: #e0f7fa; border-radius: 8px; padding: 15px 20px; box-shadow: 0 2px 8px rgba(0, 77, 64, 0.3); margin-bottom: 20px;">
            <?php if ($post_error): ?>
                <div class="error"><?= htmlspecialchars($post_error) ?></div>
            <?php endif; ?>

            <?php if ($post_success): ?>
                <div class="success"><?= htmlspecialchars($post_success) ?></div>
            <?php endif; ?>

            <form method="POST" action="/TrabalhoPHP/post-editar?id=<?= $post['id'] ?>">
                <label for="title">Título:</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required />

                <label for="content">Conteúdo:</label>
                <textarea id="content" name="content" rows="8" required><?= htmlspecialchars($post['content']) ?></textarea>

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                <button type="submit" class="btn-criar-post">Salvar Alterações</button>
            </form>
        </div>

        <p><a href="/TrabalhoPHP/forum">Voltar ao Fórum</a></p>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> - Fórum PHP</p>
    </footer>
</body>
</html>
