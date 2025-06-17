<?php

require_once __DIR__ . '/../Models/Post.php';
require_once __DIR__ . '/../Models/Category.php';

class PostController {

    public static function create() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

           

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['post_error'] = "Erro de segurança: CSRF token inválido.";
                header("Location: /TrabalhoPHP/post-criar");
                exit();
            }

            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $categoryId = $_POST['category_id'] ?? null;
            $userId = $_SESSION['user_id'] ?? null;

            if (empty($title) || empty($content) || empty($categoryId) || empty($userId)) {
                $_SESSION['post_error'] = "Todos os campos são obrigatórios.";
                header("Location: /TrabalhoPHP/post-criar");
                exit();
            }

            if (Post::create($userId, $title, $content, $categoryId)) {
                $_SESSION['post_success'] = "Post criado com sucesso!";
                header("Location: /TrabalhoPHP/forum");
                exit();
            } else {
                $_SESSION['post_error'] = "Erro ao criar o post.";
                header("Location: /TrabalhoPHP/post-criar");
                exit();
            }
        }
    }

    public static function list() {
        return Post::getAllPostsWithUsernamesAndCategories();
    }

    public static function get($id) {
        return Post::getById($id);
    }

    public static function getByCategory($categoryId) {
        return Post::getByCategoryId($categoryId);
    }

    public static function update($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

            

        
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['post_error'] = "Erro de segurança: CSRF token inválido.";
            header("Location: /TrabalhoPHP/post-editar?id=" . $id);
            exit();
        }

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $userId = $_SESSION['user_id'];

        $existingPost = Post::getById($id);
        if (!$existingPost) {
            $_SESSION['post_error'] = "Post não encontrado.";
            header("Location: /TrabalhoPHP/forum");
            exit();
        }

       
        if ($existingPost['user_id'] != $userId) {
            $_SESSION['post_error'] = "Você não tem permissão para editar este post.";
            header("Location: /TrabalhoPHP/post?id=" . $id);
            exit();
        }

        if (empty($title) || empty($content)) {
            $_SESSION['post_error'] = "Título e conteúdo são obrigatórios.";
            header("Location: /TrabalhoPHP/post-editar?id=" . $id);
            exit();
        }

        if (Post::update($id, $title, $content)) {
            $_SESSION['post_success'] = "Post atualizado com sucesso!";
            header("Location: /TrabalhoPHP/post?id=" . $id);
            exit();
        } else {
            $_SESSION['post_error'] = "Erro ao atualizar o post.";
            header("Location: /TrabalhoPHP/post-editar?id=" . $id);
            exit();
        }
    }

    public static function delete($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

            

        $userId = $_SESSION['user_id'];

        $existingPost = Post::getById($id);
        if (!$existingPost) {
            $_SESSION['post_error'] = "Post não encontrado.";
            header("Location: /TrabalhoPHP/forum");
            exit();
        }

        
        if ($existingPost['user_id'] != $userId) {
            $_SESSION['post_error'] = "Você não tem permissão para excluir este post.";
            header("Location: /TrabalhoPHP/post?id=" . $id);
            exit();
        }

        if (Post::delete($id)) {
            $_SESSION['post_success'] = "Post excluído com sucesso!";
        } else {
            $_SESSION['post_error'] = "Erro ao excluir o post.";
        }
        header("Location: /TrabalhoPHP/forum");
        exit();
    }
}
