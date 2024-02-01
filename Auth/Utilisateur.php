<?php

class Utilisateur {
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $description;
    private $conn;

    public function __construct($conn,$id, $nom, $prenom, $email, $password, $description) {
        $this->conn = $conn;
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->description = $description;
    }

    public function getId() { return $this->id; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }

  public function getNom() {
    return $this->nom;
}

public function getDescription() {
    return $this->description;
}

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
            echo "Aucune donnée trouvée pour l'utilisateur avec l'ID : " . $userId . "<br>";
            return null;
        }
    }

     public function getNomUtilisateurFromDatabase($id_user, $conn) {
        $sql = "SELECT nom FROM utilisateur WHERE id_user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $entrepriseData = $result->fetch_assoc();
            return $entrepriseData['nom'];
        } else {
            return null;
        }
    }
   public function getNomUtilisateur() {
        
        return $this->getNomUtilisateurFromDatabase($this->id, $this->conn);
    }
    
public function getprenomUtilisateurFromDatabase($id_user, $conn) {
    $sql = "SELECT prenom FROM utilisateur WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        return $userData['prenom'];
    } else {
        return null;
    }
}
    public function getprenomUtilisateur() {
        return $this->getprenomUtilisateurFromDatabase($this->id, $this->conn);
    }
}
?>
