<?php
require_once __DIR__ . '/../Models/Comment.php';

class CommentController {

    public static function listByPost($postId) {
        return Comment::listByPost($postId);
    }

    public static function create() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

    

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificação do token CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['comment_error'] = "Erro de segurança: CSRF token inválido.";
                header("Location: /TrabalhoPHP/forum");
                exit();
            }

            $postId = $_POST['post_id'] ?? null;
            $content = $_POST['content'] ?? '';
            $userId = $_SESSION['user_id'] ?? null;

            if (empty($postId) || empty($content) || empty($userId)) {
                $_SESSION['comment_error'] = "O post_id e o conteúdo do comentário são obrigatórios.";
                header("Location: /TrabalhoPHP/post?id=" . $postId);
                exit();
            }

            if (Comment::create($postId, $userId, $content)) {
                $_SESSION['comment_success'] = "Comentário adicionado com sucesso!";
                header("Location: /TrabalhoPHP/post?id=" . $postId);
                exit();
            } else {
                $_SESSION['comment_error'] = "Erro ao adicionar o comentário.";
                header("Location: /TrabalhoPHP/post?id=" . $postId);
                exit();
            }
        } else {
            // Se tentar acessar via GET, redireciona.
            header("Location: /TrabalhoPHP/forum");
            exit();
        }
    }

    
        
    public static function edit($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

            
        if (!isset($_SESSION['user_logged']) || $_SESSION['user_logged'] !== true) {
            $_SESSION['comment_error'] = "Você precisa estar logado para editar comentários.";
            header("Location: /TrabalhoPHP/login");
            exit();
        }
            

        $comment = Comment::getById($id);
        if (!$comment) {
            $_SESSION['comment_error'] = "Comentário não encontrado.";
            header("Location: /TrabalhoPHP/forum");
            exit();
        }

        if ($comment['user_id'] != $_SESSION['user_id']) {
            $_SESSION['comment_error'] = "Você não tem permissão para editar este comentário.";
            header("Location: /TrabalhoPHP/post?id=" . $comment['post_id']);
            exit();
        }
        return $comment;
    }
        

    public static function update($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_logged']) || $_SESSION['user_logged'] !== true) {
            $_SESSION['comment_error'] = "Você precisa estar logado para editar comentários.";
            header("Location: /TrabalhoPHP/login");
            exit();
        }

        // Verificação do token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['comment_error'] = "Erro de segurança: CSRF token inválido.";
            header("Location: /TrabalhoPHP/comentario-editar?id=" . $id);
            exit();
        }

        $content = $_POST['content'] ?? '';
        $userId = $_SESSION['user_id'];

        $existingComment = Comment::getById($id);
        if (!$existingComment) {
            $_SESSION['comment_error'] = "Comentário não encontrado.";
            header("Location: /TrabalhoPHP/forum");
            exit();
        }

        if ($existingComment['user_id'] != $userId) {
            $_SESSION['comment_error'] = "Você não tem permissão para editar este comentário.";
            header("Location: /TrabalhoPHP/post?id=" . $existingComment['post_id']);
            exit();
        }

        if (empty($content)) {
            $_SESSION['comment_error'] = "O conteúdo do comentário é obrigatório.";
            header("Location: /TrabalhoPHP/comentario-editar?id=" . $id);
            exit();
        }

        if (Comment::update($id, $content)) {
            $_SESSION['comment_success'] = "Comentário atualizado com sucesso!";
            header("Location: /TrabalhoPHP/post?id=" . $existingComment['post_id']);
            exit();
        } else {
            $_SESSION['comment_error'] = "Erro ao atualizar o comentário.";
            header("Location: /TrabalhoPHP/comentario-editar?id=" . $id);
            exit();
        }
    }

    public static function delete($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_logged']) || $_SESSION['user_logged'] !== true) {
            $_SESSION['comment_error'] = "Você precisa estar logado para excluir comentários.";
            header("Location: /TrabalhoPHP/login");
            exit();
        }

        $userId = $_SESSION['user_id'];

        $existingComment = Comment::getById($id);
        if (!$existingComment) {
            $_SESSION['comment_error'] = "Comentário não encontrado.";
            header("Location: /TrabalhoPHP/forum");
            exit();
        }

        // Armazena o post_id antes de excluir o comentário
        $postId = $existingComment['post_id'];

        if ($existingComment['user_id'] != $userId) {
            $_SESSION['comment_error'] = "Você não tem permissão para excluir este comentário.";
            header("Location: /TrabalhoPHP/post?id=" . $postId);
            exit();
        }

        if (Comment::delete($id)) {
            $_SESSION['comment_success'] = "Comentário excluído com sucesso!";
        } else {
            $_SESSION['comment_error'] = "Erro ao excluir o comentário.";
        }
        header("Location: /TrabalhoPHP/post?id=" . $postId);
        exit();
    }
}
