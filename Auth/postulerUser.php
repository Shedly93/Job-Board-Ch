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
  <link rel="stylesheet" href="/Job-Board-Ch\Auth\sss.css">
</head>
<body>
            <div class="dashboard-container">

                <aside class="main-sidebar">
                <header class="aside-header">
                    <div class="brand">
                        <h3>Coding City</h3>
                    </div>
                </header>

                <div class="sidebar" id="sidebar">
                    <ul class="list-items">
                        <li class="item">
                            <a href="#">
                              
                                <span>emploi</span>
                            </a>
                        </li>
                        <li class="item">
                            <a href="#">
                               
                                <span>Clients</span>
                            </a>
                        </li>
                        <li class="item">
                            <a href="#">
                                <span>Reglages</span>
                            </a>
                        </li>
                        <li class="item">
                            <a href="#">
                                <span>Ajouter un produit</span>
                            </a>
                        </li>
                        <li class="item">
                            <a href="">
                                <span class="material-icons-sharp">
                                    logout
                                </span>
                                <span>Se Deconnecter</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>

  <section class="recent-orders">
    <div class="ro-title">
        <h2 class="recent-orders-title">Commandes récentes</h2>
        <a href="#" class="show-all">Tout afficher</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID de l'emploi</th>
                <th>Titre de l'emploi</th>
                <th>ID de l'utilisateur</th>
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
                            <td><?php echo $applicationData['id_emploi']; ?></td>
                            <td><?php echo $applicationData['titre']; ?></td>
                            <td><?php echo $applicationData['id_utilisateur']; ?></td>
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
        $textePostulation = "bonjour";
        echo "Postulation envoyée avec succès: " . $textePostulation;
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
                // Traitement de l'acceptation ou du refus de la candidature sans envoi d'email
            } else {
                echo "ID de l'utilisateur non trouvé dans la base de données.<br>";
            }
        } else {
            echo "ID de l'utilisateur ou ID de l'emploi non trouvé dans la requête POST.<br>";
        }
    }
    ?>
</section>
            </div>
</body>
</html>
<?php
    }
}
?>
