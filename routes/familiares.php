<?php

require_once './controllers/FamiliarController.php';
$controller = new FamiliarController();

switch ($action) {
    case 'getAll':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller->getAll();
        }
        break;

    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $controller->create($data);
        }
        break;

    case 'vincular':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $controller->vincularAlumno($data);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Acción no válida']);
}