<?php
require_once 'Emploi.php';

$servername = "localhost";
$username = "root";
$password = ""; 
$database = "job1";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données.";

    // Créez une instance de la classe Emploi avec seulement la connexion
    $emploi = new Emploi($pdo, null, null, null, null, null, null, null);

} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if (isset($_POST['logout'])) {
    header("Location: login.html");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Vos balises meta et autres en-têtes ici -->
</head>
<body>
    <!-- Votre contenu HTML ici -->

    <?php
    // Utilisez maintenant $emploi pour appeler les méthodes de la classe Emploi
    $emplois = $emploi->getAllEmplois();

    if ($emplois) {
        foreach ($emplois as $emploi) {
            echo '<div class="job-card">';
            echo "<p>{$emploi['titre']} - {$emploi['description']} - Salaire: {$emploi['salaire']} - Contrat: {$emploi['contrat']}  </p>";
            echo '</div>';
        }
    } else {
        echo "<p>Aucun emploi disponible.</p>";
    }
    ?>

    <form method="POST">
        <button type="submit" name="logout">Logout</button>
    </form>

    <!-- Vos scripts JavaScript ici -->
</body>
</html>
