<?php
include 'config/config.php';
include 'utilisateur.php';

// Récupérer les données du formulaire
$mail = $_POST['mail'];
$password = $_POST['password'];

// Vérifier les informations de connexion
$sql = "SELECT * FROM utilisateur WHERE email = '$mail'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Vérifier le mot de passe hashé
    if (password_verify($password, $user['password'])) {
        // L'utilisateur est connecté avec succès
        session_start();
        $_SESSION['user_id'] = $user['id_user'];
        header("Location: dashboard.php");
    } else {
        // Mot de passe incorrect
        header("Location: login.php?error=1");
    }
} else {
    // Utilisateur non trouvé
    header("Location: login.php?error=2");
}
?>
