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
    <style>
        .main-sidebar {
    position: fixed;
    left: 0;
    top: 12%;
    height: 88%;
    width: 15%;
    background-color: #f4f4f4;
    border-right: 1px solid #ccc;
    overflow-y: auto;
}

    </style>
</head>
<body>
    <div class="dashboard-entreprise">
        <div class="header">
            <nav>
                <img src="\Job-Board-Ch\Auth\Logo-JobBoard.png" alt="Logo de Job-Board-CH" class="logo" />
                <ul>
                    <li><a href="../../Home/index.html">Accueil</a></li>
                    <li><a href="/Auth/GestionEmploisutilisateur.php">Candidature</a></li>
                    <li><a href="../..//Home/contact.html">Contact</a></li>
                    <li><a href="../profile_entreprise.php">Profile</a></li>
                    <li><a href="../../Auth/login.html" id="logout">Logout</a></li>
                </ul>
            </nav>
        </div>
        <div id="app">
            <!-- Votre contenu de dashboard -->
            <div class="main-sidebar">
                <div class="sidebar" id="sidebar">
                    <ul class="list-items">
                        <li class="item active">
                            <a href="../GestionEmploisEntreprise.php" @click.prevent="loadContent('../GestionEmploisEntreprise.php')">
                                <span class="material-icons-sharp"></span>
                                <span>Mes Emploi</span>
                            </a>
                        </li>
                        <li class="item">
                            <a href="../profile_entreprise.php" @click.prevent="loadContent('../profile_entreprise.php')">
                                <span>Profile</span>
                            </a>
                        </li>
                        <li class="item">
                            <a href="#" @click.prevent="loadContent('#')">
                                <span>Candidature</span>
                            </a>
                        </li>
                        <li class="item">
                            <a href="#" @click.prevent="loadContent('#')">
                                <span>Se Deconnecter</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="content" v-html="content">

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script>
        // Définir l'application Vue
        new Vue({
            el: '#app',
            data: {
                content: '' // Initialisation du contenu
            },
            methods: {
                loadContent(url) {
                    // Charger le contenu depuis l'URL
                    fetch(url)
                        .then(response => response.text())
                        .then(data => {
                            this.content = data;
                        })
                        .catch(error => {
                            console.error('Erreur lors du chargement du contenu :', error);
                            this.content = '<p>Erreur lors du chargement du contenu.</p>';
                        });
                }
            },
            mounted() {
                // Charger le contenu initial
                this.loadContent('../GestionEmploisEntreprise.php');
            }
        });
    </script>
</body>
</html>
