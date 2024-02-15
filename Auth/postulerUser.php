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

    $application = new Application($conn, null, null);
    $applicationsData = $application->getApplicationsParEmploi($id_emploi);

    if (empty($applicationsData)) {
        echo "Aucun utilisateur n'a postulé à cette offre d'emploi.";
        echo "<script>alert('Aucun utilisateur n\'a postulé à cette offre d\'emploi.'); window.location.href = 'GestionEmploisEntreprise.php';</script>";
    } else {
        foreach ($applicationsData as $applicationItem) {
            echo "ID de l'emploi: " . $applicationItem['id_emploi'] . "<br>";

            $emploi = new Emploi($conn, $applicationItem['id_emploi'], null, null, null, null, null, null);
            $infoEmploi = $emploi->getInfoFromDatabase($conn);

            if ($infoEmploi) {
                echo "Nom de l'entreprise: " . $infoEmploi->getNomEntreprise() . "<br>";
            } else {
                echo "Erreur lors de la récupération des informations sur l'emploi.<br>";
            }

            if (isset($applicationItem['id_user'])) {
                echo "ID de l'utilisateur: " . $applicationItem['id_user'] . "<br>";

                $utilisateur = Utilisateur::getInfoFromDatabase($applicationItem['id_user'], $conn);

                if ($utilisateur) {
                    echo "Nom de l'utilisateur: " . $utilisateur->getNom() . "<br>";
                    echo "Description de l'utilisateur: " . $utilisateur->getPrenom() . "<br>";

                    echo "<form method='post'>";
                    echo "<input type='hidden' name='id_user' value='" . $applicationItem['id_user'] . "' />";
                    echo "<button type='submit' name='accepter'>Accepter</button>";
                    echo "<button type='submit' name='refuser'>Refuser</button>";
                    echo "</form>";

                    echo "<br><br>";
                } else {
                    echo "Erreur lors de la récupération des informations sur l'utilisateur.<br>";
                    echo "SQL Error: " . $conn->error . "<br>";
                }
            } else {
                echo "Aucune information sur l'utilisateur. (id_user non défini)<br>";
            }
        }
    }
}

if (isset($_POST['accepter']) || isset($_POST['refuser'])) {
    if (isset($_POST['id_user'])) {
        $id_user = $_POST['id_user'];

        
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
        echo "ID de l'utilisateur non trouvé dans la requête POST.<br>";
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
