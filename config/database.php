<?php
class Database {
    private static $pdo; 

    public static function connect() {
        
        if (!self::$pdo) {
            try {
               
                self::$pdo = new PDO("mysql:host=localhost;port=3307;dbname=forum_trabalho", "root", "");
                
                
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
             
                error_log("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
                die("Erro de conexão com o banco de dados. Por favor, tente novamente mais tarde.");
            }
        }
       
        return self::$pdo;
    }
}
