<?php
header("Access-Control-Allow-Origin: *"); // Permite todas las conexiones (puedes restringirlo)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejar preflight (opcional, pero recomendado)
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}
require_once "routes/api.php";