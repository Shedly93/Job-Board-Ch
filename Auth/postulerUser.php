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
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_GET['id_emploi'])) {
    $id_emploi = $_GET['id_emploi'];

    $application = new Application($conn, null, null, null);
    $applicationsData = $application->getApplicationsParEmploi($id_emploi);

    if (empty($applicationsData)) {
        echo "Aucun utilisateur n'a postulé à cette offre d'emploi.";
        echo "<script>alert('Aucun utilisateur n\'a postulé à cette offre d\'emploi.'); window.location.href = 'GestionEmploisEntreprise.php';</script>";
    } else {
        foreach ($applicationsData as $applicationItem) {
            ?>
            <div class="container--cards">
                <div class="gradient-cards">
                    <div class="card">
                        <div class="container-card bg-white-box">
    <?php
    $applications = $application->getApplicationsParEmploi($applicationItem['id_emploi']);

    if ($applications) {
        foreach ($applications as $applicationData) {
            echo "ID de l'emploi: " . $applicationData['id_emploi'] . "<br>";
            echo "Titre de l'emploi: " . $applicationData['titre'] . "<br>";

            echo "ID de l'utilisateur: " . $applicationData['id_utilisateur'] . "<br>";
            echo "Nom de l'utilisateur: " . $applicationData['nom'] . "<br>";
            echo "Prenom de l'utilisateur: " . $applicationData['prenom'] . "<br>";
            echo "Description de l'utilisateur: " . $applicationData['description_utilisateur'] . "<br>";

            echo "<form method='post' class='forme-ac-ref'>";
            echo "<input type='hidden' name='id_user' value='" . $applicationData['id_utilisateur'] . "' />";
            echo "<input type='hidden' name='id_emploi' value='" . $applicationData['id_emploi'] . "' />";
            echo "<div class='action-buttons'>";
            echo "<button class='accept-button' type='submit' name='accepter'>Accepter</button>";
            echo "<button class='reject-button' type='submit' name='refuser'>Refuser</button>";
            echo "</div>";
            echo "</form>";
            echo "<br><br>";
        }
    } else {
        echo "Aucune application trouvée pour cet emploi.";
    }
    ?>
</div>

                    </div>
                </div>
            </div>
            <?php
        }
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
            $user_email = $utilisateur->getEmail();

            $emploi = new Emploi($conn, $id_emploi); 
            $id_entreprise = $emploi->getIdEntreprise();

            $entreprise = Entreprise::getInfoFromDatabase($id_entreprise, $conn);
            if ($entreprise) {
                $entreprise_email = $entreprise->getEmail();
                $smtp_host = $entreprise->getSmtpHost();
                $smtp_username = $entreprise->getSmtpUsername();
                $smtp_password = $entreprise->getSmtpPassword();
            }

            if (isset($_POST['accepter'])) {
                $subject = "Votre candidature a été acceptée";
                $message = "Merci de postuler! Votre candidature a été acceptée.";
            } elseif (isset($_POST['refuser'])) {
                $subject = "Votre candidature a été refusée";
                $message = "Merci de postuler! Votre candidature a été refusée.";

                $mail = new PHPMailer(true);

                try {
                    $mail->SMTPDebug = 0;
                    $mail->isSMTP();
                    $mail->Host       = $smtp_host;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $smtp_username;
                    $mail->Password   = $smtp_password;
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;

                    $mail->setFrom($entreprise_email, 'Nom de votre entreprise');
                    $mail->addAddress($user_email);

                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body    = $message;

                    $mail->send();
                    echo "Email envoyé avec succès.";
                } catch (Exception $e) {
                    echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
                }
            }
        } else {
            echo "ID de l'utilisateur non trouvé dans la base de données.<br>";
        }
    } else {
        echo "ID de l'utilisateur ou ID de l'emploi non trouvé dans la requête POST.<br>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  
</head>
<body>
</body>
</html>
