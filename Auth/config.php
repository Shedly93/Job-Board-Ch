<?php
$servername = "127.0.0.1";
$username = "root";
$password = ""; 
$database = "job";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
} else {
    echo "Connexion réussie à la base de données.";
}
?>
