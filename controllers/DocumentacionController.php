<?php

require_once './models/DocumentacionModel.php';
class DocumentacionController {
    private $documentacionModel;

    public function __construct() {
        $this->documentacionModel = new DocumentacionModel();
    }

    public function subir() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
            $alumno_id = $_POST['alumno_id'];
            $tipo = $_POST['tipo'];
           

            $archivo = $_FILES['archivo'];
            $nombreArchivo = time() . '_' . basename($archivo['name']);
            $ruta = "uploads/documentacion/" . $nombreArchivo;

            if (move_uploaded_file($archivo['tmp_name'], $ruta)) {
                $data = [
                    'alumno_id' => $alumno_id,
                    'tipo' => $tipo,
                    'url' => $ruta,
                ];
                $success = $this->documentacionModel->subirDocumentacion($data);
                echo json_encode(['success' => $success, 'message' => 'Archivo subido correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al subir el archivo']);
            }
        }
    }
}