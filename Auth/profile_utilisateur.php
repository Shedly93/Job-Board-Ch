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
<div class="profile-page">
    <div class="act-emploi">
    <button class="btn-envoie-emploi" onclick="redirectToGestionEmplois()"> Recherche Emploi</button>
       </div>
           <div class="profile-container">
<div class="card">
    <div class="card-text">
        <div class="left-side">
            <div class="title">
                <div>
                <label for="nom">Nom :</label> 
                <span id="nom"><?= $user_info->getNom() ?></span> <!-- Contenu affiché -->
                </div>
                <br/>
                <div>
                <label for="prenom">Prénom :</label>
                <span id="prenom"><?= $user_info->getPrenom() ?></span> <!-- Contenu affiché -->
                </div>
            </div>
        </div>
        <div class="right-side"> <!-- Correction de la classe right-side -->
            <div class="desc">
                <label for="email">Email :</label> <br/>
                <span id="email"><?= $user_info->getEmail() ?></span> <!-- Contenu affiché -->
            </div>
            <div class="desc">
                <label for="description">Description :</label> <br/>
                <span id="description"><?= $user_info->getDescription() ?></span> <!-- Contenu affiché -->
            </div>
            <div class="actions">
                <button id="openModalBtn" class="btn-modifier">Modifier</button>
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
