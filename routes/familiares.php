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
        case 'update':
            if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
                $_PUT = json_decode(file_get_contents("php://input"), true);
                $controller->update($_GET['id'], $_PUT);
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