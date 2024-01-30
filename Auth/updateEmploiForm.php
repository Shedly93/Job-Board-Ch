<?php
// Assurez-vous d'avoir inclus la classe Emploi et EmploiManager au préalable

// Récupérer l'ID de l'emploi à mettre à jour depuis l'URL ou d'une autre manière
$idEmploi = $_GET['idEmploi']; // Assurez-vous de valider et d'échapper cette valeur en production

// Récupérer les détails de l'emploi à partir de la base de données
$emploiManager = new EmploiManager($votreConnexionPDO);
$emploi = $emploiManager->getEmploiById($idEmploi);

// Afficher le formulaire de mise à jour
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour de l'emploi</title>
</head>
<body>
    <h2>Formulaire de mise à jour de l'emploi</h2>
    <form action="processUpdateEmploi.php" method="post">
        <input type="hidden" name="idEmploi" value="<?php echo $emploi['id_emploi']; ?>">
        <label for="titre">Titre:</label>
        <input type="text" name="titre" value="<?php echo $emploi['titre']; ?>" required><br>
        <label for="description">Description:</label>
        <textarea name="description" required><?php echo $emploi['description']; ?></textarea><br>
        <label for="salaire">Salaire:</label>
        <input type="text" name="salaire" value="<?php echo $emploi['salaire']; ?>" required><br>
        <label for="contrat">Type de contrat:</label>
        <input type="text" name="contrat" value="<?php echo $emploi['contrat']; ?>" required><br>
        <button type="submit">Mettre à jour l'emploi</button>
    </form>
</body>
</html>
