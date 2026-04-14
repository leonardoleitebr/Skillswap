<?php 
require_once 'config.php';

if ($_POST) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = md5($_POST['senha']);
    $cidade = trim($_POST['cidade']);
    
    // Validações
    $erros = [];
    if (strlen($nome) < 3) $erros[] = "Nome deve ter pelo menos 3 caracteres";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = "Email inválido";
    if (strlen($_POST['senha']) < 6) $erros[] = "Senha deve ter pelo menos 6 caracteres";
    
    // Verificar se email já existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) $erros[] = "Email já cadastrado";
    
    if (empty($erros)) {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, cidade) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nome, $email, $senha, $cidade])) {
            $sucesso = "Conta criada com sucesso! Faça login.";
        } else {
            $erros[] = "Erro ao criar conta";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="card" style="max-width: 500px; margin: 0 auto;">
        <h2 style="text-align: center; margin-bottom: 2rem; color: #ff0000;">
            <i class="fas fa-user-plus"></i> Criar Conta
        </h2>
        
        <?php if (isset($sucesso)): ?>
            <div style="background: #00ff3c; color: #155724; padding: 1rem; border-radius: 10px; margin-bottom: 1rem;">
                <?= $sucesso ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($erros)): ?>
            <div style="background: #ff0015; color: #721c24; padding: 1rem; border-radius: 10px; margin-bottom: 1rem;">
                <?php foreach($erros as $erro): ?>
                    <p><?= $erro ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Nome completo</label>
                <input type="text" name="nome" value="<?= $_POST['nome'] ?? '' ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>" required>
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" required>
            </div>
            <div class="form-group">
                <label>Cidade</label>
                <input type="text" name="cidade" value="<?= $_POST['cidade'] ?? '' ?>" required>
            </div>
            <button type="submit" class="btn" style="width: 100%;">Criar conta</button>
        </form>
        
        <p style="text-align: center; margin-top: 1.5rem;">
            Já tem conta? <a href="login.php">Faça login</a>
        </p>
    </div>
</div>

</body>
</html>