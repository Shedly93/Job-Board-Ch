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
    <link rel="stylesheet" href="styles.css">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <link rel="stylesheet" href="modal.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}
.profile-container{
    width: 70%;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80vh;
    background: #6f6f6f0f;
    border-radius: 15px;
}

.profile-page{
    width: 100%;
    background: #f3f2f2;
    height: 97vh;
    display: flex;
    flex-direction: column;
    gap: 2px;
    justify-content: center;
    align-items: center;
} 

.btn-redirect {
    cursor: pointer;
    background-color: #28a745;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 4px;
}

#entreprise-card {
    max-width: 100%;
    width: 80%;
    margin: 20px auto;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    padding: 20px;
    height: 50vh;
    display: flex;
    align-items: center;
    justify-content: flex-start;
}

.card {
    width: 85%;
    /* margin: 20px auto; */
   background: azure;
    /* box-shadow: 0px 0px 10px gray; */
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    height: 85%;
}

.card-text {
    font-size: 18px;
    display: flex;
    justify-content: space-between;
    padding: 20px;
    height: 90%;
    width: 95%;
    background:transparent;
    
}

.left-side, .right-side {
    flex: 1;
}

.title, .desc {
    margin-bottom: 15px;
}

.title label, .desc label {
    font-weight: bold;
    color: #333;
}

.btn-modifier {
    cursor: pointer;
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
}

.btn-modifier:hover {
    background-color: #0056b3;
}

.right-side {
    padding-left: 20px;
    display: flex;
    flex-direction: column;
    height: 100%;
    justify-content: space-between;
}
.card .title {
    font-size: 26px;
    margin-bottom: 10px;
    display: flex;
    flex-direction: column;
    height: 70%;
    gap: 5%;
    padding: 15px;
}

.card .desc {
    margin-bottom: 5px;
    color: #555;
    font-size: 27px;
    padding: 10px;
}

.right-side .desc span {
    color: #555;
}

.right-side .actions {
    text-align: right;
}

.right-side .actions .btn-modifier {
    margin-top: 10px;
}



.btn-modifier {
    cursor: pointer;
    background-color: #007bff;
    color: #fff;
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
}

.modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.modal-content label {
    display: block;
    margin-bottom: 5px;
}

.modal-content input {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.modal-content input[type="submit"] {
    cursor: pointer;
    background-color: #28a745;
    color: #fff;
}

.close {
    cursor: pointer;
}

.close:hover {
    color: #007bff;
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.page-title {
    color: #333;
    text-align: center;
}
.act-emploi{
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 10vh;
}
.btn-envoie-emploi{
    background-color: #007bff;
    color: #fff;
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

    </style>
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
    <div class="profile-container">
    <div class="card" id="entreprise-card">
        <div class="card-text">
                        <div class="left-side">
            <div class="title-total">
                <div class="title" id="entreprise-nom"><?= $entreprise_info->getNom() ?></div>
                        </div>
                        </div>
                           <div class="right-side">
                <div class="desc">Adresse : <span id="entreprise-adresse"><?= $entreprise_info->getAdresse() ?></span></div>
                <div class="desc">Email : <span id="entreprise-email"><?= $entreprise_info->getEmail() ?></span></div>
                <div class="desc">Localisation : <span id="entreprise-localisation"><?= $entreprise_info->getLocalisation() ?></span></div>
                <div class="desc">Domaine : <span id="entreprise-domaine"><?= $entreprise_info->getDomaine() ?></span></div>
                <button id="openModalBtn" class="btn-modifier" onclick="openModalWithData('<?= $entreprise_info->getId() ?>', '<?= $entreprise_info->getNom() ?>', '<?= $entreprise_info->getAdresse() ?>', '<?= $entreprise_info->getEmail() ?>', '<?= $entreprise_info->getLocalisation() ?>', '<?= $entreprise_info->getDomaine() ?>')">Modifier</button>
                <!-- <button class="btn-supprimer" onclick="deleteEntreprise(<?= $entreprise_info->getId() ?>)">Supprimer</button> -->
                           </div>
            </div>
        </div>
    </div>
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

<?php
if (!$entreprise_info) {
    // echo "Erreur lors de la récupération des informations de l'entreprise.";
}
?>
