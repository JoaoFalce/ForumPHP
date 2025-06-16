<?php
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Post.php';

class CategoriaController {

    public static function index() {
        $categoria = Category::getAll();
        return $categoria;
    }


    public static function verCategoria() {
        session_start();
        if (!isset($_SESSION['user_logged']) || $_SESSION['user_logged'] !== true) {
            header("Location: /TrabalhoPHP/login");
            exit();
        }

        $categoria_id = $_GET['id'] ?? null;

        if (!$categoria_id) {
            header("Location: /TrabalhoPHP/forum");
            exit();
        }

        $categoriaSelecionada = Category::getById($categoria_id);
        $postsDaCategoria = Post::getByCategoryId($categoria_id);
        $categorias = Category::getAll();

        require __DIR__ . '/../Views/forum.php';
    }

    /*
    public static function create() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('Token CSRF inválido');
            }

            $name = trim($_POST['name'] ?? '');

            if (empty($name)) {
                $_SESSION['category_error'] = 'Nome da categoria é obrigatório';
                return;
            }

            if (Category::create($name)) {
                $_SESSION['category_success'] = 'Categoria criada com sucesso!';
                header("Location: /TrabalhoPHP/forum");
                exit();
            } else {
                $_SESSION['category_error'] = 'Erro ao criar categoria.';
            }
        }
    }
        */

        /*
        public static function edit($id) {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('Token CSRF inválido');
            }

            $name = trim($_POST['name'] ?? '');

            if (empty($name)) {
                $_SESSION['category_error'] = 'Nome da categoria é obrigatório';
                return;
            }

            if (Category::update($id, $name)) {
                $_SESSION['category_success'] = 'Categoria atualizada com sucesso!';
                header("Location: /TrabalhoPHP/forum");
                exit();
            } else {
                $_SESSION['category_error'] = 'Erro ao atualizar categoria.';
            }
        }
    }
        */

        /*
    public static function delete($id) {
        session_start();
        if (Category::delete($id)) {
            $_SESSION['category_success'] = 'Categoria excluída com sucesso!';
        } else {
            $_SESSION['category_error'] = 'Erro ao excluir categoria.';
        }
        header("Location: /TrabalhoPHP/forum");
        exit();
    }
        */
    public static function get($id) {
        return Category::getById($id);
    }

}    
?>
