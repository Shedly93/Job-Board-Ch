<?php
class Contact {
    private $idContact; // Ajout de la propriété idContact
    private $name;
    private $email;
    private $message;

    public function __construct($name, $email, $message) {
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
    }
    
    // Méthode pour récupérer l'ID du contact
    public function getId() { return $this->idContact; }

    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getMessage() { return $this->message; }

    public function __toString() {
        return "Name: {$this->name}, Email: {$this->email}, Message: {$this->message}";
    }

    public function insertIntoDatabase($conn) {
        $name = $conn->real_escape_string($this->name);
        $email = $conn->real_escape_string($this->email);
        $message = $conn->real_escape_string($this->message);
        
        $sql = "INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$message')";
        $result = $conn->query($sql);
        
        if ($result) {
            $this->idContact = $conn->insert_id; // Récupération de l'ID généré par la base de données
            return true;
        } else {
            echo "Error: " . $conn->error; 
            return false;
        }
    }

    public static function getAllFromDatabase($conn) {
        $contacts = array();
        $sql = "SELECT * FROM contacts";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $contact = new Contact(
                    $row['name'],
                    $row['email'],
                    $row['message']
                );
                $contact->idContact = $row['idContact']; // Assignation de l'ID depuis la base de données
                $contacts[] = $contact;
            }
        }
        return $contacts;
    }

    public static function deleteById($conn, $id) {
        $id = $conn->real_escape_string($id);
        $sql = "DELETE FROM contacts WHERE idContact = '$id'";
        $result = $conn->query($sql);
        
        if ($result) {
            return true;
        } else {
            echo "Error: " . $conn->error; 
            return false;
        }
    }
}
?>
