<?php
header("Access-Control-Allow-Origin: *"); // Permite todas las conexiones (puedes restringirlo)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
$endpoint = $_GET['endpoint'] ?? '';
$parts = explode('/', $endpoint); // e.g., alumnos/getAll → ['alumnos', 'getAll']

if (count($parts) < 2) {
    http_response_code(400);
    echo json_encode(['error' => 'Endpoint inválido']);
    exit;
}

$resource = $parts[0]; // alumnos
$action = $parts[1];   // getAll

// Cargamos el archivo de rutas correspondiente
$routesFile = __DIR__ . "/routes/{$resource}.php";

if (file_exists($routesFile)) {
    require $routesFile;
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Recurso no encontrado']);
}