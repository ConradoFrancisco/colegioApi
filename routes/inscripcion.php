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
            case 'getInscriptos':
                if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
                    $controller->getAll($_GET['id']);
                }
                break;
                case 'toggleState':
                    if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
                        $_PUT = json_decode(file_get_contents("php://input"), true);
                        $controller->toggleState($_GET['id'], $_PUT);
                    }
                    break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Acción no válida']);
}