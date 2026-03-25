<?php
require_once '../../config/database.php';
require_once '../../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
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
    } elseif (strtotime($date_execution) < time()) {
        $error = "La date doit être dans le futur";
    } else {
        $stmt = $pdo->prepare("INSERT INTO taches (user_id, nom, description, date_execution) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$_SESSION['user_id'], $nom, $description, $date_execution])) {
            $tache_id = $pdo->lastInsertId();
            $stmt_log = $pdo->prepare("INSERT INTO logs (tache_id, message) VALUES (?, ?)");
            $stmt_log->execute([$tache_id, "Tâche créée le " . date('Y-m-d H:i:s')]);
            $success = "Tâche ajoutée avec succès !";
            header("refresh:2;url=index.php");
        } else {
            $error = "Erreur lors de l'ajout";
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Ajouter une tâche</h2>
    <a href="index.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>
</div>

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
                <input type="text" name="nom" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Date et heure d'exécution *</label>
                <input type="datetime-local" name="date_execution" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
