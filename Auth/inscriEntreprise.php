<?php
require_once 'config.php';
require_once 'entreprise.php';

$nom = $_POST['nom'];
$adresse = $_POST['adresse'];
$email = $_POST['email'];
$localisation = $_POST['localisation'];
$domaine = $_POST['domaine'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Créez un objet Entreprise
$entreprise = new Entreprise(null, $nom, $adresse, $email, $localisation, $domaine, $password);

// Utilisez une requête préparée avec bind_param
$sql = "INSERT INTO entreprise (nom_entreprise, adresse, email, localisation, domaine, password) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param('ssssss', $nom, $adresse, $email, $localisation, $domaine, $password); 

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
