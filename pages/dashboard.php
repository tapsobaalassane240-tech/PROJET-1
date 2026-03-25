<?php
require_once '../config/database.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Mise à jour automatique des statuts
$stmt = $pdo->prepare("UPDATE taches SET statut = 'executee' WHERE user_id = ? AND statut = 'en_attente' AND date_execution <= NOW()");
$stmt->execute([$user_id]);

// Statistiques
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM taches WHERE user_id = ?");
$stmt->execute([$user_id]);
$total = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as en_attente FROM taches WHERE user_id = ? AND statut = 'en_attente'");
$stmt->execute([$user_id]);
$en_attente = $stmt->fetch()['en_attente'];

$stmt = $pdo->prepare("SELECT COUNT(*) as executees FROM taches WHERE user_id = ? AND statut = 'executee'");
$stmt->execute([$user_id]);
$executees = $stmt->fetch()['executees'];

// Prochaines tâches
$stmt = $pdo->prepare("SELECT * FROM taches WHERE user_id = ? AND statut = 'en_attente' ORDER BY date_execution ASC LIMIT 5");
$stmt->execute([$user_id]);
$prochaines_taches = $stmt->fetchAll();
?>

<h2>Tableau de bord</h2>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5>Total des tâches</h5>
                <h2><?php echo $total; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5>En attente</h5>
                <h2><?php echo $en_attente; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5>Exécutées</h5>
                <h2><?php echo $executees; ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Prochaines tâches</h5>
    </div>
    <div class="card-body">
        <?php if(count($prochaines_taches) > 0): ?>
            <table class="table">
                <thead>
                    <tr><th>Tâche</th><th>Date</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach($prochaines_taches as $tache): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tache['nom']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($tache['date_execution'])); ?></td>
                        <td><a href="taches/modifier.php?id=<?php echo $tache['id']; ?>" class="btn btn-sm btn-primary">Modifier</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">Aucune tâche programmée</p>
        <?php endif; ?>
    </div>
</div>

<div class="mt-3">
    <a href="taches/ajouter.php" class="btn btn-success">+ Ajouter une tâche</a>
    <a href="taches/index.php" class="btn btn-primary">Voir toutes mes tâches</a>
    <a href="logs.php" class="btn btn-info text-white">
        <i class="fas fa-history"></i> Historique des tâches
    </a>
</div>

<?php require_once '../includes/footer.php'; ?>
