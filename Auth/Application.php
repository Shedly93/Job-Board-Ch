<?php
class Application {
    private $id_emploiA;
    private $id_utilisateurA;
    private $date_app;
    private $conn;

    public function __construct($conn, $id_emploiA, $id_utilisateurA, $date_app = null){
        $this->conn = $conn;
        $this->id_emploiA = $id_emploiA;
        $this->id_utilisateurA = $id_utilisateurA;
        $this->date_app = $date_app ? $date_app : date("Y-m-d H:i:s");
    }

    public function getIdEmploi() { return $this->id_emploiA; }
    public function getIdUtilisateur() { return $this->id_utilisateurA; }
    public function getDateApp() { return $this->date_app; }

public function postulerUtilisateur($id_emploi, $id_utilisateur, $description) {
    $date_app = date("Y-m-d H:i:s");

    $checkQuery = "SELECT * FROM application WHERE id_emploiA = ? AND id_utilisateurA = ?";
    $checkStmt = $this->conn->prepare($checkQuery);
    $checkStmt->execute([$id_emploi, $id_utilisateur]);

    if ($checkStmt->fetch(PDO::FETCH_ASSOC)) {
        return false;
    }

    $insertQuery = "INSERT INTO application (id_emploiA, id_utilisateurA, date_app) VALUES (?, ?, ?)";
    $insertStmt = $this->conn->prepare($insertQuery);

    $insertStmt->bindValue(1, $id_emploi, PDO::PARAM_INT);
    $insertStmt->bindValue(2, $id_utilisateur, PDO::PARAM_INT);
    $insertStmt->bindValue(3, $date_app, PDO::PARAM_STR);

    return $insertStmt->execute();
}

public function getApplicationsParEmploi($id_emploi) {
    $query = "SELECT utilisateur.nom AS nom_utilisateur, entreprise.nom_entreprise, application.date_app, utilisateur.description
              FROM application
              JOIN utilisateur ON application.id_utilisateurA = utilisateur.id_user
              JOIN emploi ON application.id_emploiA = emploi.id_emploi
              JOIN entreprise ON emploi.id_entreprise = entreprise.id_entreprise
              WHERE application.id_emploiA = ?";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('i', $id_emploi);
    $stmt->execute();

    if ($stmt->errno) {
        echo "Error executing query: " . $stmt->error;
        exit();
    }

    $result = $stmt->get_result();

    $applicationsData = $result->fetch_all(MYSQLI_ASSOC);

   var_dump($applicationsData);

    return json_encode($applicationsData);
}


}

?>