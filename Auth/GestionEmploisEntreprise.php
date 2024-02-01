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
            $idEmploiToDelete = $_POST['idEmploiToDelete'];
            $emploi->deleteEmploi($idEmploiToDelete);
        }

if (isset($_GET['viewUserPostModal'])) {
    $idEmploiForModal = $_GET['viewUserPostModal'];
    $application = new Application($conn, null, null);
    $applicationsData = $application->getApplicationsParEmploi($idEmploiForModal);

    header('Content-Type: application/json'); // Indique que le contenu est en JSON
echo trim(json_encode($applicationsData));

    exit();
}

$emploisEntreprise = $emploi->getEmploisParEntreprise($entreprise_info->getId());


   echo "ID de l'entreprise : " . $entreprise_info->getId();
} else {
    echo "Erreur : Impossible d'obtenir les informations de l'entreprise.";
}

// Set $applicationsData to an empty array if not defined
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
    <link rel="stylesheet" href="Entreprise.css">
        
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h2>Liste des emplois entreprise</h2>
    
    <div class="container-gestionUser">
        <form method="POST" action="">
            <input type="hidden" name="idEntreprise" value="<?= $entreprise_info->getId(); ?>">
            <label for="titre">Titre:</label>
            <input type="text" name="titre" required>
            <label for="description">Description:</label>
            <textarea name="description" required></textarea>
            <label for="salaire">Salaire:</label>
            <input type="text" name="salaire" required>
            <label for="contrat">Contrat:</label>
            <input type="text" name="contrat" required>
            <input type="hidden" name="idEmploi" value="<?= isset($_POST['idEmploi']) ? $_POST['idEmploi'] : ''; ?>">
            <button type="submit" name="postEmploi">Poster l'emploi</button>
        </form>

        <div class="list-emploi">
            <?php foreach ($emploisEntreprise as $emploi) : ?>
                <div class="job-card">
                    <h3><?= $emploi['titre']; ?></h3>
                                <p>Nom de l'entreprise: <?= $emploi['nom_entreprise']; ?></p>
                    <p><?= $emploi['description']; ?></p>
                    <p>Salaire: <?= $emploi['salaire']; ?></p>
                    <p>Contrat: <?= $emploi['contrat']; ?></p>
                    <p>date post: <?= $emploi['date_post']; ?></p>

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#emploiModal<?= $emploi['id_emploi']; ?>" onclick="logIdEmploi(<?= $emploi['id_emploi']; ?>)">
                        Modifier l'emploi
                    </button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete(<?= $emploi['id_emploi']; ?>)">
                        Supprimer l'emploi
                    </button>
  <button type="button" class="btn btn-success view-post-btn" data-toggle="modal" data-target="#applicationModal<?= $emploi['id_emploi']; ?>" onclick="viewUserPost(<?= $emploi['id_emploi']; ?>)">
            View User Post
        </button>

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
                                    <form method="POST" action="">
                                        <input type="hidden" name="idEmploi" value="<?= $emploi['id_emploi']; ?>">
                                        <label for="titre">Titre:</label>
                                        <input type="text" name="titre" value="<?= $emploi['titre']; ?>" required>
                                        <label for="description">Description:</label>
                                        <textarea name="description" required><?= $emploi['description']; ?></textarea>
                                        <label for="salaire">Salaire:</label>
                                        <input type="text" name="salaire" value="<?= $emploi['salaire']; ?>" required>
                                        <label for="contrat">Contrat:</label>
                                        <input type="text" name="contrat" value="<?= $emploi['contrat']; ?>" required>
                                        <button type="submit" name="updateEmploi">Modifier</button>
                                    </form>
                                </div>
                                <form method="POST" action="" name="deleteForm<?= $emploi['id_emploi']; ?>">
                                    <input type="hidden" name="idEmploiToDelete" value="">
                                </form>
                            </div>
                        </div>
                    </div>

                    
                      <div class="modal" tabindex="-1" role="dialog" id="applicationModal<?= $emploi['id_emploi']; ?>">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Application Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modalContent">
                        <!-- Contenu du modal ici -->
                    </div>
                </div>
            </div>
        </div>

                </div>
            <?php endforeach; ?>
        </div>



      
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        function logIdEmploi(idEmploi) {
            console.log('ID Emploi cliqué:', idEmploi);
        }

        function confirmDelete(idEmploi) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cet emploi?")) {
                document.querySelector('input[name="idEmploiToDelete"]').value = idEmploi;
                document.querySelector('form[name="deleteForm' + idEmploi + '"]').submit();
            }
        }
function viewUserPost(idEmploi) {
    var btn = document.querySelector('.view-post-btn[data-target="#emploiModal' + idEmploi + '"]');
    var modalId = 'genericModal' + idEmploi;

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            console.log(xhr.responseText); // Affiche la réponse dans la console
            if (xhr.status === 200) {
                // Utiliser une expression régulière pour extraire le JSON de la réponse
                var jsonMatch = xhr.responseText.match(/\[.*\]/);
                if (jsonMatch) {
                    var jsonData = JSON.parse(jsonMatch[0]);
                    
                    // Affichage des détails dans la console (à des fins de vérification)
                    console.log(jsonData);

                    // Ajoutez le code pour afficher les détails dans votre modal
                    var modalContent = document.getElementById('modalContent');
                    modalContent.innerHTML = ''; // Efface le contenu précédent

                    // Parcours des données JSON pour créer les balises HTML
                    for (var key in jsonData[0]) {
                        if (jsonData[0].hasOwnProperty(key)) {
                            var value = jsonData[0][key];
                            var paragraph = document.createElement('p');
                            paragraph.innerHTML = '<strong>' + key + ':</strong> ' + value;
                            modalContent.appendChild(paragraph);
                        }
                    }

                    $('#' + modalId).modal('show');
                } else {
                    console.error('Aucun JSON trouvé dans la réponse.');
                }
            } else {
                console.error('Erreur lors de la récupération des détails de l\'application:', xhr.status);
            }
        }
    };

    xhr.open('GET', 'GestionEmploisEntreprise.php?viewUserPostModal=' + idEmploi, true);
    xhr.send();
}


</script>
</body>
</html>
