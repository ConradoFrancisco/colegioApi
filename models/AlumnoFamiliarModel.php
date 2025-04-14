<?php
require_once "./config/Database.php";

class AlumnoFamiliar {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function vincular($alumnoId, $familiarId) {
        $query = "INSERT INTO alumno_familiar (alumno_id, familiar_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$alumnoId, $familiarId]);
    }
    
    public function desvincular($alumnoId, $familiarId) {
        $query = "DELETE FROM alumno_familiar WHERE alumno_id = ? AND familiar_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$alumnoId, $familiarId]);
    }
    
    public function getFamiliaresByAlumno($alumnoId) {
        $query = "SELECT f.* FROM familiares f
                  INNER JOIN alumno_familiar af ON af.familiar_id = f.id
                  WHERE af.alumno_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$alumnoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($alumnoId, $familiarId, $parentesco) {
        // Verifica si ya existe la relación
        $query = "SELECT * FROM alumno_familiar WHERE alumno_id = ? AND familiar_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$alumnoId, $familiarId]);
        $existingRelation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingRelation) {
            // Si ya existe, no crees la relación de nuevo
            return ['error' => 'La relación ya existe'];
        }

        // Si no existe, crea la relación
        $query = "INSERT INTO alumno_familiar (alumno_id, familiar_id, parentesco) 
                  VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$alumnoId, $familiarId, $parentesco]);

        return ['success' => 'Relación creada correctamente'];
    }
}

