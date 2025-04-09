<?php

require_once './models/AlumnosModel.php';

class AlumnoController {
    private $alumno;

    public function __construct() {
        $this->alumno = new Alumno();
    }

    public function getAll() {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->alumno->getAll(),JSON_UNESCAPED_UNICODE);
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
        echo json_encode(['success' => $success]);
    }

    public function delete($id) {
        $success = $this->alumno->delete($id);
        echo json_encode(['success' => $success]);
    }
}
