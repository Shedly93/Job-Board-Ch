<?php
ob_start();

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et valider les données du formulaire
    $mail = mysqli_real_escape_string($conn, $_POST['mail']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $userType = mysqli_real_escape_string($conn, $_POST['userType']);

    // Ajoutez des logs pour déboguer
    error_log("Mail: $mail, Password: $password, UserType: $userType");

    // Vérifier le type d'utilisateur et exécuter la requête correspondante
    if ($userType == 'utilisateur') {
        $table = 'utilisateur';
        $redirect_page = 'profile_utilisateur.php';
    } elseif ($userType == 'entreprise') {
        $table = 'entreprise';
        $redirect_page = 'profile_entreprise.php';
    } else {
        // Type d'utilisateur non valide
        echo "Type d'utilisateur non valide";
        ob_end_flush();
        exit();
    }

    // Exécuter la requête préparée
    $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
    $stmt->bind_param("s", $mail);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Vérifier le mot de passe hashé
        if (password_verify($password, $user['password'])) {
            // L'utilisateur est connecté avec succès
            session_start();
            $_SESSION['user_id'] = $user['id_user'];
            header("Location: $redirect_page");
            ob_end_flush();
            exit();
        } else {
            // Mot de passe incorrect
            echo "Mot de passe incorrect";
            ob_end_flush();
            exit();
        }
    } else {
        // Utilisateur non trouvé
        echo "Utilisateur non trouvé";
        ob_end_flush();
        exit();
    }
} else {
    // Redirection en cas d'accès direct à login.php sans POST
    echo "Redirection sans POST";
    ob_end_flush();
    exit();
}
ob_end_flush();
?>
