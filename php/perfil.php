<?php 
require_once 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: explorar.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    header('Location: explorar.php');
    exit();
}

// Habilidades
$stmt = $pdo->prepare("
    SELECT h.nome, uh.tipo 
    FROM usuario_habilidades uh 
    JOIN habilidades h ON uh.habilidade_id = h.id 
    WHERE uh.usuario_id = ?
");
$stmt->execute([$id]);
$habilidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div class="teacher-avatar" style="width: 120px; height: 120px; font-size: 2rem; margin: 0 auto 1rem;">
                <?= strtoupper(substr($usuario['nome'], 0, 2)) ?>
            </div>
            <h1><?= $usuario['nome'] ?></h1>
            <div style="color: #f39c12; font-size: 1.2rem; margin-bottom: 0.5rem;">
                <i class="fas fa-star"></i> 4.9 (127 avaliações)
            </div>
            <div style="color: #666; font-size: 1.1rem;"><?= $usuario['cidade'] ?></div>
        </div>

        <div style="background: #f8f9fa; padding: 2rem; border-radius: 15px; margin-bottom: 2rem;">
            <h3><i class="fas fa-user"></i> Sobre mim</h3>
            <p style="color: #555; line-height: 1.7;">
                Professor apaixonado por compartilhar conhecimento. 
                Com mais de 5 anos de experiência, ajudo alunos a dominarem suas habilidades de forma prática e divertida.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <div>
                <h3><i class="fas fa-chalkboard-teacher"></i> Ensino</h3>
                <div class="skills-tags">
                    <?php foreach($habilidades as $hab): 
                        if ($hab['tipo'] == 'ensina'): ?>
                        <span class="skill-tag"><?= $hab['nome'] ?></span>
                    <?php endif; endforeach; ?>
                </div>
            </div>
            <div>
                <h3><i class="fas fa-graduation-cap"></i> Quero aprender</h3>
                <div class="skills-tags">
                    <?php foreach($habilidades as $hab): 
                        if ($hab['tipo'] == 'aprende'): ?>
                        <span class="skill-tag"><?= $hab['nome'] ?></span>
                    <?php endif; endforeach; ?>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="agendar.php?id=<?= $id ?>" class="btn" style="flex: 1; max-width: 200px;">
                <i class="fas fa-calendar-plus"></i> Agendar aula
            </a>
            <button class="btn btn-secondary" style="flex: 1; max-width: 200px;">
                <i class="fas fa-envelope"></i> Enviar mensagem
            </button>
        </div>
    </div>
</div>

</body>
</html>