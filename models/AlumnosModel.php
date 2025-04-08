<?php
require_once "./config/Database.php";

class Alumno {
    

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM alumnos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM alumnos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO alumnos (nombre, apellido, dni, fecha_nac, direccion, barrio, socio_educativo, escuela, anio_escolar, turno)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['dni'],
            $data['fecha_nac'],
            $data['direccion'],
            $data['barrio'],
            $data['socio_educativo'],
            $data['escuela'],
            $data['anio_escolar'],
            $data['turno'],
        ]);
    }

    public function update($id, $data) {
        $query = "UPDATE alumnos SET nombre = ?, apellido = ?, dni = ?, fecha_nac = ?, direccion = ?, barrio = ?, socio_educativo = ?, escuela = ?, anio_escolar = ?, turno = ?
                  WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['dni'],
            $data['fecha_nac'],
            $data['direccion'],
            $data['barrio'],
            $data['socio_educativo'],
            $data['escuela'],
            $data['anio_escolar'],
            $data['turno'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM alumnos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
