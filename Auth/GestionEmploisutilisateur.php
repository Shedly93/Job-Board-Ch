<?php
session_start();
require_once 'Emploi.php';
require_once 'Application.php';
require_once 'FiltreEmploi.php';



$servername = "localhost";
$username = "root";
$password = ""; 
$database = "job1";

$message = "";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connexion réussie à la base de données.";

    $emploi = new Emploi($pdo, null, null, null, null, null, null, null);
    $filtreEmploi = new FiltreEmploi($pdo);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if (isset($_POST['logout'])) {
    session_destroy();
    echo '<script>';
    echo 'localStorage.removeItem("user_id");'; 
    echo 'localStorage.removeItem("id_entreprise");'; 
    echo '</script>';
    header("Location: login.html");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="gestionEmploiutilisateur.css">
</head>
    
</head>
<body>
<div style="overflow: hidden; width: 100%; position: absolute;">
       <nav>
<img src="http://localhost:8080/Job-Board-Ch/Home/Logo-JobBoard.png" alt="Logo de Job-Board-CH" class="logo" />
            <ul>
                <li><a href="../../Home/index.html">Accueil</a></li>
                <li><a href="../Auth//GestionEmploisutilisateur.php">Offres d'emploi</a></li>
                <li><a href="../..//Home/contact.html">Contact</a></li>
                <li><a href="../Auth//login.html">Login</a></li>
           
            </ul>
            
            <div class="dropdown">
    <button class="dropbtn">Register  
      <i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-content">
      <a href="../Auth/InscriUtilisateur.html">Utilisateur</a>
      <a href="../Auth//inscriEntreprise.html">Entreprise</a>
    </div>
  </div> 


        </nav>
    <div class="container-user">
        <div class="top_barre">
        <h1 class="container-title-user"><b>List Des Emplois</b></h1>
          
        </div>
        <form method="GET" id="searchbox">
    <label for="titre" class="sr-only">Rechercher par titre :</label>
    <input type="text" name="titre" id="titre">
    <input type="submit" id="button-submit">
</form>
       <!-- <form method="POST" id="form-nav-logout">
            <input type="submit" name="logout" value="Logout" class="logout" >
        </form> -->


      
        <div class="gradient-cards">
            <?php
            $titre = isset($_GET['titre']) ? $_GET['titre'] : null;
            $emploisParTitre = $filtreEmploi->rechercherParTitre($titre);


            $emplois = array_merge($emploisParTitre);

            if ($emplois) {
                foreach ($emplois as $emploi) {
                    echo '<div class="card-user">';
                    echo '<div class="container-card-user bg-green-box">';
                    echo "<p class='card-title-user'>{$emploi['titre']}</p>";
                    echo "<p class='card-description-user'>{$emploi['description']} - Salaire: {$emploi['salaire']} - Contrat: {$emploi['contrat']}</p>";

                    echo '<form method="POST">';
                    echo '<input type="hidden" name="id_emploi" value="' . $emploi['id_emploi'] . '">';
                    echo '<input type="hidden" name="id_utilisateur" value="' . $_SESSION['user_id'] . '">';
                    echo '<input type="submit" name="apply" value="Postuler">';
                    echo '</form>';

                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>Aucun emploi disponible.</p>";
            }
            ?>
        </div>
        <?php
        if (isset($_POST['apply'])) {
            $id_emploi = $_POST['id_emploi'];
            $id_utilisateur = $_POST['id_utilisateur'];
            
            $application = new Application($pdo, null, null, null);

            try {
                if ($application->postulerUtilisateur($id_emploi, $id_utilisateur, "Candidature pour le poste")) {
                    $message = "Postulation réussie!";
                } else {
                    $message = "La postulation a échoué. Veuillez réessayer.";
                }
            } catch (PDOException $e) {
                $message = "Erreur MySQL : " . $e->getMessage();
            }
        }
        ?>
        <p><?php echo $message; ?></p>
      
    </div>
     <div class="footer-copyright">
<div class="footer-copyright-wrapper">
  <p class="footer-copyright-text">
    <a class="footer-copyright-link" href="#" target="_self"> ©2024. | CH-JOB-BOARD. | All rights reserved. </a>
  </p>
</div>
</div>
    </div>
   

</div>
</body>
</html>

