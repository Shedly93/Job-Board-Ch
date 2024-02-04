<?php
require_once 'config.php';
require_once 'entreprise.php';

$nom = $_POST['nom_entreprise']; 
$adresse = $_POST['adresse'];
$email = $_POST['email'];
$localisation = $_POST['localisation'];
$domaine = $_POST['domaine'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$entreprise = new Entreprise(null, $nom, $adresse, $email, $localisation, $domaine, $password);

$sql = "INSERT INTO entreprise (nom_entreprise, adresse, email, localisation, domaine, password) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param('ssssss', $nom, $adresse, $email, $localisation, $domaine, $password); 

    if ($stmt->execute()) {
        header("Location: confirmation.php?success=1");
    } else {
        echo "Erreur d'exécution de la requête : " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Erreur de préparation de la requête : " . $conn->error;
}

$conn->close();
?>
