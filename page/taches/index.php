<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM taches WHERE user_id = ? ORDER BY date_execution ASC");
$stmt->execute([$_SESSION['user_id']]);
$taches = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Mes tâches</h2>
    <a href="../dashboard.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour au tableau de bord
    </a>
</div>

<a href="ajouter.php" class="btn btn-success mb-3">+ Nouvelle tâche</a>

<?php if(count($taches) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Date d'exécution</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
            </thead>
            <tbody>
                <?php foreach($taches as $tache): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($tache['nom']); ?></strong></td>
                    <td><?php echo htmlspecialchars(substr($tache['description'], 0, 50)); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($tache['date_execution'])); ?></td>
                    <td>
                        <?php if($tache['statut'] == 'en_attente'): ?>
                            <span class="badge bg-warning">En attente</span>
                        <?php else: ?>
                            <span class="badge bg-success">Exécutée</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="modifier.php?id=<?php echo $tache['id']; ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="supprimer.php?id=<?php echo $tache['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette tâche ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Aucune tâche pour le moment. 
        <a href="ajouter.php">Créez votre première tâche</a>
    </div>
<?php endif; ?>

<?php require_once '../../includes/footer.php'; ?>
