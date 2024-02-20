<?php
require_once 'config.php';
require_once 'Utilisateur.php';


if (isset($_POST['name'], $_POST['lastname'], $_POST['mail'], $_POST['password'])) {
    $nom = $_POST['name'];
    $prenom = $_POST['lastname'];
    $email = $_POST['mail'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // La description n'est pas nécessaire ici, car elle n'est pas incluse dans le formulaire
    $utilisateur = new Utilisateur(null, $nom, $prenom, $email, $password, null, $conn);

    if ($utilisateur->insertIntoDatabase()) {
        header("Location: confirmation.php?success=1");
    } else {
        echo "Erreur lors de l'insertion.";
    }
} else {
    echo "Veuillez fournir toutes les informations nécessaires.";
}
?>
