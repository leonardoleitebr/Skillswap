<?php 
require_once 'config.php';

$busca = $_GET['busca'] ?? '';
$cidade = $_GET['cidade'] ?? '';

$sql = "
    SELECT DISTINCT u.*, AVG(4.5) as media_avaliacao 
    FROM usuarios u 
    LEFT JOIN usuario_habilidades uh ON u.id = uh.usuario_id 
    LEFT JOIN habilidades h ON uh.habilidade_id = h.id 
    WHERE uh.tipo = 'ensina'
";

$params = [];
if ($busca) {
    $sql .= " AND h.nome LIKE ?";
    $params[] = "%$busca%";
}
if ($cidade) {
    $sql .= " AND u.cidade LIKE ?";
    $params[] = "%$cidade%";
}

$sql .= " GROUP BY u.id ORDER BY u.nome";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$professores = $stmt->fetchAll();

// Todas as cidades
$stmt = $pdo->query("SELECT DISTINCT cidade FROM usuarios WHERE cidade IS NOT NULL ORDER BY cidade");
$cidades = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<?php include '../includes/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="card">
        <h2><i class="fas fa-search"></i> Explorar Professores</h2>
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem;">
            <form method="GET" style="flex: 1; min-width: 200px;">
                <div class="form-group">
                    <label>Buscar por habilidade</label>
                    <input type="text" name="busca" value="<?= $busca ?>" placeholder="Ex: Guitarra, Inglês...">
                </div>
            </form>
            <form method="GET" style="flex: 1; min-width: 200px;">
                <div class="form-group">
                    <label>Filtrar por cidade</label>
                    <select name="cidade">
                        <option value="">Todas as cidades</option>
                        <?php foreach($cidades as $c): ?>
                            <option value="<?= $c ?>" <?= $cidade == $c ? 'selected' : '' ?>><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
            <a href="explorar.php" class="btn" style="align-self: end; padding: 15px 30px;">Limpar</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem; margin-top: 2rem;">
        <?php if (empty($professores)): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #999;">
                <i class="fas fa-search" style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <h3>Nenhum professor encontrado</h3>
                <p>Tente buscar por outra habilidade ou cidade</p>
            </div>
        <?php else: ?>
            <?php foreach($professores as $prof): ?>
                <div class="card teacher-card">
                    <div class="teacher-avatar"><?= strtoupper(substr($prof['nome'], 0, 2)) ?></div>
                    <div class="teacher-info" style="flex: 1;">
                        <h3><?= $prof['nome'] ?></h3>
                        <div class="teacher-rating">
                            <i class="fas fa-star"></i> <?= number_format($prof['media_avaliacao'], 1) ?>/5.0
                        </div>
                        <div><?= $prof['cidade'] ?></div>
                        
                        <?php
                        // Pegar habilidades deste professor
                        $stmt = $pdo->prepare("
                            SELECT h.nome FROM usuario_habilidades uh 
                            JOIN habilidades h ON uh.habilidade_id = h.id 
                            WHERE uh.usuario_id = ? AND uh.tipo = 'ensina' 
                            LIMIT 4
                        ");
                        $stmt->execute([$prof['id']]);
                        $habs = $stmt->fetchAll(PDO::FETCH_COLUMN);
                        ?>
                        
                        <div class="skills-tags">
                            <?php foreach($habs as $hab): ?>
                                <span class="skill-tag"><?= $hab ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <a href="perfil.php?id=<?= $prof['id'] ?>" class="btn">Ver perfil</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>