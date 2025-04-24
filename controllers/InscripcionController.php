<?php
require_once './models/InscripcionModel.php';


class InscripcionController {
    private $inscripcionModel;


    public function __construct() {
        $this->inscripcionModel = new Inscripcion();
        
    }
    /* public function getAll() {
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
    } */

  
    public function create($data) {
        $success = $this->inscripcionModel->create($data);
        echo json_encode(['message' => 'Inscripcion realizada correctamente', 'success' => $success]);
    }
    public function getAll($id) {
        $result = $this->inscripcionModel->getInscriptos($id);
        echo json_encode(['data' => $result], JSON_UNESCAPED_UNICODE);
    }
    public function toggleState($id,$data){
        $str = $data['estado'] === 1 ? 'Inscripcion dada de alta' : 'Inscripcion dada de baja';
        header('Content-Type: application/json; charset=utf-8');
        $success = $this->inscripcionModel->toggleState($id, $data['estado']);
        echo json_encode(['message' => $str, 'success' => $success]);
    }
}