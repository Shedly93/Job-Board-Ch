<?php
session_start();

require_once 'config.php';
require_once 'Utilisateur.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_info = Utilisateur::getInfoFromDatabase($user_id, $conn);
 if ($user_info) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
            $newNom = mysqli_real_escape_string($conn, $_POST['newNom']);
            $newPrenom = mysqli_real_escape_string($conn, $_POST['newPrenom']);
            $newEmail = mysqli_real_escape_string($conn, $_POST['newEmail']);
            $newDescription = mysqli_real_escape_string($conn, $_POST['newDescription']);

            $sql = "UPDATE utilisateur SET nom = '$newNom', prenom = '$newPrenom', email = '$newEmail', description = '$newDescription' WHERE id_user = $user_id";
            $result = $conn->query($sql);

            if ($result) {
                $user_info = Utilisateur::getInfoFromDatabase($user_id, $conn);
                echo "Vos informations ont été mises à jour avec succès.";
            } else {
                echo "Erreur lors de la mise à jour de vos informations.";
            }

        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="profileutilisateur.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="modal.css">
  
</head>
<body>
<div class="profile-page-user">
    <!-- <div class="act-emploi-utilisateur">
    </div> -->
   <div class="profile">
  <div class="profile-bg"></div>
  <section class="container-profile">
    <aside class="profile-image">
    </aside>
        <section class="profile-info">
            <h1 class="profile-name"><?= $user_info->getPrenom() . " " . $user_info->getNom() ?></h1>
            <p class="profile-bio">description : <?= $user_info->getDescription() ?></p>
            <div class="profile-details">
                <div class="profile-detail">
                <label for="email">Email :</label>
                <span id="email"><?= $user_info->getEmail() ?></span>
                </div>
            </div>
        </section>
    </section>
      <section class="statistics">
  <div class="actions">
                <button id="openModalBtn" class="btn-modifier">Modifier</button>
                    <button class="btn-envoie-emploi" onclick="redirectToGestionEmplois()"> Recherche Emploi</button>

            </div>
      </section>

   </div>
</div>
 <div class="footer-copyright">
<div class="footer-copyright-wrapper">
  <p class="footer-copyright-text">
    <a class="footer-copyright-link" href="#" target="_self"> ©2024. | CH-JOB-BOARD. | All rights reserved. </a>
  </p>
</div>
</div>


    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModalBtn">&times;</span>
            <form method='post' action=''>
                <label for='newNom'>Nouveau Nom:</label>
                <input type='text' name='newNom' value='<?= $user_info->getNom() ?>'><br>

                <label for='newPrenom'>Nouveau Prénom:</label>
                <input type='text' name='newPrenom' value='<?= $user_info->getPrenom() ?>'><br>

                <label for='newEmail'>Nouvel Email:</label>
                <input type='email' name='newEmail' value='<?= $user_info->getEmail() ?>'><br>

                <label for='newDescription'>Nouvelle Description:</label>
                <textarea name='newDescription' ><?= $user_info->getDescription() ?></textarea><br>

                <input type='submit' name='update' value='Modifier'>
            </form>
        </div>
    </div>

    <script>
        var modal = document.getElementById('myModal');
        var openModalBtn = document.getElementById('openModalBtn');
        var closeModalBtn = document.getElementById('closeModalBtn');
        
 var userId = localStorage.getItem('user_id');
        var userType = localStorage.getItem('user_type');

        console.log("User ID:", userId);
        console.log("User Type:", userType);

        openModalBtn.onclick = function() {
            modal.style.display = 'block';
        }

        closeModalBtn.onclick = function() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
        function redirectToGestionEmplois() {
            window.location.href = 'GestionEmploisutilisateur.php';
        }
    </script>
</body>

</html>
<?php
    } else {
        echo "Erreur lors de la récupération des informations de l'utilisateur.";
    }
} else {
    header("Location: login.html");
    exit();
}
?>
