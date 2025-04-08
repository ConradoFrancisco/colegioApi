<?php
require_once "./controllers/UserController.php";
$controller = new UserController();
if ($_SERVER["REQUEST_METHOD"] === "GET" && $_GET["endpoint"] === "users") {
    $controller->index();
}

if ($_SERVER["REQUEST_METHOD"] === "PUT" && $_GET["endpoint"] === "updateStatus") {
    $controller->updateStatus();
}

if ($_SERVER["REQUEST_METHOD"] === "PUT" && $_GET["endpoint"] === "updateDelete") {
    $controller->updateDelete();
}