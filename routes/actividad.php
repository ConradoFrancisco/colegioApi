<?php

require_once './controllers/ActividadController.php';
$controller = new ActividadController();

switch ($action) {
    case 'getAll':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller->getAll();
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Acción no válida']);
}