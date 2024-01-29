<?php

class Utilisateur {
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $description;

    public function __construct($id, $nom, $prenom, $email, $password, $description) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->description = $description;
    }

    // Méthodes "getter"
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getDescription() { return $this->description; }

    // Méthode magique __toString
    public function __toString() {
        return "Utilisateur: {$this->nom} {$this->prenom}, Email: {$this->email}";
    }
        public static function getInfoFromDatabase($userId, $conn) {
        $sql = "SELECT * FROM utilisateur WHERE id_user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();

            // Créez une instance de la classe Utilisateur avec les données de la base de données
            $user = new Utilisateur(
                $userData['id_user'],
                $userData['nom'],
                $userData['prenom'],
                $userData['email'],
                $userData['password'],
                $userData['description']
            );

            return $user;
        } else {
            return null; // Utilisateur non trouvé
        }
    }
}
?>
