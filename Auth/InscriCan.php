<?php
require_once 'config.php';
include '../utilisateur.php';

$name = $_POST['name'];
$lastname = $_POST['lastname'];
$mail = $_POST['mail'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$user = new Utilisateur(null, $name, $lastname, $mail, $password, null);

$sql = "INSERT INTO utilisateur (nom, prenom, email, password) VALUES ('$name', '$lastname', '$mail', '$password')";
$conn->query($sql);

// Redirection avec un message de succès
header("Location: confirmation.php?success=1");
?>
