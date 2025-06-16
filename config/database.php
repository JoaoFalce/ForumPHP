<?php
class Database {
    private static $pdo; // Propriedade estática para armazenar a instância PDO

    public static function connect() {
        // Verifica se a conexão PDO já foi estabelecida
        if (!self::$pdo) {
            try {
                // Cria uma nova instância PDO se ainda não existir
                // DSN (Data Source Name): mysql:host=localhost;port=3307;dbname=forum_trabalho
                // "root" como usuário e senha vazia "" são padrões para ambientes de desenvolvimento local (XAMPP, WAMP)
                self::$pdo = new PDO("mysql:host=localhost;dbname=forum_trabalho", "root", "");
                
                // Configura o PDO para lançar exceções em caso de erros SQL.
                // Isso é crucial para um tratamento de erros robusto e facilita a depuração.
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Em caso de falha na conexão, registra o erro e exibe uma mensagem amigável.
                // No ambiente de produção, você pode não querer exibir $e->getMessage() diretamente.
                error_log("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
                die("Erro de conexão com o banco de dados. Por favor, tente novamente mais tarde.");
            }
        }
        // Retorna a instância PDO existente ou recém-criada
        // Retorna a instância PDO
        return self::$pdo;
    }
}
