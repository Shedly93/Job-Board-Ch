<?php
require_once 'config.php';
require_once 'utlisateur.php';

$name = $_POST['name'];
$lastname = $_POST['lastname'];
$mail = $_POST['mail'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Créez un objet Utilisateur
$user = new Utilisateur(null, $name, $lastname, $mail, $password, null);

// Utilisez une requête préparée avec bind_param
$sql = "INSERT INTO utilisateur (nom, prenom, email, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param('ssss', $name, $lastname, $mail, $password); // ajustez les types de données selon votre schéma

    // Exécutez la requête
    if ($stmt->execute()) {
        // Redirection avec un message de succès
        header("Location: confirmation.php?success=1");
    } else {
        // Gestion des erreurs d'exécution de la requête
        echo "Erreur d'exécution de la requête : " . $stmt->error;
    }

    // Fermez la requête
    $stmt->close();
} else {
    // Gestion des erreurs de préparation de la requête
    echo "Erreur de préparation de la requête : " . $conn->error;
}

// Fermez la connexion
$conn->close();
?>
