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
    <style>
            .container {
        text-align: center;
        margin: 20px;
    }

    .container-title-user {
      font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: center;
    }

    .top_barre{
            display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    height: 10vh;
    background: #bfcabf;
    }
    .gradient-cards {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        justify-content: space-evenly;
        align-items: center;
    }

    .card-user {
    width: 20%;
    margin: 20px;
    box-shadow: 0 4px 8px rgb(0 0 0 / 56%);
    overflow: hidden;
    border-radius: 8px;
    transition: transform 0.2s;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 35vh;
    border: 2px solid lightgray;
}

    .card-user:hover {
        transform: scale(1.05);
    }

    .container-card-user {
        padding: 20px;
    }



    .bg-green-box {
    background-color: #bfcabf;
    color: #fff;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: flex-start;
}

    .card-title-user {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}
    .card-description-user {
        font-size: 16px;
        color: #333;
        margin-bottom: 15px;
        height: 8vh;
        width: 100%;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    form {
    margin-top: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

    input[type="submit"] {
        background-color: #4CAF50;
        color: #fff;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }


#searchbox {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1%;
    height: 10vh;
    width: 100%;
}

label {
    margin-bottom: 8px;
}

input[type="text"] {
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 300px;
}

#button-submit {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    position: relative;
    top: -5%;
}

#button-submit:hover {
    background-color: #45a049;
}

.logout{
    position: relative;
    right: 10%;
}
    </style>
<link rel="stylesheet" href="http://localhost:8080/Job-Board-Ch/Home/styles.css">
<link rel="stylesheet" href="styles.css">
</head>
    
</head>
<body>
<div style="overflow: hidden; width: 100%; position: absolute;">
       <!-- <nav>
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

        </nav> -->
    <div class="container">
        <div class="top_barre">
        <h1 class="container-title-user"><b>List Des Emplois</b></h1>
          <form method="POST">
            <input type="submit" name="logout" value="Logout" class="logout" >
        </form>
        </div>
        <form method="GET" id="searchbox">
    <label for="titre" class="sr-only">Rechercher par titre :</label>
    <input type="text" name="titre" id="titre">
    <input type="submit" id="button-submit">
</form>


      
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
    </div>
    <div class="footer-social-links"3> 
  
</div>
</div>
<div class="footer-copyright">
<div class="footer-copyright-wrapper">
  <p class="footer-copyright-text">
    <a class="footer-copyright-link" href="#" target="_self"> ©2024. | CH-JOB-BOARD. | All rights reserved. </a>
  </p>
</div>
</div>
</footer>
</body>
</html>

