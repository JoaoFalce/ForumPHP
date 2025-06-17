<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

$basePath = '/TrabalhoPHP';
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

$path = trim($path, '/');

require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/CategoriaController.php';
require_once __DIR__ . '/app/controllers/CommentController.php';
require_once __DIR__ . '/app/controllers/PostController.php';

if (empty($path) || $path === 'index') {
    header("Location: /TrabalhoPHP/login");
    exit();
}

switch ($path) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            AuthController::login();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET' || isset($_SESSION['login_error'])) {
            require __DIR__ . '/app/Views/auth/login.php';
        }
        break;

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            AuthController::register();
        }
        require __DIR__ . '/app/Views/auth/register.php';
        break;

    case 'recover':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            AuthController::recover();
        }
        require __DIR__ . '/app/Views/auth/recover.php';
        break;

    case 'forum':
       
        $categorias = CategoriaController::index();
        $posts = PostController::list();

        require __DIR__ . '/app/Views/forum.php';
        break;

    case 'logout':
        AuthController::logout();
        break;

  
    case 'categorias':
        $categorias = CategoriaController::index();
        require __DIR__ . '/app/Views/categorias.php';
        break;
            

    case 'categoria':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if ($id) {
            $categoria = CategoriaController::get($id);

            
            $posts = PostController::getByCategory($id);
            require __DIR__ . '/app/Views/categoria.php';
        } else {
            header("Location: /TrabalhoPHP/forum");
            exit();
        }
        break;

    

    case 'post-criar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            PostController::create();
        }
        require __DIR__ . '/app/Views/post_criar.php';
        break;

    case 'post-editar':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if (!$id) {
            header("Location: /TrabalhoPHP/forum");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            PostController::update($id);
        }
        $post = PostController::get($id);
        require __DIR__ . '/app/Views/post_editar.php';
        break;

    case 'post-excluir':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if ($id) {
            PostController::delete($id);
        }
        header("Location: /TrabalhoPHP/forum");
        exit();
        break;

   
    case 'post':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id) {
            $post = PostController::get($id);
            $comentarios = CommentController::listByPost($id);
            require __DIR__ . '/app/Views/post_detalhes.php';
        } else {
            header("Location: /TrabalhoPHP/forum");
            exit();
        }
        break;

 
    case 'comentario':
        $post_id = isset($_GET['post_id']) ? (int) $_GET['post_id'] : null;
        $comentarios = CommentController::listByPost($post_id);
        require __DIR__ . '/app/Views/comentarios.php';
        break;

    case 'comentario-criar':
        CommentController::create();
        break;

    case 'comentario-editar':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if (!$id) {
            header("Location: /TrabalhoPHP/forum");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CommentController::update($id);
        } else {
            $comment = CommentController::edit($id);
            require __DIR__ . '/app/Views/comentario_editar.php';
        }
        break;

    case 'comentario-excluir':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if ($id) {
            CommentController::delete($id);
        } else {
            header("Location: /TrabalhoPHP/forum");
        }
        exit();
        break;

    default:
        http_response_code(404);
        echo "Página não encontrada: " . htmlspecialchars($path);
        break;
}
