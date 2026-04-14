<?php 
require_once 'config.php';

if ($_POST) {
    $email = trim($_POST['email']);
    $senha = md5($_POST['senha']);
    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ?");
    $stmt->execute([$email, $senha]);
    
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $user['id'];

        $_SESSION['user_name'] = $user['nome'];
        header('Location: dashboard.php');
        exit();
    } else {
        $erro = "Email ou senha incorretos";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="card" style="max-width: 400px; margin: 0 auto;">
        <h2 style="text-align: center; margin-bottom: 2rem; color: #667eea;">
            <i class="fas fa-sign-in-alt"></i> Login
        </h2>
        
        <?php if (isset($erro)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 10px; margin-bottom: 1rem;">
                <?= $erro ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>" required>
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" required>
            </div>
            <button type="submit" class="btn" style="width: 100%;">Entrar</button>
        </form>
        
        <p style="text-align: center; margin-top: 1.5rem;">
            Não tem conta? <a href="cadastrar.php">Crie uma agora</a>
        </p>
    </div>
</div>

</body>
</html>