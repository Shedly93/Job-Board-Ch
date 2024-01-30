<?php
session_start();

require_once 'config.php';
require_once 'Entreprise.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $entreprise_info = Entreprise::getInfoFromDatabase($user_id, $conn);

    if ($entreprise_info) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
            $newNom = mysqli_real_escape_string($conn, $_POST['newNom']);
            $newAdresse = mysqli_real_escape_string($conn, $_POST['newAdresse']);
            $newEmail = mysqli_real_escape_string($conn, $_POST['newEmail']);
            $newLocalisation = mysqli_real_escape_string($conn, $_POST['newLocalisation']);
            $newDomaine = mysqli_real_escape_string($conn, $_POST['newDomaine']);

            $sql = "UPDATE entreprise SET nom_entreprise = '$newNom', adresse = '$newAdresse', email = '$newEmail', localisation = '$newLocalisation', domaine = '$newDomaine' WHERE id_entreprise = $user_id";
            $result = $conn->query($sql);

            if ($result) {
                $entreprise_info = Entreprise::getInfoFromDatabase($user_id, $conn);
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
    <title>Profil Entreprise</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="modal.css">
</head>
<body>
    <!-- <a href="EmploiManagero.php"> -->
<button ><i class="fas fa-user-edit" ></i> emploi</button>
<!-- </a> -->
    <div class="card">
        <div class="img-avatar">
        </div>
        <div class="card-text">
            <div class="portada">
            </div>
            <div class="title-total">
<div class="title"> <?= $entreprise_info->getNom() ?></div>
                <div class="desc">Adresse : <?= $entreprise_info->getAdresse() ?></div>
                <div class="desc">Email : <?= $entreprise_info->getEmail() ?></div>
                <div class="desc">Localisation : <?= $entreprise_info->getLocalisation() ?></div>
                <div class="desc">Domaine : <?= $entreprise_info->getDomaine() ?></div>
                <div class="actions">
                    <button><i class="far fa-heart"></i></button>
                    <button><i class="far fa-envelope"></i></button>
                    <button id="openModalBtn"><i class="fas fa-user-edit"></i> Modifier</button>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModalBtn">&times;</span>
            <form method='post' action=''>
                <label for='newNom'>Nouveau Nom:</label>
                <input type='text' name='newNom' value='<?= $entreprise_info->getNom_entreprise() ?>'><br>

                <label for='newAdresse'>Nouvelle Adresse:</label>
                <input type='text' name='newAdresse' value='<?= $entreprise_info->getAdresse() ?>'><br>

                <label for='newEmail'>Nouvel Email:</label>
                <input type='email' name='newEmail' value='<?= $entreprise_info->getEmail() ?>'><br>

                <label for='newLocalisation'>Nouvelle Localisation:</label>
                <input type='text' name='newLocalisation' value='<?= $entreprise_info->getLocalisation() ?>'><br>

                <label for='newDomaine'>Nouveau Domaine:</label>
                <input type='text' name='newDomaine' value='<?= $entreprise_info->getDomaine() ?>'><br>

                <input type='submit' name='update' value='Modifier'>
            </form>
        </div>
    </div>

    <script>
        var modal = document.getElementById('myModal');
        var openModalBtn = document.getElementById('openModalBtn');
        var closeModalBtn = document.getElementById('closeModalBtn');
console.log()
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
    </script>
</body>
</html>
<?php
    } else {
        echo "Erreur lors de la récupération des informations de l'entreprise.";
    }
} else {
    header("Location: login.html");
    exit();
}
?>
