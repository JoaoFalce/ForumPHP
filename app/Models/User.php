<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    public static function register($username, $email, $password, $cpf, $birth_date) {
        try {
            $db = Database::connect();
            
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                return false;
            }
            
            $stmt = $db->prepare("SELECT id FROM users WHERE cpf = ?");
            $stmt->execute([$cpf]);
            if ($stmt->fetch()) {
                return false; 
            }
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $db->prepare("INSERT INTO users (username, email, password, cpf, birth_date, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            return $stmt->execute([$username, $email, $hashed_password, $cpf, $birth_date]);
            
        } catch (PDOException $e) {
            error_log("Erro no registro: " . $e->getMessage());
            return false;
        }
    }

    public static function login($email, $password) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("SELECT id, password FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $user['id'];
                setcookie("user", $user['id'], time() + 3600, "/");
                return true;
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("Erro no login: " . $e->getMessage());
            return false;
        }
    }

    public static function findByCpfAndBirthDate($cpf, $birth_date) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("SELECT email, password FROM users WHERE cpf = ? AND birth_date = ?");
            $stmt->execute([$cpf, $birth_date]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                return [
                    'email' => $user['email'],
                    'password_display' => 'Um link de redefinição de senha foi enviado para o seu Email!'];
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("Erro na recuperação: " . $e->getMessage());
            return false;
        }
    }
    
    public static function findById($id) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("SELECT id, username, email, cpf, birth_date FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário: " . $e->getMessage());
            return false;
        }
    }

    public static function getByEmail($email) {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("SELECT id, username, email, cpf, birth_date FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário por email: " . $e->getMessage());
            return false;
        }
    }
}
?>