<?php

class DocumentacionModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function subirDocumentacion($data) {
        $query = "INSERT INTO documentacion_adjunta (alumno_id, tipo, url) 
                  VALUES (:alumno_id, :tipo, :url)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function obtenerDocumentacionPorAlumno($alumno_id) {
        $query = "SELECT * FROM documentacion_alumnos WHERE alumno_id = :alumno_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['alumno_id' => $alumno_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>