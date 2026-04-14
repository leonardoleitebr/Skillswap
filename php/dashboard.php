<?php 
require_once 'config.php';
requireLogin();

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Habilidades que ensina
$stmt = $pdo->prepare("
    SELECT h.nome 
    FROM usuario_habilidades uh 
    JOIN habilidades h ON uh.habilidade_id = h.id 
    WHERE uh.usuario_id = ? AND uh.tipo = 'ensina'
");
$stmt->execute([$_SESSION['user_id']]);
$habilidades_ensina = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Habilidades que aprende
$stmt = $pdo->prepare("
    SELECT h.nome 
    FROM usuario_habilidades uh 
    JOIN habilidades h ON uh.habilidade_id = h.id 
    WHERE uh.usuario_id = ? AND uh.tipo = 'aprende'
");
$stmt->execute([$_SESSION['user_id']]);
$habilidades_aprende = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Próximas aulas
$stmt = $pdo->prepare("
    SELECT a.*, u.nome as professor_nome 
    FROM aulas a 
    LEFT JOIN usuarios u ON a.professor_id = u.id 
    WHERE a.aluno_id = ? AND a.status IN ('pendente', 'confirmada')
    ORDER BY a.data, a.horario LIMIT 5
");
$stmt->execute([$_SESSION['user_id']]);
$proximas_aulas = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="dashboard-header">
        <div class="user-info">
            <div class="user-avatar"><?= strtoupper(substr($user['nome'], 0, 2)) ?></div>
            <div>
                <h1>Olá, <?= $user['nome'] ?>!</h1>
                <p>Seja bem-vindo de volta à SkillSwap</p>
            </div>
        </div>
        <div style="display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap; margin-top: 2rem;">
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: #ffd700;"><?= $user['creditos'] ?></div>
                Créditos disponíveis
            </div>
            <div style="text-align: center;">
                <div style="font-size: 2rem; font-weight: bold; color: #00d4aa;">3</div>
                Aulas na semana
            </div>
        </div>
    </div>

    <div class="card-grid">
        <div class="card">
            <h3><i class="fas fa-chalkboard-teacher"></i> Habilidades que ensino</h3>
            <div class="skills-tags" style="margin-top: 1rem;">
                <?php foreach($habilidades_ensina as $hab): ?>
                    <span class="skill-tag"><?= $hab ?></span>
                <?php endforeach; ?>
                <?php if (empty($habilidades_ensina)): ?>
                    <span style="color: #999;">Nenhuma habilidade cadastrada</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <h3><i class="fas fa-graduation-cap"></i> Quero aprender</h3>
            <div class="skills-tags" style="margin-top: 1rem;">
                <?php foreach($habilidades_aprende as $hab): ?>
                    <span class="skill-tag"><?= $hab ?></span>
                <?php endforeach; ?>
                <?php if (empty($habilidades_aprende)): ?>
                    <span style="color: #999;">Nenhuma habilidade cadastrada</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card">
        <h3><i class="fas fa-calendar-check"></i> Próximas aulas</h3>
        <?php if (empty($proximas_aulas)): ?>
            <p style="color: #999; text-align: center; padding: 2rem;">Nenhuma aula agendada</p>
        <?php else: ?>
            <div style="display: grid; gap: 1rem;">
                <?php foreach($proximas_aulas as $aula): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                        <div>
                            <strong><?= $aula['professor_nome'] ?></strong><br>
                            <?= date('d/m/Y', strtotime($aula['data'])) ?> às <?= $aula['horario'] ?>
                        </div>
                        <span style="background: #e3f2fd; color: #1976d2; padding: 0.5rem 1rem; border-radius: 20px;">
                            <?= ucfirst($aula['status']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>