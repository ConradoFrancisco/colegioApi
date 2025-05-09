<?php

require_once './controllers/DocumentacionController.php';
$controller = new DocumentacionController();

switch ($action) {
    case 'subir':
        $controller->subir();
        break;
    
    case 'alumno':
        if (isset($_GET['id'])) {
            $controller->getByAlumno($_GET['id']);
        }
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Acción no válida']);
}