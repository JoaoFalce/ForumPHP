<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="/TrabalhoPHP/app/Views/css/style.css">
</head>
<body>
<div class="container">
    <h2>Recuperação de Senha</h2>
    
    <?php if (isset($_SESSION['recover_error'])): ?>
        <div class="error">
            <?= $_SESSION['recover_error']; ?>
            <?php unset($_SESSION['recover_error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['recover_success'])): ?>
        <div class="success">
            <?= $_SESSION['recover_success']; ?>
            <?php unset($_SESSION['recover_success']); ?>
        </div>
        
        <div class="user-info">
            <p><strong>E-mail:</strong> <?= $_SESSION['recovered_email']; ?></p>
            <p><strong>Status:</strong> <?= $_SESSION['recovered_password']; ?></p>
            <?php 
                unset($_SESSION['recovered_email']); 
                unset($_SESSION['recovered_password']); 
            ?>
        </div>
        
        <div class="button-group">
            <button type="button" onclick="window.location.href='/TrabalhoPHP/login'" class="back-btn">Voltar ao Login</button>
        </div>
    <?php else: ?>
        <form method="POST" action="/TrabalhoPHP/recover">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="text" name="cpf" placeholder="CPF (apenas números)" maxlength="11" required>
            <input type="date" name="birth_date" placeholder="Data de Nascimento" required>
            <button type="submit">Recuperar</button>
        </form>
        
        <div class="button-group">
            <button type="button" onclick="window.location.href='/TrabalhoPHP/login'" class="back-btn">Voltar ao Login</button>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cpfInput = document.querySelector('input[name="cpf"]');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            e.target.value = value;
        });
    }
});
</script>
</body>
</html>