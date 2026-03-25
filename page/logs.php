<?php
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT logs.*, taches.nom as tache_nom 
                      FROM logs 
                      INNER JOIN taches ON logs.tache_id = taches.id 
                      WHERE taches.user_id = ? 
                      ORDER BY logs.date_log DESC 
                      LIMIT 50");
$stmt->execute([$_SESSION['user_id']]);
$logs = $stmt->fetchAll();
?>

<h2>Historique des logs</h2>

<?php if(count($logs) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Tâche</th>
                    <th>Message</th>
                 </tr>
            </thead>
            <tbody>
                <?php foreach($logs as $log): ?>
                <tr>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($log['date_log'])); ?></td>
                    <td><strong><?php echo htmlspecialchars($log['tache_nom']); ?></strong></td>
                    <td><?php echo htmlspecialchars($log['message']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Aucun log pour le moment.
    </div>
<?php endif; ?>

<div class="mt-3">
    <a href="dashboard.php" class="btn btn-secondary">Retour au tableau de bord</a>
</div>

<?php require_once '../includes/footer.php'; ?>
