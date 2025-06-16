<?php
require_once __DIR__ . '/../../config/database.php';

class Category {

    public static function getAll() {
        try {
            $db = Database::connect();
            $stmt = $db->query("SELECT id, name FROM categories ORDER BY name ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar categorias: " . $e->getMessage());
            return [];
        }
    }

    public static function getById($id) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("SELECT id, name FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar categoria: " . $e->getMessage());
            return false;
        }
    }
}
?>
