<?php
require_once "./config/Database.php";

class Inscripcion {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function create($data) {
        $query = "INSERT INTO inscripciones (alumno_id, actividad_id, en_lista_espera)
                  VALUES (:alumno_id, :actividad_id, 1)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':alumno_id', $data['alumno_id']);
        $stmt->bindParam(':actividad_id', $data['actividad_id']);
        

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
