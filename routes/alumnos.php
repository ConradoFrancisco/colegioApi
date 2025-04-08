<?php

require_once './controllers/AlumnoController.php';
$controller = new AlumnoController();

switch ($action) {
    case 'getAll':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller->getAll();
        }
        break;

    case 'get':
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $controller->getById($_GET['id']);
        }
        break;

    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $controller->store($data);
        }
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
            parse_str(file_get_contents("php://input"), $_PUT);
            $controller->update($_GET['id'], $_PUT);
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
            $controller->delete($_GET['id']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Acción no válida']);
}