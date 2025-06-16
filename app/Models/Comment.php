<?php


require_once __DIR__ . '/../../config/Database.php';

class Comment {
    public static function create($postId, $userId, $content) {
        try {
            $db = Database::connect(); // Usa o método connect() para PDO
            $stmt = $db->prepare("INSERT INTO comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())"); // Adiciona created_at
            return $stmt->execute([$postId, $userId, $content]); // Executa com array de parâmetros
        } catch (PDOException $e) {
            error_log("Erro ao criar comentário: " . $e->getMessage());
            return false;
        }
    }

    public static function listByPost($postId) {
        try {
            $db = Database::connect(); // Usa o método connect() para PDO
            $stmt = $db->prepare("SELECT c.id, c.post_id, c.user_id, c.content, c.created_at, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at ASC");
            $stmt->execute([$postId]); // Executa com array de parâmetros
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos os resultados como array associativo
        } catch (PDOException $e) {
            error_log("Erro ao listar comentários por post: " . $e->getMessage());
            return [];
        }
    }

    public static function getById($id) {
        try {
            $db = Database::connect(); // Usa o método connect() para PDO
            // Inclui user_id para a verificação de autoria no controlador
            $stmt = $db->prepare("SELECT c.id, c.post_id, c.user_id, c.content, c.created_at, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.id = ?");
            $stmt->execute([$id]); // Executa com array de parâmetros
            return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna um único resultado
        } catch (PDOException $e) {
            error_log("Erro ao buscar comentário: " . $e->getMessage());
            return false;
        }
    }

    public static function update($id, $content) {
        try {
            $db = Database::connect(); // Usa o método connect() para PDO
            $stmt = $db->prepare("UPDATE comments SET content = ? WHERE id = ?");
            return $stmt->execute([$content, $id]); // Executa com array de parâmetros
        } catch (PDOException $e) {
            error_log("Erro ao atualizar comentário: " . $e->getMessage());
            return false;
        }
    }

    public static function delete($id) {
        try {
            $db = Database::connect(); // Usa o método connect() para PDO
            $stmt = $db->prepare("DELETE FROM comments WHERE id = ?");
            return $stmt->execute([$id]); // Executa com array de parâmetros
        } catch (PDOException $e) {
            error_log("Erro ao deletar comentário: " . $e->getMessage());
            return false;
        }
    }
}
