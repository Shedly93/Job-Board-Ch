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
                    $mail->Host       = 'smtp.example.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'test@test.com';
                    $mail->Password   = 'test';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;

                    $mail->setFrom('chediouerghi8@gmail.com', 'chedi');
                    $mail->addAddress($user_email);

                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body    = $message;

                    $mail->send();
                    echo "Email sent successfully.";
                } catch (Exception $e) {
                    echo "Error sending email: {$mail->ErrorInfo}";
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
    <style>

        .forme-ac-ref{
    width: 40%;
    height: 5vh;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    position: absolute;
    bottom: 5%;
    right: 0;
}

        body {
  background-color: black;
}

.container--cards {
    width: 100%;
    height: 97vh;
    background: linear-gradient(170deg, #080509, #1a171c, #080509);
    overflow-x: hidden;
    overflow-y: auto;
}

.gradient-cards {
display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    width: 100%;
    height: 100%;
}

.container-title {
  text-align: center;
  padding: 0 !important;
  margin-bottom: 40px;
  font-size: 40px;
  color: #fff;
  font-weight: 600;
  line-height: 60px;
}

.card {
    max-width: 25%;
    border: 0;
    width: 100%;
    height: 55vh;
}

.container-card {
position: relative !important;
    border: 2px solid transparent;
    background: linear-gradient(71deg, #080509, #1a171c, #080509);
    background-clip: padding-box;
    border-radius: 45px;
    padding: 40px;
    height: 75%;
    width: 84%;
    color: white;
    font-size: x-large;
    white-space: pre-line;
}
       .user-info {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .user-description {
            margin-bottom: 10px;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
        }

        .action-buttons button {
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .accept-button {
            background-color: #4CAF50;
            color: white;
            border: none;
        }

        .reject-button {
            background-color: #f44336;
            color: white;
            border: none;
        }

.bg-green-box,
.bg-white-box,
.bg-yellow-box,
.bg-blue-box {
  position: relative;
}

.bg-green-box::after,
.bg-white-box::after,
.bg-yellow-box::after,
.bg-blue-box::after {
  position: absolute;
  top: -1px;
  bottom: -1px;
  left: -1px;
  right: -1px;
  content: "";
  z-index: -1;
  border-radius: 45px;
}

.bg-green-box::after {
  background: linear-gradient(71deg, #0d1212, #3da077, #0d1212);
}

.bg-white-box::after {
  background: linear-gradient(71deg, #121013, #b0afb0, #121013);
}

.bg-yellow-box::after {
  background: linear-gradient(71deg, #110e0e, #afa220, #110e0e);
}

.bg-blue-box::after {
  background: linear-gradient(71deg, #0c0a0e, #5f6fad, #0c0a0e);
}

.card-title {
  font-weight: 600;
  color: white;
  letter-spacing: -0.02em;
  line-height: 40px;
  font-style: normal;
  font-size: 28px;
  padding-bottom: 8px;

}

.card-description {
  font-weight: 600;
  line-height: 32px;
  color: hsla(0, 0%, 100%, 0.5);
  font-size: 16px;
  max-width: 470px;
}

    </style>
</head>
<body>
</body>
</html>
