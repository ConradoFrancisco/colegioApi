<?php
require_once './models/FamiliarModel.php';
require_once './models/AlumnoFamiliarModel.php';

class FamiliarController {
    private $familiarModel;
    private $alumnoFamiliarModel;

    public function __construct() {
        $this->familiarModel = new Familiar();
        $this->alumnoFamiliarModel = new AlumnoFamiliar();
    }

    public function create($data) {
        $familiarId = $this->familiarModel->create($data);
        if ($familiarId) {
            echo json_encode(['message' => 'Familiar creado con éxito', 'id' => $familiarId]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear el familiar']);
        }
    }

    public function vincularAlumno($data) {
        $success = $this->alumnoFamiliarModel->create($data['alumno_id'], $data['familiar_id'], $data['parentesco']);
        if ($success) {
            echo json_encode(['message' => 'Familiar vinculado al alumno con éxito']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al vincular familiar al alumno']);
        }
    }

    public function getAll() {
        $familiares = $this->familiarModel->getAll();
        echo json_encode($familiares);
    }
}