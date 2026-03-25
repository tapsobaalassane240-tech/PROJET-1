<?php
require_once '../config/database.php';

$error = '';

if ($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center">Connexion</h3>
                    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="POST">
                        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                        <input type="password" name="password" class="form-control mb-2" placeholder="Mot de passe" required>
                        <button class="btn btn-primary w-100">Se connecter</button>
                    </form>
                    <p class="text-center mt-2"><a href="register.php">Créer un compte</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
