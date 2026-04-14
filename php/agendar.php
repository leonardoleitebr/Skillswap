<?php 
require_once 'config.php';
requireLogin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$professor_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = ?");
$stmt->execute([$professor_id]);
$professor = $stmt->fetch();

if (!$professor) {
    header('Location: dashboard.php');
    exit();
}

if ($_POST) {
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $custo = 10; // 10 créditos por aula
    
    // Verificar créditos do aluno
    $stmt = $pdo->prepare("SELECT creditos FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $aluno = $stmt->fetch();
    
    if ($aluno['creditos'] >= $custo) {
        // Deduzir créditos
        $stmt = $pdo->prepare("UPDATE usuarios SET creditos = creditos - ? WHERE id = ?");
        $stmt->execute([$custo, $_SESSION['user_id']]);
        
        // Criar aula
        $stmt = $pdo->prepare("
            INSERT INTO aulas (professor_id, aluno_id, data, horario, custo_creditos) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$professor_id, $_SESSION['user_id'], $data, $horario, $custo]);
        
        $sucesso = "Aula agendada com sucesso! Custo: $custo créditos.";
    } else {
        $erro = "Créditos insuficientes. Você tem " . $aluno['creditos'] . " créditos.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="card" style="max-width: 500px; margin: 0 auto;">
        <h2 style="text-align: center; margin-bottom: 2rem;">
            <i class="fas fa-calendar-plus"></i> Agendar aula com <?= $professor['nome'] ?>
        </h2>
        
        <?php if (isset($sucesso)): ?>
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 10px; margin-bottom: 1rem; text-align: center;">
                <?= $sucesso ?>
                <br><a href="dashboard.php" class="btn" style="margin-top: 1rem;">Ver Dashboard</a>
            </div>
        <?php endif; ?>
        
        <?php if (isset($erro)): ?>
            <div style="background: #f8d7da; color:
                        <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 10px; margin-bottom: 1rem;">
                <?= $erro ?>
            </div>
        <?php endif; ?>

        <?php if (!isset($sucesso)): ?>
        <form method="POST">
            <div class="form-group">
                <label>Data da aula</label>
                <input type="date" name="data" required>
            </div>
            <div class="form-group">
                <label>Horário</label>
                <select name="horario" required>
                    <option value="">Selecione o horário</option>
                    <option value="09:00">09:00</option>
                    <option value="10:00">10:00</option>
                    <option value="14:00">14:00</option>
                    <option value="15:00">15:00</option>
                    <option value="16:00">16:00</option>
                    <option value="17:00">17:00</option>
                </select>
            </div>
            <div style="background: #e3f2fd; padding: 1rem; border-radius: 10px; text-align: center; margin-bottom: 1.5rem;">
                <strong>💰 Custo: 10 créditos por aula (1 hora)</strong>
            </div>
            <button type="submit" class="btn" style="width: 100%;">
                <i class="fas fa-check"></i> Confirmar agendamento
            </button>
        </form>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="perfil.php?id=<?= $professor_id ?>" class="btn btn-secondary">← Voltar ao perfil</a>
        </div>
    </div>
</div>

</body>
</html>