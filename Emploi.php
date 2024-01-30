<?php

class Emploi {
    private $id;
    private $idEntreprise;
    private $titre;
    private $description;
    private $salaire;
    private $contrat;
    private $datePost;

    public function __construct($id, $idEntreprise, $titre, $description, $salaire, $contrat, $datePost) {
        $this->id = $id;
        $this->idEntreprise = $idEntreprise;
        $this->titre = $titre;
        $this->description = $description;
        $this->salaire = $salaire;
        $this->contrat = $contrat;
        $this->datePost = $datePost;
    }

    public function getId() { return $this->id; }
    public function getIdEntreprise() { return $this->idEntreprise; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getSalaire() { return $this->salaire; }
    public function getContrat() { return $this->contrat; }
    public function getDatePost() { return $this->datePost; }
}
?>
