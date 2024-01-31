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
    <link rel="stylesheet" href="styleGestionUser.css">
</head>
<body>
    <!-- Votre contenu HTML ici -->

    <div class="container">
      <p class="container-title">List<br>Des Emplois</p>

      <div class="gradient-cards">
        <?php
        $emplois = $emploi->getAllEmplois();

        if ($emplois) {
            foreach ($emplois as $emploi) {
                echo '<div class="card">';
                echo '<div class="container-card bg-green-box">';
                echo "<svg width='80' height='80' viewBox='0 0 120 120' fill='none' xmlns='http://www.w3.org/2000/svg'>";
                echo "</svg>";
                echo "<p class='card-title'>{$emploi['titre']}</p>";
                echo "<p class='card-description'>{$emploi['description']} - Salaire: {$emploi['salaire']} - Contrat: {$emploi['contrat']}</p>";
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>Aucun emploi disponible.</p>";
        }
        ?>
      </div>

      <form method="POST">
          <button type="submit" name="logout">Logout</button>
      </form>

    </div>

    <!-- Vos scripts JavaScript ici -->
</body>
</html>

