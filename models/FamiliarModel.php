<?php

require_once "./config/Database.php";
class Familiar {
    
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function create($data) {
        // Primero verifica si ya existe un familiar con el mismo DNI
        $query = "SELECT id FROM familiares WHERE dni = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$data['dni']]);
        $existingFamiliar = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingFamiliar) {
            return $existingFamiliar['id']; // Si ya existe, retorna el ID del familiar existente
        }

        // Si no existe, crea un nuevo familiar
        $query = "INSERT INTO familiares (apellido, nombre, dni, fecha_nacimiento, telefono) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $data['apellido'],
            $data['nombre'],
            $data['dni'],
            $data['fechaNac'],
            $data['telefono']
        ]);

        return $this->db->lastInsertId(); // Devuelve el ID del nuevo familiar
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM familiares");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
