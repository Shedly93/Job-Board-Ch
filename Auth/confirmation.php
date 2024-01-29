<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
</head>
<body>
    <?php
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo "<p>Inscription réussie! Veuillez vous connecter <a href='login.html'>ici</a>.</p>";
    }
    ?>
</body>
</html>
