<?php
include '../entreprise.php';
require_once 'config.php';

$nom = $_POST['nom'];
$adresse = $_POST['adresse'];
$email = $_POST['email'];
$localisation = $_POST['localisation'];
$domaine = $_POST['domaine'];

$entreprise = new Entreprise(null, $nom, $adresse, $email, $localisation, $domaine);

$sql = "INSERT INTO entreprise (nom_entreprise, adresse, email, localisation, domaine) VALUES ('$nom', '$adresse', '$email', '$localisation', '$domaine')";
$conn->query($sql);

// Redirection avec un message de succès
header("Location: confirmation.php?success=1");
?>
