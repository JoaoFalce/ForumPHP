<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_logged']) || $_SESSION['user_logged'] !== true) {
    header("Location: /TrabalhoPHP/login");
    exit();
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Posts da categoria: <?= htmlspecialchars($categoria['name']) ?></title>
    <link rel="stylesheet" href="/TrabalhoPHP/app/Views/css/style.css?v=1">
</head>
<body>
    <header>
        <h1>Categoria: <?= htmlspecialchars($categoria['name']) ?></h1>
        <p><a href="/TrabalhoPHP/forum">Voltar ao Fórum</a></p>
    </header>

    <main>
        <a href="/TrabalhoPHP/post-criar?category_id=<?= $categoria['id'] ?>" class="btn-criar-post" style="margin-bottom: 10px; display: inline-block;">Criar Post</a>
        <?php if (!empty($posts)): ?>
            <ul>
                <?php foreach ($posts as $post): ?>
                    <li>
                <div class="post-container" style="background-color: #e0f7fa; border-radius: 8px; padding: 15px 20px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0, 77, 64, 0.3);">
                    <a href="/TrabalhoPHP/post?id=<?= $post['id'] ?>" style="text-decoration: none; color: inherit; display: block;">
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        <p><small>Por: <?= htmlspecialchars($post['username']) ?> em <?= $post['created_at'] ?></small></p>
                    </a>
                    <p style="margin-top: 10px;">
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
                            <a href="/TrabalhoPHP/post-editar?id=<?= $post['id'] ?>" class="btn-action">Editar</a>
                            <a href="/TrabalhoPHP/post-excluir?id=<?= $post['id'] ?>" class="btn-action" onclick="return confirm('Tem certeza que deseja excluir este post?');">Excluir</a>
                        <?php endif; ?>
                    </p>
                </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum post nessa categoria.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> - Fórum PHP</p>
    </footer>
</body>
</html>
