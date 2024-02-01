<?php
if (isset($_POST['postuler'])) {
    $textePostulation = "bonjour";
    echo "Postulation envoyée avec succès: " . $textePostulation;
}

require_once 'config.php';
require_once 'Application.php';
require_once 'Utilisateur.php';
require_once 'Emploi.php';
require_once 'Entreprise.php';

if (isset($_GET['id_emploi'])) {
    $id_emploi = $_GET['id_emploi'];

    $application = new Application($conn, null, null);
    $applicationsData = $application->getApplicationsParEmploi($id_emploi);

 foreach ($applicationsData as $applicationItem) {
    echo "ID de l'emploi: " . $applicationItem['id_emploi'] . "<br>";

    $emploi = new Emploi($conn, $applicationItem['id_emploi'], null, null, null, null, null, null);
    $infoEmploi = $emploi->getInfoFromDatabase($conn);

    if ($infoEmploi) {
        echo "Nom de l'entreprise: " . $infoEmploi->getNomEntreprise() . "<br>";
    } else {
        echo "Erreur lors de la récupération des informations sur l'emploi.<br>";
    }

    if (isset($applicationItem['id_utilisateur'])) {
        echo "ID de l'utilisateur: " . $applicationItem['id_utilisateur'] . "<br>";

        $utilisateur = Utilisateur::getInfoFromDatabase($applicationItem['id_utilisateur'], $conn);

        if ($utilisateur) {
            echo "Nom de l'utilisateur: " . $utilisateur->getNomUtilisateur() . "<br>";
            echo "Description de l'utilisateur: " . $utilisateur->getprenomUtilisateur() . "<br>";
        } else {
            echo "Erreur lors de la récupération des informations sur l'utilisateur.<br>";
            // Add debugging statements
            echo "SQL Error: " . $conn->error . "<br>";
        }
    } else {
        echo "Aucune information sur l'utilisateur.<br>";
    }

    echo "<br><br>";
}


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Les balises head nécessaires pour votre page PostulerUser.php -->
</head>
<body>
    <!-- Le reste du contenu de votre page PostulerUser.php -->
</body>
</html>
