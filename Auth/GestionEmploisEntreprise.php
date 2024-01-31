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
    <style>
        .job-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
            width: 30%;
        }
    </style>
</head>
<body>

<h2>Liste des emplois entreprise</h2>

<form method="POST" action="" >
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

<!-- Afficher la liste des emplois de l'entreprise -->
<?php foreach ($emploisEntreprise as $emploi) : ?>
    <div class="job-card">
        <h3><?= $emploi['titre']; ?></h3>
        <p><?= $emploi['description']; ?></p>
        <p>Salaire: <?= $emploi['salaire']; ?></p>
        <p>Contrat: <?= $emploi['contrat']; ?></p>
        <!-- Ajoutez d'autres informations selon vos besoins -->
    </div>
<?php endforeach; ?>

<script>
    // Votre script JavaScript ici
</script>

</body>
</html>
