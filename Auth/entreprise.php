<?php
class Entreprise {
    private $id;
    private $nom;
    private $adresse;
    private $email;
    private $localisation;
    private $domaine;

    public function __construct($id, $nom, $adresse, $email, $localisation, $domaine) {
        $this->id = $id;
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->email = $email;
        $this->localisation = $localisation;
        $this->domaine = $domaine;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getAdresse() { return $this->adresse; }
    public function getEmail() { return $this->email; }
    public function getLocalisation() { return $this->localisation; }
    public function getDomaine() { return $this->domaine; }
}
?>
