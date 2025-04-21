<?php
require_once './models/ActividadModel.php';


class ActividadController {
    private $actividadModel;


    public function __construct() {
        $this->actividadModel = new Actividad();
        
    }
    public function getAll() {
        header('Content-Type: application/json; charset=utf-8');
        $params = [
            'limit' => isset($_GET['limit']) ? (int)$_GET['limit'] : null,
            'offset' => isset($_GET['offset']) ? (int)$_GET['offset'] : null
        ];
    
        $result = $this->actividadModel->getAll($params);
    
        echo json_encode([
            'data' => $result['data'],
            'total' => $result['total']
        ], JSON_UNESCAPED_UNICODE);
    }

  
    public function create($data) {
        $success = $this->actividadModel->create($data);
        echo json_encode(['message' => 'Actividad creada correctamente', 'success' => $success]);
    }

   /*  public function update($id,$data) {
        try{
            $success = $this->familiarModel->update($id,$data);
            echo json_encode(['message' => 'Familiar actualizado con éxito']);

        }catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar el familiar: ' . $e->getMessage()]);
        }
        
    }
    public function delete($id) {
        $success = $this->familiarModel->delete($id);
        if ($success) {
            echo json_encode(['message' => 'Familiar eliminado con éxito']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al eliminar el familiar']);
        }}

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
    } */
}