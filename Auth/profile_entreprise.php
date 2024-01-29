<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    echo "Bienvenue sur votre profil entreprise!";

} else {
    header("Location: login.html");
    exit();
}
?>
