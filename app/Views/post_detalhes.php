<?php if ($post): ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/TrabalhoPHP/app/Views/css/style.css?v=<?php echo time(); ?>" />
</head>
<body>
    <header>
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <a href="/TrabalhoPHP/categoria?id=<?= $post['category_id'] ?? '' ?>" class="btn-criar-post">Voltar aos Posts</a>
    </header>

    <main class="main-container">
        <div class="post-container">
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            <div class="post-actions">
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
                    <a href="/TrabalhoPHP/post-editar?id=<?= $post['id'] ?>" class="btn-action">Editar</a>
                    <a href="/TrabalhoPHP/post-excluir?id=<?= $post['id'] ?>" class="btn-action" onclick="return confirm('Tem certeza que deseja excluir este post?');">Excluir</a>
                <?php endif; ?>
            </div>
        </div>

        <form method="POST" action="/TrabalhoPHP/comentario-criar" class="comment-form">
            <h3>Deixe seu Comentário</h3>
            <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
            <textarea name="content" rows="4" placeholder="Escreva seu comentário..." required></textarea>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <button type="submit" class="btn-criar-post">Comentar</button>
        </form>

        <!-- Comentários existentes -->
        <?php if (!empty($comentarios)): ?>
            <h2>Comentários</h2>
            <?php foreach ($comentarios as $comentario): ?>
            <div class="comment">
                <div class="comment-header">
                    <strong><?= htmlspecialchars($comentario['username']) ?></strong> comentou:
                    <small><?= htmlspecialchars($comentario['created_at']) ?></small>
                </div>
                <div class="comment-content">
                    <?= nl2br(htmlspecialchars($comentario['content'])) ?>
                </div>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comentario['user_id']): ?>
                    <div class="comment-actions">
                        <a href="/TrabalhoPHP/comentario-editar?id=<?= $comentario['id'] ?>" class="btn-action">Editar</a>
                        <a href="/TrabalhoPHP/comentario-excluir?id=<?= $comentario['id'] ?>" class="btn-action" onclick="return confirm('Tem certeza que deseja excluir este comentário?');">Excluir</a>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-comments">Nenhum comentário ainda. Seja o primeiro a comentar!</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> - Fórum PHP</p>
    </footer>
</body>
</html>
<?php else: ?>
    <p>Post não encontrado.</p>
<?php endif; ?>
