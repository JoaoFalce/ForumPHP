<?php


require_once __DIR__ . '/../../config/Database.php';

class Comment {
    public static function create($postId, $userId, $content) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("INSERT INTO comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())"); // Adiciona created_at
            return $stmt->execute([$postId, $userId, $content]);
        } catch (PDOException $e) {
            error_log("Erro ao criar comentário: " . $e->getMessage());
            return false;
        }
    }

    public static function listByPost($postId) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("SELECT c.id, c.post_id, c.user_id, c.content, c.created_at, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at ASC");
            $stmt->execute([$postId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar comentários por post: " . $e->getMessage());
            return [];
        }
    }

    public static function getById($id) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("SELECT c.id, c.post_id, c.user_id, c.content, c.created_at, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.id = ?");
            $stmt->execute([$id]); 
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar comentário: " . $e->getMessage());
            return false;
        }
    }

    public static function update($id, $content) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("UPDATE comments SET content = ? WHERE id = ?");
            return $stmt->execute([$content, $id]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar comentário: " . $e->getMessage());
            return false;
        }
    }

    public static function delete($id) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("DELETE FROM comments WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar comentário: " . $e->getMessage());
            return false;
        }
    }
}
