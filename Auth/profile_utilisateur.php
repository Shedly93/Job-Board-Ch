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
    <link rel="stylesheet" href="style.css">
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

.card-text {
    font-size: xx-large;
    margin-bottom: 10px;
}

.card .desc {
    margin-bottom: 5px;
    color: #555;
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
<div class="profile-page">
    <div class="act-emploi">
    <button class="btn-envoie-emploi" onclick="redirectToGestionEmplois()"> Recherche Emploi</button>
       </div>
           <div class="profile-container">
    <div class="card">
        <div class="img-avatar"> </div>
        <div class="card-text">
            <div class="portada"> </div>
            <div class="title-total">   
                <div class="title">Nom : <?= $user_info->getNom() ?> Prenom : <?= $user_info->getPrenom() ?></div>
                <div class="desc">Email :<?= $user_info->getEmail() ?></div>
                <div class="desc">description :<?= $user_info->getDescription() ?></div>
               <div class="actions">
    <button id="openModalBtn" class="btn-modifier"></i> Modifier</button>
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
                <label for='newNom'>Nouveau Nom:</label>
                <input type='text' name='newNom' value='<?= $user_info->getNom() ?>'><br>

                <label for='newPrenom'>Nouveau Prénom:</label>
                <input type='text' name='newPrenom' value='<?= $user_info->getPrenom() ?>'><br>

                <label for='newEmail'>Nouvel Email:</label>
                <input type='email' name='newEmail' value='<?= $user_info->getEmail() ?>'><br>

                <label for='newDescription'>Nouvelle Description:</label>
                <textarea name='newDescription'><?= $user_info->getDescription() ?></textarea><br>

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
