<?php
class Entreprise {
    private $id;
    private $nom;
    private $adresse;
    private $email;
    private $localisation;
    private $domaine;
    private $password;

    public function __construct($id, $nom, $adresse, $email, $localisation, $domaine, $password) {
        $this->id = $id;
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->email = $email;
        $this->localisation = $localisation;
        $this->domaine = $domaine;
        $this->password = $password;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getAdresse() { return $this->adresse; }
    public function getEmail() { return $this->email; }
    public function getLocalisation() { return $this->localisation; }
    public function getDomaine() { return $this->domaine; }
    public function getPassword() { return $this->password; }

    public function __toString() {
        return "Entreprise: {$this->nom}, Adresse: {$this->adresse}, Email: {$this->email}";
    }

    public function updateInfoInDatabase($newNom, $newAdresse, $newEmail, $newLocalisation, $newDomaine, $conn) {
        $sql = "UPDATE entreprise SET nom_entreprise = '$newNom', adresse = '$newAdresse', email = '$newEmail', localisation = '$newLocalisation', domaine = '$newDomaine' WHERE id_entreprise = {$this->id}";
        $result = $conn->query($sql);
        return $result;
    }

    public function updatePasswordInDatabase($newPassword, $conn) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE entreprise SET password = '$hashedPassword' WHERE id_entreprise = {$this->id}";
        $result = $conn->query($sql);
        return $result;
    }

    public function deleteFromDatabase($conn) {
        $sql = "DELETE FROM entreprise WHERE id_entreprise = {$this->id}";
        $result = $conn->query($sql);
        return $result;
    }

   public static function getInfoFromDatabase($user_id, $conn) {
    $sql = "SELECT * FROM entreprise WHERE id_entreprise = $user_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $entrepriseData = $result->fetch_assoc();
        return new Entreprise(
            $entrepriseData['id_entreprise'],
            $entrepriseData['nom_entreprise'],
            $entrepriseData['adresse'],
            $entrepriseData['email'],
            $entrepriseData['localisation'],
            $entrepriseData['domaine'],
            $entrepriseData['password']
        );
    } else {
        return null;
    }
}
}
?>
