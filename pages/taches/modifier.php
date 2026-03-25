<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM taches WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$tache = $stmt->fetch();

if (!$tache) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_POST) {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $date_execution = $_POST['date_execution'];
    
    if (empty($nom)) {
        $error = "Le nom de la tâche est requis";
    } elseif (empty($date_execution)) {
        $error = "La date d'exécution est requise";
    } else {
        $stmt = $pdo->prepare("UPDATE taches SET nom = ?, description = ?, date_execution = ? WHERE id = ? AND user_id = ?");
        if ($stmt->execute([$nom, $description, $date_execution, $id, $_SESSION['user_id']])) {
            // Ajouter un log
            $stmt_log = $pdo->prepare("INSERT INTO logs (tache_id, message) VALUES (?, ?)");
            $stmt_log->execute([$id, "Tâche modifiée le " . date('Y-m-d H:i:s')]);
            $success = "Tâche modifiée avec succès !";
            header("refresh:2;url=index.php");
        } else {
            $error = "Erreur lors de la modification";
        }
    }
}
?>

<h2>Modifier la tâche</h2>

<?php if($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nom de la tâche *</label>
                <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($tache['nom']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($tache['description']); ?></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Date et heure d'exécution *</label>
                <input type="datetime-local" name="date_execution" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($tache['date_execution'])); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
