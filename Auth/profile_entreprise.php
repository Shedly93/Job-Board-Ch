<?php
session_start();

require_once 'config.php';
require_once 'Entreprise.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['id_entreprise_localstorage'])) {
        $user_id = mysqli_real_escape_string($conn, $_POST['id_entreprise']);
    }

    $entreprise_info = Entreprise::getInfoFromDatabase($user_id, $conn);

    if ($entreprise_info) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
            $entrepriseId = mysqli_real_escape_string($conn, $_POST['entreprise_id']);

            $newNom = mysqli_real_escape_string($conn, $_POST['newNom']);
            $newAdresse = mysqli_real_escape_string($conn, $_POST['newAdresse']);
            $newEmail = mysqli_real_escape_string($conn, $_POST['newEmail']);
            $newLocalisation = mysqli_real_escape_string($conn, $_POST['newLocalisation']);
            $newDomaine = mysqli_real_escape_string($conn, $_POST['newDomaine']);

            $sql = "UPDATE entreprise SET nom_entreprise = '$newNom', adresse = '$newAdresse', email = '$newEmail', localisation = '$newLocalisation', domaine = '$newDomaine' WHERE id_entreprise = $entrepriseId";
            $result = $conn->query($sql);

            if ($result) {
                $entreprise_info = Entreprise::getInfoFromDatabase($user_id, $conn);
                echo "Vos informations ont été mises à jour avec succès.";
            } else {
                echo "Erreur lors de la mise à jour de vos informations.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Entreprise</title>
    <link rel="stylesheet" href="profileEntreprise.css">
        <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="modal.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">

</head>
<body>
    <script>
        var userId = localStorage.getItem('user_id');
        var userType = localStorage.getItem('user_type');

        function redirectToGestionEmploisEntreprise(idEntreprise, userId, userType) {
            localStorage.setItem('id_entreprise', idEntreprise);
            window.location.href = `GestionEmploisEntreprise.php?idEntreprise=${idEntreprise}&userId=${userId}&userType=${userType}`;
        }

        function openModalWithData(entrepriseId, nom, adresse, email, localisation, domaine) {
            document.getElementById('entreprise_id').value = entrepriseId;
            document.getElementById('newNom').value = nom;
            document.getElementById('newAdresse').value = adresse;
            document.getElementById('newEmail').value = email;
            document.getElementById('newLocalisation').value = localisation;
            document.getElementById('newDomaine').value = domaine;

            modal.style.display = 'block';
        }

      
    </script>
<div class="profile-page">
    <div class="act-emploi">
        <button class="btn-envoie-emploi" onclick="redirectToGestionEmploisEntreprise(<?= $user_id ?>, <?= $user_id ?>, 'entreprise')">
            Emploi
        </button>
    </div>
   <div class="profile">
  <div class="profile-bg"></div>
  <section class="container-profile">
    <aside class="profile-image">
      <a class="camera" href="#">
                    <i class="fas fa-camera"></i>
                </a>
    </aside>
    <section class="profile-info">
      <h1 class="first-name"><?= $entreprise_info->getNom() ?></h1>
      <!-- <h1 class="second-name">Yun He</h1> -->
      <h2>ABOUT</h2>
      <p>
        hello hello, I'm angela, artist and developer 🌼 student at stanford; intern at zynga 🌱 happy to be here! 🌿 let's code the best we can!
  <br>
        Email:  <?= $entreprise_info->getEmail() ?>
<br>
Localisation : <?= $entreprise_info->getLocalisation() ?>
<br>
Adresse : <?= $entreprise_info->getAdresse() ?>
<br>
Domaine : <?= $entreprise_info->getDomaine() ?>     
    </p>

      <aside class="social-media-icons">
        <a href="https://twitter.com/zephybite" >
                        <i class="fab fa-twitter"></i>
                    </a>
        <a href="https://codepen.io/zephyo" >
                        <i class="fab fa-codepen"></i>
                    </a>
        <a href="https://github.com/zephyo" >
                        <i class="fab fa-github"></i>
                    </a>
        </a>
        <a href="https://medium.com/@zephyo" >
                        <i class="fab fa-medium"></i>
                    </a>
      </aside>
    </section>
  </section>
  <section class="statistics">
   <button id="openModalBtn" class="btn-modifier" onclick="openModalWithData('<?= $entreprise_info->getId() ?>', '<?= $entreprise_info->getNom() ?>', '<?= $entreprise_info->getAdresse() ?>', '<?= $entreprise_info->getEmail() ?>', '<?= $entreprise_info->getLocalisation() ?>', '<?= $entreprise_info->getDomaine() ?>')">Modifier</button>

  </section>

  <button class="icon close"></button>
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
                <input type='hidden' name='entreprise_id' id='entreprise_id' value=''>
                <label for='newNom'>Nouveau Nom:</label>
                <input type='text' name='newNom' id='newNom'><br>

                <label for='newAdresse'>Nouvelle Adresse:</label>
                <input type='text' name='newAdresse' id='newAdresse'><br>

                <label for='newEmail'>Nouvel Email:</label>
                <input type='email' name='newEmail' id='newEmail'><br>

                <label for='newLocalisation'>Nouvelle Localisation:</label>
                <input type='text' name='newLocalisation' id='newLocalisation'><br>

                <label for='newDomaine'>Nouveau Domaine:</label>
                <input type='text' name='newDomaine' id='newDomaine'><br>

                <input type='submit' name='update' value='Modifier'>
            </form>
        </div>
    </div>

    <script>
    var modal = document.getElementById('myModal');
    var closeModalBtn = document.getElementById('closeModalBtn');

    closeModalBtn.onclick = function () {
        modal.style.display = 'none';
    }

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>

</body>
</html>

<?php
if (!$entreprise_info) {
    // echo "Erreur lors de la récupération des informations de l'entreprise.";
}
?>
