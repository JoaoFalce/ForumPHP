<?php

if (!isset($_SESSION['user_logged']) || $_SESSION['user_logged'] !== true) {
    header("Location: /TrabalhoPHP/login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Fórum - Categorias</title>
    <link rel="stylesheet" href="/TrabalhoPHP/app/Views/css/style.css?v=1">
</head>
<body>
    <header>
        <h1>Bem-vindo ao Fórum</h1>
        <p>Olá, <?= htmlspecialchars($_SESSION['user_email']) ?> | <a href="/TrabalhoPHP/logout">Sair</a></p>
    </header>

    <main>
        <section>
            <h2>Categorias Disponíveis</h2>

            <?php if (!empty($categorias)): ?>
                <ul>
                    <?php foreach ($categorias as $categoria): ?>
                        <li>
                            <a href="/TrabalhoPHP/categoria?id=<?= $categoria['id'] ?>">
                                <?= htmlspecialchars($categoria['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nenhuma categoria encontrada.</p>
            <?php endif; ?>
        </section>

        <?php
        if (isset($_GET['id'])) {
            require_once __DIR__ . '/../Models/Post.php';
            require_once __DIR__ . '/../Models/Category.php';

            $categoriaId = intval($_GET['id']);
            $categoria = Category::getById($categoriaId);
            $posts = Post::getByCategoryId($categoriaId);

            if ($categoria):
        ?>
            <section>
                <h2>Postagens em: <?= htmlspecialchars($categoria['name']) ?></h2>
                <a href="/TrabalhoPHP/post-criar?category_id=<?= $categoriaId ?>" style="display: inline-block; margin-bottom: 10px;">[Criar Post]</a>

                <?php if (!empty($posts)): ?>
                    <ul>
                        <?php foreach ($posts as $post): ?>
                            <li>
                                <strong><?= htmlspecialchars($post['title']) ?></strong><br>
                                <em><?= nl2br(htmlspecialchars($post['content'])) ?></em><br>
                                <small>Criado em: <?= $post['created_at'] ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Não há postagens nesta categoria.</p>
                <?php endif; ?>
            </section>
        <?php
            else:
                echo "<p>Categoria não encontrada.</p>";
            endif;
        }
        ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> - Fórum PHP</p>
    </footer>
</body>
</html>
