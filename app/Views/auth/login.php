<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/TrabalhoPHP/app/Views/css/style.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    
    <?php if (isset($_SESSION['login_error'])): ?>
        <div class="error">
            <?= $_SESSION['login_error']; ?>
            <?php unset($_SESSION['login_error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['register_success'])): ?>
        <div class="success">
            <?= $_SESSION['register_success']; ?>
            <?php unset($_SESSION['register_success']); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="/TrabalhoPHP/login">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
    
    <div class="button-group">
        <button type="button" onclick="window.location.href='/TrabalhoPHP/recover'" class="recover-btn">Recuperar senha</button>
        <button type="button" onclick="window.location.href='/TrabalhoPHP/register'" class="register-btn">Cadastre-se</button>
    </div>
</div>
</body>
</html>