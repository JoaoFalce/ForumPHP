<?php

require_once __DIR__ . '/../../config/Database.php';

class Post {
    public static function create($userId, $title, $content, $categoryId) {
        try {
            $db = Database::connect();
            // Inicia uma transação para garantir que ambas as inserções (posts e post_categories) funcionem ou falhem juntas
            $db->beginTransaction();

            $stmt = $db->prepare("INSERT INTO posts (user_id, title, content, created_at) VALUES (?, ?, ?, NOW())");
            $success = $stmt->execute([$userId, $title, $content]);
            
            if (!$success) {
                $db->rollBack(); // Desfaz se a inserção do post falhar
                return false;
            }

            $postId = $db->lastInsertId();

            // Insere na tabela post_categories para associar o post à categoria
            $stmtCategory = $db->prepare("INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)");
            $successCategory = $stmtCategory->execute([$postId, $categoryId]);

            if (!$successCategory) {
                $db->rollBack();
                return false;
            }

            $db->commit(); // Confirma ambas as transações
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao criar post: " . $e->getMessage());
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }

    // Renomeado de getAll para corresponder ao controlador
    public static function getAllPostsWithUsernamesAndCategories() {
        try {
            $db = Database::connect();
            $stmt = $db->query("
                SELECT p.id, p.title, p.content, p.created_at, u.username, c.name as category_name, c.id as category_id
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                JOIN post_categories pc ON p.id = pc.post_id
                JOIN categories c ON pc.category_id = c.id
                ORDER BY p.created_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar posts: " . $e->getMessage());
            return [];
        }
    }

    public static function getById($id) {
        try {
            $db = Database::connect();
            // Adicionado p.user_id e pc.category_id na seleção e join para post_categories
            $stmt = $db->prepare("
                SELECT p.id, p.user_id, p.title, p.content, p.created_at, u.username, pc.category_id 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                LEFT JOIN post_categories pc ON p.id = pc.post_id 
                WHERE p.id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar post: " . $e->getMessage());
            return false;
        }
    }

    public static function update($id, $title, $content) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
            return $stmt->execute([$title, $content, $id]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar post: " . $e->getMessage());
            return false;
        }
    }

    public static function delete($id) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("DELETE FROM posts WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar post: " . $e->getMessage());
            return false;
        }
    }

    public static function getByCategoryId($categoryId) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("
                SELECT p.id, p.title, p.content, p.created_at, p.user_id, u.username 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                JOIN post_categories pc ON p.id = pc.post_id
                WHERE pc.category_id = ? 
                ORDER BY p.created_at DESC
            ");
            $stmt->execute([$categoryId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar posts por categoria: " . $e->getMessage());
            return [];
        }
    }
}
?>
