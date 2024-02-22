<?php
session_start();

require_once 'config.php';
require_once 'Entreprise.php';
require_once 'Emploi.php';
require_once 'Application.php'; 

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $entreprise_info = Entreprise::getInfoFromDatabase($user_id, $conn);

    if ($entreprise_info) {
        $emploi = new Emploi($conn, null, null, null, null, null, null, null);

        if (isset($_POST['postEmploi'])) {
            $idEntreprise = $entreprise_info->getId();
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $salaire = $_POST['salaire'];
            $contrat = $_POST['contrat'];

            $emploi->postEmploi($idEntreprise, $titre, $description, $salaire, $contrat);
        }

        if (isset($_POST['updateEmploi'])) {
            $idEmploiToUpdate = $_POST['idEmploi'];
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $salaire = $_POST['salaire'];
            $contrat = $_POST['contrat'];

            $emploi->updateEmploi($idEmploiToUpdate, $titre, $description, $salaire, $contrat);
        }

if (isset($_POST['deleteEmploi'])) {
    $idEmploiToDelete = $_POST['id_emploi'];
    $emploi->deleteEmploi($idEmploiToDelete);
}

$emploisEntreprise = $emploi->getEmploisParEntreprise($entreprise_info->getId());



//    echo "ID de l'entreprise : " . $entreprise_info->getId();
} else {
    echo "Erreur : Impossible d'obtenir les informations de l'entreprise.";
}
$applicationsData = isset($applicationsData) ? $applicationsData : [];

} else {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Emplois</title>
    <link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="\Job-Board-Ch\Auth\gestionEmploiEntreprise.css">  

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- <link rel="stylesheet" href="/Job-Board-Ch/Home/styles.css"> -->
</head>
<body>
    <div
     
     >

    <div class="container-gestionUser">
    <div class="form-submit">    
    <form method="POST" action="" class="emploi-form">
            <input type="hidden" name="idEntreprise" value="<?= $entreprise_info->getId(); ?>" class="entreprise-id">
            <label for="titre" class="form-label">Titre:</label>
            <input type="text" name="titre" required class="form-input">
            <label for="description" class="form-label">Description:</label>
            <textarea name="description" required class="form-textarea"></textarea>
            <label for="salaire" class="form-label">Salaire:</label>
            <input type="text" name="salaire" required class="form-input">
            <label for="contrat" class="form-label">Contrat:</label>
            <input type="text" name="contrat" required class="form-input">
            <input type="hidden" name="idEmploi" value="<?= isset($_POST['idEmploi']) ? $_POST['idEmploi'] : ''; ?>" class="emploi-id">
            <button type="submit" name="postEmploi" class="form-button">Poster l'emploi</button>
        </form>
    </div>
        <div class="list-emploi">
            <?php foreach ($emploisEntreprise as $emploi) : ?>
                <div class="job-card">
                    <h3 class="emploi-title"><?= $emploi['titre']; ?></h3>
                    <p class="entreprise-name">Nom de l'entreprise: <?= $emploi['nom_entreprise']; ?></p>
                    <p class="emploi-description"><?= $emploi['description']; ?></p>
                    <p class="emploi-salaire">Salaire: <?= $emploi['salaire']; ?></p>
                    <p class="emploi-contrat">Contrat: <?= $emploi['contrat']; ?></p>
                    <p class="emploi-date-post">Date post: <?= $emploi['date_post']; ?></p>

                    <div class="pos-bouton">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#emploiModal<?= $emploi['id_emploi']; ?>" onclick="logIdEmploi(<?= $emploi['id_emploi']; ?>)">
                        Modifier l'emploi
                    </button>
<button type="button" class="btn btn-danger" onclick="submitDeleteForm(<?= $emploi['id_emploi']; ?>)">
    Supprimer l'emploi
</button>
<a href="../PostulerUser.php?id_emploi=<?= $emploi['id_emploi']; ?>" class="btn btn-success" onclick="logIdEmploi(<?= $emploi['id_emploi']; ?>">
    Candidature d'emploi
</a>

                    </div>

                    <div class="modal" tabindex="-1" role="dialog" id="emploiModal<?= $emploi['id_emploi']; ?>">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier l'emploi</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="" class="emploi-update-form">
                                        <input type="hidden" name="idEmploi" value="<?= $emploi['id_emploi']; ?>" class="emploi-id">
                                        <label for="titre" class="form-label">Titre:</label>
                                        <input type="text" name="titre" value="<?= $emploi['titre']; ?>" required class="form-input">
                                        <label for="description" class="form-label">Description:</label>
                                        <textarea name="description" required class="form-textarea"><?= $emploi['description']; ?></textarea>
                                        <label for="salaire" class="form-label">Salaire:</label>
                                        <input type="text" name="salaire" value="<?= $emploi['salaire']; ?>" required class="form-input">
                                        <label for="contrat" class="form-label">Contrat:</label>
                                        <input type="text" name="contrat" value="<?= $emploi['contrat']; ?>" required class="form-input">
                                        <button type="submit" name="updateEmploi" class="form-button">Modifier</button>
                                    </form>
                                </div>
                                <form method="POST" action="" name="deleteForm<?= $emploi['id_emploi']; ?>" class="emploi-delete-form">
                                    <input type="hidden" name="idEmploiToDelete" value="" class="emploi-id-to-delete">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
    document.getElementById('logout').addEventListener('click', function() {
    localStorage.removeItem('user_id');
    localStorage.removeItem('id_entreprise');
    localStorage.removeItem('user_type');
    
    window.location.href = 'login.html';
});
    function logIdEmploi(idEmploi) {
        console.log('ID Emploi cliqué:', idEmploi);

        localStorage.setItem('id_emploi', idEmploi);
    }
function confirmDelete(idEmploi) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cet emploi?")) {
        submitDeleteForm(idEmploi);
    }
}

function submitDeleteForm(idEmploi) {
    // Ask for confirmation
    var confirmDelete = window.confirm("Êtes-vous sûr de vouloir supprimer cet emploi?");

    // If confirmed, proceed with deletion
    if (confirmDelete) {
        try {
            // Set the value in the correct form
            document.querySelector('.emploi-id-to-delete').value = idEmploi;

            // Fetch API to submit the form asynchronously
            fetch(document.querySelector('.emploi-delete-form').action, {
                method: 'POST',
                body: new FormData(document.querySelector('.emploi-delete-form')),
            })
            .then(response => {
                if (response.ok) {
                    // Success message
                    alert('Suppression réussie');
                } else {
                    // Error message
                    alert('Erreur lors de la suppression: ' + response.statusText);
                }
            })
            .catch(error => {
                // Catch any network or other errors
                alert('Erreur lors de la suppression: ' + error.message);
            });
        } catch (error) {
            // Display an alert with the error message
            alert('Erreur lors de la suppression: ' + error.message);
        }
    } else {
        // User canceled the delete action
        alert('Suppression annulée');
    }
}




</script>
  

</body>
</html>
