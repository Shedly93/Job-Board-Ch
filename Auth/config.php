<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$database = "job1";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
} else {
    // echo "Connexion réussie à la base de données.";
}
?>