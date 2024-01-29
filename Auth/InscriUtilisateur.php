<?php
require_once 'config.php';
require_once 'Utilisateur.php';

$name = $_POST['name'];
$lastname = $_POST['lastname'];
$mail = $_POST['mail'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);


$user = new Utilisateur(null, $name, $lastname, $mail, $password, null);


$sql = "INSERT INTO utilisateur (nom, prenom, email, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param('ssss', $name, $lastname, $mail, $password); 

    
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
