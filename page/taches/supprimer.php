<?php
require_once '../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$stmt_log = $pdo->prepare("INSERT INTO logs (tache_id, message) SELECT ?, ? FROM taches WHERE id = ? AND user_id = ?");
$stmt_log->execute([$id, "Tâche supprimée le " . date('Y-m-d H:i:s'), $id, $_SESSION['user_id']]);


$stmt = $pdo->prepare("DELETE FROM taches WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);

header("Location: index.php");
exit;
?>
