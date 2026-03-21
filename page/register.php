<?php
include "../config/database.php";

if(isset($_POST['submit'])){
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if($stmt->rowCount() > 0){
        echo "Email déjà utilisé";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (nom, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $password]);
        echo "Inscription réussie";
    }
}
?>

<h2>Inscription</h2>
<form method="post">
    <input type="text" name="nom" placeholder="Nom" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Mot de passe" required><br><br>
    <button type="submit" name="submit">S'inscrire</button>
</form>

<a href="login.php">Se connecter</a>