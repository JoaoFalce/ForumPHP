<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Category.php';

class AuthController {

    public static function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('Token CSRF inválido');
            }

            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (User::login($email, $password)) {
                $_SESSION['user_logged'] = true;
                $_SESSION['user_email'] = $email;

                
                $user = User::getByEmail($email);
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                }

                header("Location: /TrabalhoPHP/forum");
                exit();
            } else {
                $_SESSION['login_error'] = 'E-mail ou senha incorretos';
            }
        }
    }

    public static function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('Token CSRF inválido');
            }

            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $cpf = trim($_POST['cpf'] ?? '');
            $birth_date = $_POST['birth_date'] ?? '';

            if (empty($username) || empty($email) || empty($password) || empty($cpf) || empty($birth_date)) {
                $_SESSION['register_error'] = 'Todos os campos são obrigatórios';
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['register_error'] = 'E-mail inválido';
                return;
            }

            $cpf = preg_replace('/[^0-9]/', '', $cpf);
            if (strlen($cpf) !== 11) {
                $_SESSION['register_error'] = 'CPF deve ter 11 dígitos';
                return;
            }

            try {
                if (User::register($username, $email, $password, $cpf, $birth_date)) {
                    $_SESSION['register_success'] = 'Cadastro realizado com sucesso! Faça seu login.';
                    header("Location: /TrabalhoPHP/login");
                    exit();
                } else {
                    $_SESSION['register_error'] = 'Erro ao cadastrar. E-mail ou CPF já podem estar em uso.';
                }
            } catch (Exception $e) {
                $_SESSION['register_error'] = 'Erro interno. Tente novamente.';
            }
        }
    }

    public static function recover() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('Token CSRF inválido');
            }

            $cpf = trim($_POST['cpf'] ?? '');
            $birth_date = $_POST['birth_date'] ?? '';

            if (empty($cpf) || empty($birth_date)) {
                $_SESSION['recover_error'] = 'CPF e data de nascimento são obrigatórios';
                return;
            }

            $cpf = preg_replace('/[^0-9]/', '', $cpf);
            if (strlen($cpf) !== 11) {
                $_SESSION['recover_error'] = 'CPF deve ter 11 dígitos';
                return;
            }

            try {
                $user = User::findByCpfAndBirthDate($cpf, $birth_date);

                if ($user) {
                    $_SESSION['recover_success'] = 'Usuário encontrado!';
                    $_SESSION['recovered_email'] = $user['email'];
                    $_SESSION['recovered_password'] = $user['password_display'];
                } else {
                    $_SESSION['recover_error'] = 'CPF e/ou data de nascimento não encontrados';
                }
            } catch (Exception $e) {
                $_SESSION['recover_error'] = 'Erro interno. Tente novamente.';
            }
        }
    }

    public static function forum() {
        if (!isset($_SESSION['user_logged']) || $_SESSION['user_logged'] !== true) {
            header("Location: /TrabalhoPHP/login");
            exit();
        }
        $categorias = Category::getAll();

        require __DIR__ . '/../Views/forum.php';
    }

    public static function logout() {
        session_destroy();
        header("Location: /TrabalhoPHP/login");
        exit();
    }
}
