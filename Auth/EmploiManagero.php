<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Emplois</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h2>Poster un nouvel emploi</h2>
    <form action="EmploiManager.php" method="post">
        <label for="idEntreprise">ID Entreprise:</label>
        <input type="text" name="idEntreprise" required><br>
        <label for="titre">Titre:</label>
        <input type="text" name="titre" required><br>
        <label for="description">Description:</label>
        <textarea name="description" required></textarea><br>
        <label for="salaire">Salaire:</label>
        <input type="text" name="salaire" required><br>
        <label for="contrat">Type de contrat:</label>
        <input type="text" name="contrat" required><br>
        <button type="submit">Poster l'emploi</button>
    </form>
    
<hr>

<h2>Liste des emplois</h2>
<?php

$emploiManager = new EmploiManager($votreConnexionPDO);
$emplois = $emploiManager->getAllEmplois();

if ($emplois) {
    foreach ($emplois as $emploi) {
        echo "<p>{$emploi['titre']} - {$emploi['description']} - Salaire: {$emploi['salaire']} - Contrat: {$emploi['contrat']} 
              <button onclick=\"openModal({$emploi['id_emploi']})\">Edit</button></p>";
    }
} else {
    echo "<p>Aucun emploi disponible pour le moment.</p>";
}
?>

<!-- Le modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Formulaire de mise à jour de l'emploi</h2>
        <form id="updateForm" action="updateEmploiForm.php" method="post">
            <input type="hidden" id="modalIdEmploi" name="idEmploi" value="">
            <label for="modalTitre">Titre:</label>
            <input type="text" id="modalTitre" name="titre" required><br>
            <label for="modalDescription">Description:</label>
            <textarea id="modalDescription" name="description" required></textarea><br>
            <label for="modalSalaire">Salaire:</label>
            <input type="text" id="modalSalaire" name="salaire" required><br>
            <label for="modalContrat">Type de contrat:</label>
            <input type="text" id="modalContrat" name="contrat" required><br>
            <button type="submit">Mettre à jour l'emploi</button>
        </form>
    </div>
</div>

<script>
    function openModal(idEmploi) {
        document.getElementById('modalIdEmploi').value = idEmploi;

     

        document.getElementById('myModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('myModal').style.display = 'none';
    }
</script>

</body>
</html>
