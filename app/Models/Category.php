<?php
require_once __DIR__ . '/../../config/database.php';

class Category {
    
    /*
    public static function create($name) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("INSERT INTO categories (name) VALUES (?)");
            return $stmt->execute([$name]);
        } catch (PDOException $e) {
            error_log("Erro ao criar categoria: " . $e->getMessage());
            return false;
        }
    }

    */
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
      /*
    public static function update($id, $name) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("UPDATE categories SET name = ? WHERE id = ?");
            return $stmt->execute([$name, $id]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar categoria: " . $e->getMessage());
            return false;
        }
    }
    */

         /*
    public static function delete($id) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar categoria: " . $e->getMessage());
            return false;
        }
    }
        */
}
?>
