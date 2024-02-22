<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="dashboardEntreprise.css">
    <link rel="stylesheet" href="\Job-Board-Ch\Auth\gestionEmploiEntreprise.css">
    <link rel="stylesheet" href="../profileEntreprise.css">
    <link rel="stylesheet" href="../modal.css">
</head>
<body>
    <div class="dashboard-entreprise">
        <div class="header">
            <nav>
                <img src="\Job-Board-Ch\Auth\Logo-JobBoard.png" alt="Logo de Job-Board-CH" class="logo" />
                <ul>
                    <li><a href="../../Home/index.html">Accueil</a></li>
                    <li><a href="/Auth/GestionEmploisutilisateur.php">Offres d'emploi</a></li>
                    <li><a href="../..//Home/contact.html">Contact</a></li>
                    <li><a href="../profile_entreprise.php">Profile</a></li>
                    <li><a href="#" id="logout">Logout</a></li>
                </ul>
            </nav>
        </div>
        <div class="dashboard_container">
            <!-- Sidebar -->
            <aside class="main-sidebar">
                <div class="sidebar" id="sidebar">
                    <ul class="list-items">
                        <li class="item active">
                            <a href="../GestionEmploisEntreprise.php">
                                <span class="material-icons-sharp">
                                    
                                </span>
                                <span>emploi</span>
                            </a>
                        </li>
                        <li class="item">
                            <a href="../profile_entreprise.php">
                                <span>Profile</span>
                            </a>
                        </li>
                        <li class="item">
                            <a href="#">
                                <span>Applications</span>
                            </a>
                        </li>
                        <li class="item">
                            <a href="#">
                                <span>Ajouter un produit</span>
                            </a>
                        </li>
                        <li class="item">
                            <a href="#">
                                <span>Se Deconnecter</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>  
            <!-- Content Area -->
            <div class="content">
                <!-- Le contenu chargé dynamiquement sera affiché ici -->
            </div>
        </div>
        <div class="footer-copyright">
            <div class="footer-copyright-wrapper">
                <p class="footer-copyright-text">
                    <a class="footer-copyright-link" href="#" target="_self"> ©2024. | CH-JOB-BOARD. | All rights reserved. </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Charger la page d'accueil au chargement initial de la page
            $('.content').load('../Auth/GestionEmploisutilisateur.php');

            $('.item a').on('click', function(e) {
                e.preventDefault(); // Empêche le lien de charger la page directement
                
                var urlToLoad = $(this).attr('href'); // Utilise l'attribut href du lien cliqué

                // Utilise AJAX pour charger le contenu depuis l'URL spécifiée
                $.ajax({
                    url: urlToLoad,
                    type: 'GET',
                    success: function(response) {
                        $('.content').html(response); // Affiche le contenu dans la div .content
                    },
                    error: function() {
                        $('.content').html('<p>Erreur lors du chargement du contenu.</p>'); 
                    }
                });
            });
        });
    </script>
</body>
</html>
