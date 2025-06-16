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

    
    public static function get($id) {
        return Category::getById($id);
    }

}    
?>
