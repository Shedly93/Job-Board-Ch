<?php
session_start();

if (isset($_SESSION['user_id'])) {
    // Utilisateur connecté, récupérez les informations de l'utilisateur à partir de la session
    $user_id = $_SESSION['user_id'];

    // Récupérez les informations supplémentaires de l'entreprise depuis la base de données (à adapter selon votre schéma)
    // $entreprise_info = get_entreprise_info_by_id($user_id);

    // Affichez les informations dans la page
    echo "Bienvenue sur votre profil entreprise!";
    // Affichez les détails de l'entreprise à partir de $entreprise_info

} else {
    // Redirection si l'utilisateur n'est pas connecté
    header("Location: login.html");
    exit();
}
?>
