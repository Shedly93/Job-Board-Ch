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
<link rel="stylesheet" href="\Job-Board-Ch\Auth\gestionEmploiUtilisateur.css">  
<link rel="stylesheet" href="\Job-Board-Ch\Home\styles.css">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>
    
<body>
<div class="body_List_Des_Emplois">
       <nav>
<img src="http://localhost:8080/Job-Board-Ch/Home/Logo-JobBoard.png" alt="Logo de Job-Board-CH" class="logo" />
            <ul>
                <li><a href="../../Home/index.html">Accueil</a></li>
                <li><a href="../Auth//GestionEmploisutilisateur.php">Offres d'emploi</a></li>
                <li><a href="../..//Home/contact.html">Contact</a></li>
                <li><a href="../Auth//login.html">Login</a></li>
<li><a href="#" id="logout">Logout</a></li>
            </ul>
            
          

        </nav>
        <div style="overflow-x:hidden; overflow-y:auto;    height: 81vh;">
    <div class="container_List_Des_Emplois">
   
        <form method="GET" id="searchbox">
    <input type="text" name="titre" id="titre" placeholder="Rechercher par titre " style="margin-left:2%;">
    <input type="submit" id="button-submit">
</form>

<div id="message" class="alert" role="alert" style="color: #000000 !important; background-color: #d4d4ed !important; border-color: #c3e6cb !important; width: max-content; position: absolute; right: 1%; top: 2%;"></div>

      
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
      
    </div>
        </div>
<div class="footer-copyright">
<div class="footer-copyright-wrapper">
  <p class="footer-copyright-text">
    <a class="footer-copyright-link" href="#" target="_self"> ©2024. | CH-JOB-BOARD. | All rights reserved. </a>
  </p>
</div>
</div>
</div>
  

<script>
document.getElementById('logout').addEventListener('click', function() {
    localStorage.removeItem('user_id');
    localStorage.removeItem('id_entreprise');
    localStorage.removeItem('user_type');
    
    window.location.href = 'login.html';
});

var message = '<?php echo $message; ?>';
var messageDiv = document.getElementById('message');

if (message !== '') {
    messageDiv.textContent = message;
    messageDiv.classList.add('alert-' + (message.startsWith('Erreur') ? 'danger' : 'success'));
    messageDiv.classList.add('show');
}
</script>



</body>
</html>

