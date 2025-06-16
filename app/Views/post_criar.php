<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_logged']) || $_SESSION['user_logged'] !== true) {
    header("Location: /TrabalhoPHP/login");
    exit();
}

$post_error = $_SESSION['post_error'] ?? null;
$post_success = $_SESSION['post_success'] ?? null;

unset($_SESSION['post_error'], $_SESSION['post_success']);

require_once __DIR__ . '/../Controllers/CategoriaController.php';
$categories = CategoriaController::index();

$selectedCategoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Criar Novo Post</title>
    <link rel="stylesheet" href="/TrabalhoPHP/app/Views/css/style.css">
</head>
<body>
    <h1>Criar Novo Post</h1>

    <?php if ($post_error): ?>
        <div style="color: red;"><?= htmlspecialchars($post_error) ?></div>
    <?php endif; ?>

    <?php if ($post_success): ?>
        <div style="color: green;"><?= htmlspecialchars($post_success) ?></div>
    <?php endif; ?>

    <form method="POST" action="/TrabalhoPHP/post-criar">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />

        <div>
            <label for="title">Título:</label><br />
            <input type="text" id="title" name="title" required maxlength="255" />
        </div>

        <div>
            <label for="content">Conteúdo:</label><br />
            <textarea id="content" name="content" required rows="8" cols="60"></textarea>
        </div>

        <div>
            <label for="category_id">Categoria:</label><br />
            <select id="category_id" name="category_id" required>
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= $selectedCategoryId === $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Criar Post</button>
    </form>

    <p><a href="/TrabalhoPHP/forum">Voltar ao Fórum</a></p>
</body>
</html>
