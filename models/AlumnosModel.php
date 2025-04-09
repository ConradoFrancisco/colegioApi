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
        try{
            $query = "INSERT INTO alumnos (nombre, apellido, dni, fecha_nacimiento, direccion, barrio, socio_educativo, escuela, anio_escolar)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $data['socio_educativo'] = $data['socio_educativo'] === '0' ? false : true;
            $stmt->execute([
                $data['nombre'],
                $data['apellido'],
                $data['dni'],
                $data['fecha_nacimiento'],
                $data['direccion'],
                $data['barrio'],
                $data['socio_educativo'],
                $data['escuela'],
                $data['anio_escolar'],
            ]);
            return ['success' => true,'message' => 'Alumno creado correctamente'];
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error al crear el alumno: ' . $e->getMessage()]);
            return false;
        }

       
    }

    public function update($id, $data) {
        $query = "UPDATE alumnos SET nombre = ?, apellido = ?, dni = ?, fecha_nac = ?, direccion = ?, barrio = ?, socio_educativo = ?, escuela = ?, anio_escolar = ?, turno = ?
                  WHERE id = ?";
        $stmt = $this->db->prepare($query);

        $data['socio_educativo'] = $data['socio_educativo'] === '0' ? false : true;

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
          
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM alumnos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
