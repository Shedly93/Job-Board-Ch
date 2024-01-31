<?php

class Emploi {
    private $id;
    private $id_entrepriseE;
    private $titre;
    private $description;
    private $salaire;
    private $contrat;
    private $datePost;
    private $conn;

    public function __construct($conn, $id, $id_entrepriseE, $titre, $description, $salaire, $contrat, $datePost){
        $this->conn = $conn;
        $this->id = $id;
        $this->id_entrepriseE = $id_entrepriseE;
        $this->titre = $titre;
        $this->description = $description;
        $this->salaire = $salaire;
        $this->contrat = $contrat;
        $this->datePost = $datePost;
    }

    public function getId() { return $this->id; }
    public function getIdEntreprise() { return $this->id_entrepriseE; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getSalaire() { return $this->salaire; }
    public function getContrat() { return $this->contrat; }
    public function getDatePost() { return $this->datePost; }

 public function postEmploi($idEntreprise, $titre, $description, $salaire, $contrat)
{
    $datePost = date("Y-m-d H:i:s");
    $query = "INSERT INTO emploi (id_entreprise, titre, description, salaire, contrat, date_post) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('isssss', $idEntreprise, $titre, $description, $salaire, $contrat, $datePost);
    return $stmt->execute();
}

 public function getAllEmplois() {
    $query = "SELECT * FROM emploi";
    $resultats = $this->conn->query($query);

    if ($resultats) {
        return $resultats->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false; 
    }
}


 public function getEmploisParEntreprise($idEntreprise) {
        $query = "SELECT * FROM emploi WHERE id_entreprise = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $idEntreprise);
        $stmt->execute();

       
        $resultats = $stmt->get_result();
        return $resultats->fetch_all(MYSQLI_ASSOC);
    }

public function updateEmploi($idEmploi, $titre, $description, $salaire, $contrat) {
    $query = "UPDATE emploi SET titre=?, description=?, salaire=?, contrat=? WHERE id_emploi=?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('ssssi', $titre, $description, $salaire, $contrat, $idEmploi);
    return $stmt->execute();
}

  public function deleteEmploi($idEmploi) {
        $query = "DELETE FROM emploi WHERE id_emploi=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $idEmploi);
        return $stmt->execute();
    }
    
}
?>
