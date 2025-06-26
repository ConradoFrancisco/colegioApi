<?php

require_once './models/AlumnosModel.php';
require_once './models/FamiliarModel.php';
require_once './models/AlumnoFamiliarModel.php';

class AlumnoController {
    private $alumno;
    private $familiarModel;
    private $alumnoFamiliarModel;

    public function __construct() {
        $this->alumno = new Alumno();
        $this->familiarModel = new Familiar();
        $this->alumnoFamiliarModel = new AlumnoFamiliar();
    }

    public function getAll() {
        header('Content-Type: application/json; charset=utf-8');
    
        $params = [
            'busqueda' => $_GET['busqueda'] ?? null,
            'orden' => $_GET['orden'] ?? null,
            'orderDirection' => $_GET['orderDirection'] ?? null,
            'barrio' => $_GET['barrio'] ?? null,
            'limit' => isset($_GET['limit']) ? (int)$_GET['limit'] : null,
            'offset' => isset($_GET['offset']) ? (int)$_GET['offset'] : null
        ];
    
        $result = $this->alumno->getAll($params);
    
        echo json_encode([
            'data' => $result['data'],
            'total' => $result['total'],
            'sql' => $result['query'],
            'params' => $params
        ], JSON_UNESCAPED_UNICODE);
    }

    public function getById($id) {
        echo json_encode($this->alumno->getById($id));
    }

    public function store($data) {
        $success = $this->alumno->create($data);
        echo json_encode(['message' => 'Alumno creado Correctamente', 'success' => $success]);
    }

    public function update($id, $data) {
        $success = $this->alumno->update($id, $data);
        echo json_encode(['message' => 'Alumno editado Correctamente','success' => $success]);
    }

    public function delete($id) {
        $success = $this->alumno->delete($id);
        echo json_encode(['success' => $success]);
    }

    public function storeFamilarAlumno($data) {
        // 1. Crea el familiar (si no existe)
        $familiarId = $this->familiarModel->create($data);

        // 2. Crea la relación alumno-familiar
        $response = $this->alumnoFamiliarModel->create($data['alumno_id'], $familiarId, $data['parentesco']);

        if (isset($response['error'])) {
            echo json_encode($response);
            return;
        }

        echo json_encode(['success' => 'Familiar agregado correctamente']);
    }

    public function updatePrioridad($id, $data) {
        echo json_encode($data);
        $success = $this->alumno->updatePrioridad($id, $data);
        echo json_encode(['message' => 'Respuestas guardadas satisfactoriamente','success' => $success]);
    }
}
