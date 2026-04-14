<?php
// Funções utilitárias para a aplicação

function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}

function formatarHorario($horario) {
    return date('H:i', strtotime($horario));
}

function getStatusBadge($status) {
    $badges = [
        'pendente' => ['bg-warning', 'text-dark', 'Aguardando'],
        'confirmada' => ['bg-info', 'text-white', 'Confirmada'],
        'concluida' => ['bg-success', 'text-white', 'Concluída'],
        'cancelada' => ['bg-danger', 'text-white', 'Cancelada']
    ];
    
    $badge = $badges[$status] ?? $badges['pendente'];
    return '<span class="badge bg-' . $badge[0] . '">' . $badge[2] . '</span>';
}

function getHabilidadesUsuario($pdo, $user_id) {
    $stmt = $pdo->prepare("
        SELECT h.nome, uh.tipo 
        FROM usuario_habilidades uh 
        JOIN habilidades h ON uh.habilidade_id = h.id 
        WHERE uh.usuario_id = ?
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>