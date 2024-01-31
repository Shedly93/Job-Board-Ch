<?php
session_start();

require_once 'config.php';
require_once 'Entreprise.php';
require_once 'Emploi.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Obtenir l'id de l'entreprise associée à l'utilisateur
    $entreprise_info = Entreprise::getInfoFromDatabase($user_id, $conn);

    if ($entreprise_info) {
        // Créer une instance de la classe Emploi avec la connexion à la base de données
        $emploi = new Emploi($conn, null, null, null, null, null, null, null);

        // Vérifier si le formulaire est soumis pour ajouter un emploi
        if (isset($_POST['postEmploi'])) {
            $idEntreprise = $entreprise_info->getId();
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $salaire = $_POST['salaire'];
            $contrat = $_POST['contrat'];

            $emploi->postEmploi($idEntreprise, $titre, $description, $salaire, $contrat);
        }

        // Vérifier si le formulaire est soumis pour mettre à jour un emploi
        if (isset($_POST['updateEmploi'])) {
            $idEmploiToUpdate = $_POST['idEmploiToUpdate'];
            $titreToUpdate = $_POST['titreToUpdate'];
            $descriptionToUpdate = $_POST['descriptionToUpdate'];
            $salaireToUpdate = $_POST['salaireToUpdate'];
            $contratToUpdate = $_POST['contratToUpdate'];

            $emploi->updateEmploi($idEmploiToUpdate, $titreToUpdate, $descriptionToUpdate, $salaireToUpdate, $contratToUpdate);
        }

        // Vérifier si le formulaire est soumis pour supprimer un emploi
        if (isset($_POST['deleteEmploi'])) {
            $idEmploiToDelete = $_POST['idEmploiToDelete'];
            $emploi->deleteEmploi($idEmploiToDelete);
        }

        // Récupérer les emplois de l'entreprise
        $emploisEntreprise = $emploi->getEmploisParEntreprise($entreprise_info->getId());

        echo "ID de l'entreprise : " . $entreprise_info->getId();
        // echo "Nom de l'entreprise : " . $entreprise_info->getNomEntreprise();
    } else {
        echo "Erreur : Impossible d'obtenir les informations de l'entreprise.";
    }
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
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
        <!-- Afficher la liste des emplois de l'entreprise -->
        <?php foreach ($emploisEntreprise as $emploi) : ?>
            <div class="job-card">
                <h3><?= $emploi['titre']; ?></h3>
                <p><?= $emploi['description']; ?></p>
                <p>Salaire: <?= $emploi['salaire']; ?></p>
                <p>Contrat: <?= $emploi['contrat']; ?></p>

               <!-- Bouton "Modifier" qui ouvre le modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modifierModal" data-id-emploi="<?= $emploi['id']; ?>" data-titre="<?= $emploi['titre']; ?>" data-description="<?= $emploi['description']; ?>" data-salaire="<?= $emploi['salaire']; ?>" data-contrat="<?= $emploi['contrat']; ?>">
    Modifier
</button>

<!-- Bouton "Supprimer" qui ouvre la confirmation Bootstrap -->
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimerModal" data-id-emploi="<?= $emploi['id']; ?>">
    Supprimer
</button>


                <!-- Ajoutez d'autres informations selon vos besoins -->

                <!-- Modal pour la mise à jour de l'emploi -->
                <div class="modal fade" id="modifierModal<?= $emploi['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modifierModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modifierModalLabel">Modifier l'emploi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Formulaire pour la mise à jour de l'emploi -->
                                <form method="POST" action="">
                                    <input type="hidden" name="idEmploiToUpdate" value="<?= $emploi['id']; ?>">
                                    <input type="text" name="titreToUpdate" placeholder="Nouveau titre" value="<?= $emploi['titre']; ?>" required>
                                    <textarea name="descriptionToUpdate" placeholder="Nouvelle description" required><?= $emploi['description']; ?></textarea>
                                    <input type="text" name="salaireToUpdate" placeholder="Nouveau salaire" value="<?= $emploi['salaire']; ?>" required>
                                    <input type="text" name="contratToUpdate" placeholder="Nouveau contrat" value="<?= $emploi['contrat']; ?>" required>
                                    <button type="submit" name="updateEmploi">Mettre à jour</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal pour la confirmation de suppression -->
                <div class="modal fade" id="supprimerModal<?= $emploi['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="supprimerModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="supprimerModalLabel">Confirmation de suppression</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Voulez-vous vraiment supprimer cet emploi?</p>
                                <!-- Formulaire pour la suppression de l'emploi -->
                                <form method="POST" action="">
                                    <input type="hidden" name="idEmploiToDelete" value="<?= $emploi['id']; ?>">
                                    <button type="submit" name="deleteEmploi">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    </div>


<script>
    // Attend que le document soit prêt
    $(document).ready(function () {
        // Ferme tous les modaux lorsqu'ils sont cachés
        $('.modal').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset(); // Réinitialise le formulaire dans le modal
        });

        // Pré-remplit les champs du formulaire de mise à jour lorsqu'il est affiché
        $('#modifierModal').on('show.bs.modal', function (event) {
                    console.log('Bouton Modifier cliqué !');

            var button = $(event.relatedTarget); // Bouton qui a déclenché l'événement
            var idEmploi = button.data('id-emploi'); // Récupère l'ID de l'emploi à mettre à jour

            // Récupère les données de l'emploi
            var titre = button.data('titre');
            var description = button.data('description');
            var salaire = button.data('salaire');
            var contrat = button.data('contrat');

            // Met à jour les champs du formulaire
            var modal = $(this);
            modal.find('.modal-body input[name="idEmploiToUpdate"]').val(idEmploi);
            modal.find('.modal-body input[name="titreToUpdate"]').val(titre);
            modal.find('.modal-body textarea[name="descriptionToUpdate"]').val(description);
            modal.find('.modal-body input[name="salaireToUpdate"]').val(salaire);
            modal.find('.modal-body input[name="contratToUpdate"]').val(contrat);
        });

        // Pré-remplit le champ caché du formulaire de suppression lorsqu'il est affiché
        $('#supprimerModal').on('show.bs.modal', function (event) {
                    console.log('Bouton Supprimer cliqué !');

            var button = $(event.relatedTarget); // Bouton qui a déclenché l'événement
            var idEmploiToDelete = button.data('id-emploi'); // Récupère l'ID de l'emploi à supprimer

            // Met à jour le champ caché du formulaire
            var modal = $(this);
            modal.find('.modal-body input[name="idEmploiToDelete"]').val(idEmploiToDelete);
        });
    });
</script>



</body>
</html>
