<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="/TrabalhoPHP/app/Views/css/style.css">
</head>
<body>
<div class="container">
    <h2>Cadastre-se!</h2>
    
    <?php if (isset($_SESSION['register_error'])): ?>
        <div class="error">
            <?= $_SESSION['register_error']; ?>
            <?php unset($_SESSION['register_error']); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="/TrabalhoPHP/register">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
        <input type="text" name="username" placeholder="Usuário" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Senha" minlength="6" required>
        <input type="text" name="cpf" placeholder="CPF (apenas números)" maxlength="11" required>
        <input type="date" name="birth_date" placeholder="Data de Nascimento" required>
        <button type="submit">Registrar</button>
    </form>
    
    <div class="button-group">
        <button type="button" onclick="window.location.href='/TrabalhoPHP/login'" class="back-btn">Voltar ao Login</button>
    </div>
</div>


 <!--O QUE É ESSE SCRIPT? -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cpfInput = document.querySelector('input[name="cpf"]');
    const form = document.querySelector('form');
    
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            e.target.value = value;
        });
    }
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const cpf = cpfInput.value.replace(/\D/g, '');
            const password = document.querySelector('input[name="password"]').value;
            
            if (cpf.length !== 11) {
                alert('CPF deve ter exatamente 11 dígitos');
                e.preventDefault();
                return;
            }
            
            if (password.length < 6) {
                alert('Senha deve ter pelo menos 6 caracteres');
                e.preventDefault();
                return;
            }
        });
    }
});
</script>
</body>
</html>