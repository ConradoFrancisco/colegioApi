<?php

require_once './controllers/InscripcionController.php';
$controller = new InscripcionController();

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
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Acción no válida']);
}