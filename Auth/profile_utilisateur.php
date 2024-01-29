<?php
session_start();

require_once 'config.php';
require_once 'utlisateur.php'; // Assurez-vous que le chemin du fichier est correct

if (isset($_SESSION['user_id'])) {
    // Utilisateur connecté, récupérez les informations de l'utilisateur à partir de la session
    $user_id = $_SESSION['user_id'];

    // Récupérez les informations supplémentaires de l'utilisateur depuis la base de données
    $user_info = Utilisateur::getInfoFromDatabase($user_id, $conn);

    if ($user_info) {
        // Traitement du formulaire de suppression s'il a été soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
            // Supprimer l'utilisateur de la base de données
            $sql = "DELETE FROM utilisateur WHERE id_user = $user_id";
            $result = $conn->query($sql);

            if ($result) {
                // Déconnexion de l'utilisateur après suppression
                session_unset();
                session_destroy();
                header("Location: login.html");
                exit();
            } else {
                echo "Erreur lors de la suppression de votre compte.";
            }
        }

        // Affichez les informations dans la page
        echo "Bienvenue sur votre profil utilisateur!<br>";
        echo "Nom: " . $user_info->getNom() . "<br>";
        echo "Prénom: " . $user_info->getPrenom() . "<br>";
        echo "Email: " . $user_info->getEmail() . "<br>";
        echo "Description: " . $user_info->getDescription() . "<br>";

        // Bouton de suppression avec confirmation
        echo "<form method='post' action='' onsubmit='return confirm(\"Voulez-vous vraiment supprimer votre compte ?\");'>
                <input type='submit' name='delete' value='Supprimer le compte'>
            </form>";

        // Bouton de modification et formulaire
        echo "<form method='post' action=''>
                <label for='newNom'>Nouveau Nom:</label>
                <input type='text' name='newNom' value='{$user_info->getNom()}'><br>

                <label for='newPrenom'>Nouveau Prénom:</label>
                <input type='text' name='newPrenom' value='{$user_info->getPrenom()}'><br>

                <label for='newEmail'>Nouvel Email:</label>
                <input type='email' name='newEmail' value='{$user_info->getEmail()}'><br>

                <label for='newDescription'>Nouvelle Description:</label>
                <textarea name='newDescription'>{$user_info->getDescription()}</textarea><br>

                <input type='submit' name='update' value='Modifier'>
            </form>";
    } else {
        echo "Erreur lors de la récupération des informations de l'utilisateur.";
    }
} else {
    // Redirection si l'utilisateur n'est pas connecté
    header("Location: login.html");
    exit();
}
?>