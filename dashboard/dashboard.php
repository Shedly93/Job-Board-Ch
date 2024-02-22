<?php
// Suppression d'un contact s'il y a une demande de suppression
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    require_once '../Home/vendor/autoload.php';
    require_once '../Auth/config.php';
    require_once '../Home/Contact.php';

    $id = $_POST["id"];

    // Supprimer le contact par son ID
    if (Contact::deleteById($conn, $id)) {
        echo "<script>alert('Contact supprimé avec succès');</script>";
    } else {
        echo "<script>alert('Erreur lors de la suppression du contact');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Material icon -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" href="index.css?v=1.0">
    <title>Dashboard</title>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="main-sidebar">
            <div class="sidebar" id="sidebar">
                <ul class="list-items">
                    <li class="item">
                        <a href="#">
                            <span class="material-icons-sharp">people</span>
                            <span>emploi</span>
                        </a>
                    </li>
                    <li class="item">
                        <a href="#">
                            <span class="material-icons-sharp">people</span>
                            <span>Clients</span>
                        </a>
                    </li>
                    <li class="item">
                        <a href="#">
                            <span class="material-icons-sharp">settings</span>
                            <span>Reglages</span>
                        </a>
                    </li>
                    <li class="item">
                        <a href="#">
                            <span class="material-icons-sharp">add</span>
                            <span>Ajouter un produit</span>
                        </a>
                    </li>
                    <li class="item">
                        <a href="#">
                            <span class="material-icons-sharp">logout</span>
                            <span>Se Deconnecter</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main -->
        <main class="main-container">
            <h1 class="main-title">Dashboard</h1>
            <!-- RECENT ORDERS -->
            <section class="recent-orders">
                <div class="ro-title">
                    <h2 class="recent-orders-title">Commandes recentes</h2>
                    <a href="#" class="show-all">Tout afficher</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Action</th> <!-- Ajout de la colonne Action -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once '../Home/vendor/autoload.php';
                        require_once '../Auth/config.php';
                        require_once '../Home/Contact.php';

                        // Récupération des données depuis la base de données
                        $contacts = Contact::getAllFromDatabase($conn);

                        // Affichage des données dans le tableau
                        foreach ($contacts as $contact) {
                            echo "<tr>";
                            echo "<td>{$contact->getName()}</td>";
                            echo "<td>{$contact->getEmail()}</td>";
                            echo "<td>{$contact->getMessage()}</td>";
                            // Ajout d'un formulaire avec un bouton de suppression et confirmation pour chaque ligne
                            echo "<td>";
                            echo "<form id=\"form-{$contact->getId()}\" method=\"post\" action=\"\">";
                            echo "<input type=\"hidden\" name=\"id\" value=\"{$contact->getId()}\">";
                            echo "<button type=\"button\" onclick=\"confirmDelete({$contact->getId()})\">Supprimer</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script>
        function confirmDelete(id) {
            if (confirm("Êtes-vous sûr de vouloir supprimer ce contact ?")) {
                document.getElementById("form-" + id).action = "";
                document.getElementById("form-" + id).submit();
            }
        }
    </script>
    <script src="script.js"></script>
</body>
</html>
