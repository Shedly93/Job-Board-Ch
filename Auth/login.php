<?php
ob_start();

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = mysqli_real_escape_string($conn, $_POST['mail']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $userType = mysqli_real_escape_string($conn, $_POST['userType']);

error_log("Mail: $mail, Password: $password, UserType: $userType");

    if ($userType == 'utilisateur') {
        $table = 'utilisateur';
        $redirect_page = 'profile_utilisateur.php';
    } elseif ($userType == 'entreprise') {
        $table = 'entreprise';
        $redirect_page = 'profile_entreprise.php';
    } else {
        echo "Type d'utilisateur non valide";
        ob_end_flush();
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
    $stmt->bind_param("s", $mail);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if ($userType == 'utilisateur' && isset($user['id_user'])) {
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id_user'];
            header("Location: $redirect_page");
            ob_end_flush();
            exit();
        } else {
            echo "Mot de passe incorrect";
            ob_end_flush();
            exit();
        }
    } elseif ($userType == 'entreprise' && isset($user['id_entreprise'])) {
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id_entreprise'];
            header("Location: $redirect_page");
            ob_end_flush();
            exit();
        } else {
            echo "Mot de passe incorrect";
            ob_end_flush();
            exit();
        }
    } else {
        echo "Clé 'id_entreprise' ou 'id_user' non définie dans le tableau";
        ob_end_flush();
        exit();
    }
} else {
    echo "Utilisateur non trouvé";
    ob_end_flush();
    exit();
}


} else {
    echo "Redirection sans POST";
    ob_end_flush();
    exit();
}
ob_end_flush();
?>
