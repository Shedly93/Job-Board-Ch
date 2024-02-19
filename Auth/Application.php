<?php
class Application {
    private $id_emploi;
    private $id_utilisateur;
    private $date_app;
    private $status;
    private $conn;

    public function __construct($conn, $id_emploi, $id_utilisateur, $status, $date_app = null){
        $this->conn = $conn;
        $this->id_emploi = $id_emploi;
        $this->id_utilisateur = $id_utilisateur;
        $this->status = $status;
        $this->date_app = $date_app ? $date_app : date("Y-m-d H:i:s");
    }

    public function getIdEmploi() { return $this->id_emploi; }
    public function getIdUtilisateur() { return $this->id_utilisateur; }
    public function getDateApp() { return $this->date_app; }
    public function getStatus() { return $this->status; }

public function postulerUtilisateur($id_emploi, $id_utilisateur, $description) {
    $date_app = date("Y-m-d H:i:s");
    $status = "en cours";

    $checkQuery = "SELECT * FROM application WHERE id_emploi = ? AND id_utilisateur = ?";
    $checkStmt = $this->conn->prepare($checkQuery);
    $checkStmt->execute([$id_emploi, $id_utilisateur]);

    if ($checkStmt->fetch(PDO::FETCH_ASSOC)) {
        return false;
    }

    $insertQuery = "INSERT INTO application (id_emploi, id_utilisateur, date_app, status) VALUES (?, ?, ?, ?)";
    $insertStmt = $this->conn->prepare($insertQuery);

    $insertStmt->bindValue(1, $id_emploi, PDO::PARAM_INT);
    $insertStmt->bindValue(2, $id_utilisateur, PDO::PARAM_INT);
    $insertStmt->bindValue(3, $date_app, PDO::PARAM_STR);
    $insertStmt->bindValue(4, $status, PDO::PARAM_STR);

    return $insertStmt->execute();
}

public function getApplicationsParEmploi($id_emploi) {
    $query = "SELECT id_emploi,titre, id_utilisateur, nom, prenom, description_utilisateur FROM view_application_infos WHERE id_emploi = ?
";
    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        error_log("Error preparing query: " . $this->conn->error);
        exit();
    }

    $stmt->bind_param("i", $id_emploi);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result === false) {
        error_log("Error executing query: " . $stmt->error);
        exit();
    }

    $applicationsData = array();

    while ($row = $result->fetch_assoc()) {
        $applicationsData[] = $row;
    }

    $stmt->close();

    return $applicationsData;
}


}
?>
