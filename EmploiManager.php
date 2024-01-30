<?php

class EmploiManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function postEmploi($idEntreprise, $titre, $description, $salaire, $contrat) {
        $datePost = date("Y-m-d H:i:s");
        $query = "INSERT INTO emploi (id_entrepriseE, titre, description, salaire, contrat, date_post) 
                  VALUES (:idEntreprise, :titre, :description, :salaire, :contrat, :datePost)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idEntreprise', $idEntreprise);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':salaire', $salaire);
        $stmt->bindParam(':contrat', $contrat);
        $stmt->bindParam(':datePost', $datePost);
        return $stmt->execute();
    }

    public function updateEmploi($idEmploi, $idEntreprise, $titre, $description, $salaire, $contrat) {
        $query = "UPDATE emploi SET id_entrepriseE = :idEntreprise, titre = :titre, description = :description, salaire = :salaire, contrat = :contrat WHERE id_emploi = :idEmploi";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idEntreprise', $idEntreprise);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':salaire', $salaire);
        $stmt->bindParam(':contrat', $contrat);
        $stmt->bindParam(':idEmploi', $idEmploi);
        return $stmt->execute();
    }

    public function getAllEmplois() {
        $query = "SELECT * FROM emploi";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEmploiById($idEmploi) {
        $query = "SELECT * FROM emploi WHERE id_emploi = :idEmploi";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idEmploi', $idEmploi);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}
?>
