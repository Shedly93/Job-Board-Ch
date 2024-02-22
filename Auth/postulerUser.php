<?php
    require_once 'config.php';
    require_once 'Application.php';
    require_once 'Utilisateur.php';
    require_once 'Emploi.php';
    require_once 'Entreprise.php';

if (isset($_GET['id_emploi'])) {
    $id_emploi = $_GET['id_emploi'];

    $application = new Application($conn, null, null, null);
    $applicationsData = $application->getApplicationsParEmploi($id_emploi);

    if (empty($applicationsData)) {
        echo "Aucun utilisateur n'a postulé à cette offre d'emploi.";
        echo "<script>alert('Aucun utilisateur n\'a postulé à cette offre d\'emploi.'); window.location.href = 'GestionEmploisEntreprise.php';</script>";
    } else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="\Job-Board-Ch\Auth\sss.css">
    <script>
        function afficherMessagePostulationEchouee() {
            alert("La postulation a échoué. Veuillez réessayer.");
        }
    </script>
    <style>
.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
    padding: 10px;
    margin-bottom: 20px;
}
.alert-failure {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
    padding: 10px;
    margin-bottom: 20px;
}

    </style>
</head>
<body>
    <div class="dashboard-entreprise">
            <div class="content">
                <section class="recent-orders">
                    <table>
                        <thead>
                            <tr>
                                <th>Titre de l'emploi</th>
                                <th>Nom de l'utilisateur</th>
                                <th>Prénom de l'utilisateur</th>
                                <th>Description de l'utilisateur</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($applicationsData as $applicationItem) {
                                $applications = $application->getApplicationsParEmploi($applicationItem['id_emploi']);
                                if ($applications) {
                                    foreach ($applications as $applicationData) {
                                        ?>
                                        <tr>
                                            <td><?php echo $applicationData['titre']; ?></td>
                                            <td><?php echo $applicationData['nom']; ?></td>
                                            <td><?php echo $applicationData['prenom']; ?></td>
                                            <td><?php echo $applicationData['description_utilisateur']; ?></td>
                                            <td>
                                                <form method='post' class='forme-ac-ref'>
                                                    <input type='hidden' name='id_user' value='<?php echo $applicationData['id_utilisateur']; ?>' />
                                                    <input type='hidden' name='id_emploi' value='<?php echo $applicationData['id_emploi']; ?>' />
                                                    <div class='action-buttons'>
                                                        <button class='accept-button' type='submit' name='accepter'>Accepter</button>
                                                        <button class='reject-button' type='submit' name='refuser'>Refuser</button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    if (isset($_POST['postuler'])) {
                        if ($postulationReussie) {
                            echo "<script>alert('Postulation réussie.');</script>";
                        } else {
                            echo "<script>afficherMessagePostulationEchouee();</script>";
                        }
                    }

                    if (isset($_POST['accepter']) || isset($_POST['refuser'])) {
                        if (isset($_POST['id_user'], $_POST['id_emploi'])) {
                            $id_user = $_POST['id_user'];
                            $id_emploi = $_POST['id_emploi'];

                            $status = isset($_POST['accepter']) ? "accepter" : "refuser";

                            $updateQuery = "UPDATE application SET status = ? WHERE id_utilisateur = ? AND id_emploi = ?";
                            $updateStmt = $conn->prepare($updateQuery);
                            $updateStmt->execute([$status, $id_user, $id_emploi]);

                            $utilisateur = Utilisateur::getInfoFromDatabase($id_user, $conn);
                            if ($utilisateur) {
                            } else {
                                echo "ID de l'utilisateur non trouvé dans la base de données.<br>";
                            }
                        } else {
                            echo "ID de l'utilisateur ou ID de l'emploi non trouvé dans la requête POST.<br>";
                        }
                    }
                    ?>
                </section>
            <!-- <div class="footer-copyright">
                <div class="footer-copyright-wrapper">
                    <p>
                        <a href="#" target="_self"> ©2024. | CH-JOB-BOARD. | Tous droits réservés. </a>
                    </p>
                </div>
            </div> -->
        </div>
    </div>
</body>
</html>
<?php
    }
}
?>
