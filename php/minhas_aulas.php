<?php 
require_once 'config.php';
requireLogin();

// Aulas como aluno
$stmt = $pdo->prepare("
    SELECT a.*, 
           p.nome as professor_nome,
           p.cidade as professor_cidade
    FROM aulas a 
    JOIN usuarios p ON a.professor_id = p.id
    WHERE a.aluno_id = ?
    ORDER BY a.data DESC, a.horario DESC
");
$stmt->execute([$_SESSION['user_id']]);
$aulas_aluno = $stmt->fetchAll();

// Aulas como professor
$stmt = $pdo->prepare("
    SELECT a.*, 
           u.nome as aluno_nome,
           u.cidade as aluno_cidade
    FROM aulas a 
    JOIN usuarios u ON a.aluno_id = u.id
    WHERE a.professor_id = ?
    ORDER BY a.data DESC, a.horario DESC
");
$stmt->execute([$_SESSION['user_id']]);
$aulas_professor = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <h1 style="text-align: center; margin-bottom: 3rem; color: #667eea;">
        <i class="fas fa-calendar-alt"></i> Minhas Aulas
    </h1>

    <?php if (!empty($aulas_aluno)): ?>
    <div class="card">
        <h3><i class="fas fa-graduation-cap"></i> Aulas como Aluno</h3>
        <div style="display: grid; gap: 1rem; margin-top: 1.5rem;">
            <?php foreach($aulas_aluno as $aula): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; background: #f8f9fa; border-radius: 12px; border-left: 4px solid #667eea;">
                    <div>
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">
                            <?= $aula['professor_nome'] ?? 'Professor' ?> • <?= $aula['professor_cidade'] ?? '' ?>
                        </div>
                        <div style="color: #666;">
                            <?= date('d/m/Y', strtotime($aula['data'])) ?> às <?= $aula['horario'] ?>
                            <span style="margin-left: 1rem; background: #e3f2fd; color: #1976d2; padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.85rem;">
                                <?= ucfirst($aula['status']) ?>
                            </span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: bold; color: #28a745;">-<?= $aula['custo_creditos'] ?> cr</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($aulas_professor)): ?>
    <div class="card" style="margin-top: 2rem;">
        <h3><i class="fas fa-chalkboard-teacher"></i> Aulas como Professor</h3>
        <div style="display: grid; gap: 1rem; margin-top: 1.5rem;">
            <?php foreach($aulas_professor as $aula): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; background: #f8f9fa; border-radius: 12px; border-left: 4px solid #28a745;">
                    <div>
                        <div style="font-weight: bold; margin-bottom: 0.5rem;">
                            <?= $aula['aluno_nome'] ?? 'Aluno' ?> • <?= $aula['aluno_cidade'] ?? '' ?>
                        </div>
                        <div style="color: #666;">
                            <?= date('d/m/Y', strtotime($aula['data'])) ?> às <?= $aula['horario'] ?>
                            <span style="margin-left: 1rem; background: #d4edda; color: #155724; padding: 0.2rem 0.6rem; border-radius: 12px; font-size: 0.85rem;">
                                <?= ucfirst($aula['status']) ?>
                            </span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: bold; color: #28a745;">+<?= $aula['custo_creditos'] ?> cr</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (empty($aulas_aluno) && empty($aulas_professor)): ?>
    <div class="card" style="max-width: 500px; margin: 0 auto; text-align: center; padding: 3rem;">
        <i class="fas fa-calendar-times" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
        <h3>Nenhuma aula encontrada</h3>
        <p style="color: #999; margin-bottom: 2rem;">Agende sua primeira aula!</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="explorar.php" class="btn">Explorar professores</a>
            <a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
        </div>
    </div>
    <?php endif; ?>
</div>

</body>
</html>