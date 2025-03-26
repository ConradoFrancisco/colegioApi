<?php
require_once "./models/UserModel.php";
class UserController {
    public function index() {
        $user = new UserModel();
       echo json_encode($user->getUsers());
    }
    public function updateStatus() {
        // Obtener datos del cuerpo de la petición
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["id"]) || !isset($data["estado"])) {
            echo json_encode(["error" => "Faltan datos"]);
            return;
        }

        $id = $data["id"];
        $estado = $data["estado"];
        $user = new UserModel();

        // Llamar al modelo para actualizar el estado
        $result = $user->updateStatus($id, $estado);
        $msg = "";
        if($estado == 1){
            $msg = "Usuario dado de alta!";
        }else{
            $msg = "Usuario dado de baja!";
        }
        if ($result) {
            echo json_encode(["message" => $msg]);
        } else {
            echo json_encode(["error" => "No se pudo actualizar el estado"]);
        }
    }
    public function updateDelete() {
        // Obtener datos del cuerpo de la petición
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["id"])) {
            echo json_encode(["error" => "Faltan datos"]);
            return;
        }

        $id = $data["id"];
        $user = new UserModel();

        $result = $user->updateDelete($id);
        
        if ($result) {
            echo json_encode(["message" => "Usuario eliminado con éxito"]);
        } else {
            echo json_encode(["error" => "No se pudo actualizar el estado"]);
        }
    }
}