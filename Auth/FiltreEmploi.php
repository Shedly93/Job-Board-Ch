<?php

class FiltreEmploi {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Recherche les emplois par titre
    public function rechercherParTitre($titre) {
        $query = "SELECT * FROM emploi WHERE titre LIKE :titre";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['titre' => "%$titre%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
// Filtrer les emplois par type de contrat
// public function filtrerParContrat($contrats) {
//     if (empty($contrats)) {
//         return array(); // Si le tableau de contrats est vide, retourner un tableau vide
//     }

//     // Utilisez la clause IN pour filtrer les contrats spécifiés
//     $contratPlaceholders = implode(',', array_fill(0, count($contrats), '?'));

//     // Créez un tableau associatif avec des clés numériques pour les valeurs
//     $params = array();

//     foreach ($contrats as $index => $contrat) {
//         $params[$index + 1] = $contrat;
//     }

//     $query = "SELECT * FROM emploi WHERE contrat IN ($contratPlaceholders)";
//     $stmt = $this->pdo->prepare($query);
//     $stmt->execute(array_values($params));
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }




}
?>
