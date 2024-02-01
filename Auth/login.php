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
        header("HTTP/1.1 400 Bad Request");
        exit("Type d'utilisateur non valide");
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

                echo "<script>";
                echo "function storeInLocalStorage(userId, userType) {";
                echo "    localStorage.setItem('user_id', userId);";
                echo "    localStorage.setItem('user_type', userType);";
                echo "}";
                echo "storeInLocalStorage('" . $user['id_user'] . "', 'utilisateur');";
                echo "window.location.href = '$redirect_page';";
                echo "</script>";

                ob_end_flush();
                exit();
            } else {
                header("HTTP/1.1 401 Unauthorized");
                exit("Mot de passe incorrect");
            }
        } elseif ($userType == 'entreprise' && isset($user['id_entreprise'])) {
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id_entreprise'];

                echo "<script>";
                echo "localStorage.setItem('user_id', '" . $user['id_entreprise'] . "');";
                echo "localStorage.setItem('user_type', 'entreprise');";
                echo "window.location.href = '$redirect_page';";
                echo "</script>";

                ob_end_flush();
                exit();
            } else {
                header("HTTP/1.1 401 Unauthorized");
                exit("Mot de passe incorrect");
            }
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            exit("Clé 'id_entreprise' ou 'id_user' non définie dans le tableau");
        }
    } else {
        header("HTTP/1.1 404 Not Found");
        exit("Utilisateur non trouvé");
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    exit("Redirection sans POST");
}
ob_end_flush();
?>