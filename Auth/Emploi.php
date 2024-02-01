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



// ...



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
        $query = "SELECT emploi.*, entreprise.nom_entreprise
FROM emploi
JOIN entreprise ON emploi.id_entreprise = entreprise.id_entreprise
WHERE emploi.id_entreprise = ?";
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
    
    public function getInfoFromDatabase($conn) {
    $sql = "SELECT * FROM emploi WHERE id_emploi = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $this->id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $emploiData = $result->fetch_assoc();

        return new Emploi(
            $conn,
            $emploiData['id_emploi'],
            $emploiData['id_entreprise'],
            $emploiData['titre'],
            $emploiData['description'],
            $emploiData['salaire'],
            $emploiData['contrat'],
            $emploiData['date_post']
        );
    } else {
        return null;
    }
}
   public function getNomEntrepriseFromDatabase($id_entreprise, $conn) {
        $sql = "SELECT nom_entreprise FROM entreprise WHERE id_entreprise = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_entreprise);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $entrepriseData = $result->fetch_assoc();
            return $entrepriseData['nom_entreprise'];
        } else {
            return null;
        }
    }
   public function getNomEntreprise() {
        return $this->getNomEntrepriseFromDatabase($this->id_entrepriseE, $this->conn);
    }
}

?>
