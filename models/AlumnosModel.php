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
        // Obtener datos del alumno
        $stmtAlumno = $this->db->prepare("SELECT * FROM alumnos WHERE id = ?");
        $stmtAlumno->execute([$id]);
        $alumno = $stmtAlumno->fetch(PDO::FETCH_ASSOC);
    
        if (!$alumno) {
            return null;
        }
    
        // Obtener familiares (JOIN con tabla intermedia)
        $stmtFamiliares = $this->db->prepare("
            SELECT f.id, f.nombre, f.apellido, f.dni, f.telefono, af.parentesco, f.fecha_nacimiento as fechaNac
            FROM familiares f
            INNER JOIN alumno_familiar af ON af.familiar_id = f.id
            WHERE af.alumno_id = ?
        ");
        $stmtFamiliares->execute([$id]);
        $familiares = $stmtFamiliares->fetchAll(PDO::FETCH_ASSOC);
    
        // Obtener actividades (asumimos tabla alumno_actividad)
        $stmtActividades = $this->db->prepare("
            SELECT a.id, a.nombre, a.tipo, a.descripcion, a.cupo, a.turno,
                   a.fecha_inicio, a.fecha_fin, a.estado
            FROM actividades a
            INNER JOIN inscripciones aa ON aa.actividad_id = a.id
            WHERE aa.alumno_id = ?
        ");
        $stmtActividades->execute([$id]);
        $actividades = $stmtActividades->fetchAll(PDO::FETCH_ASSOC);
    
        // Formatear el resultado final
        $resultado = [
            'id' => (int)$alumno['id'],
            'nombre' => $alumno['nombre'],
            'apellido' => $alumno['apellido'],
            'dni' => $alumno['dni'],
            'fechaNac' => $alumno['fecha_nacimiento'],
            'direccion' => $alumno['direccion'],
            'barrio' => $alumno['barrio'],
            'socioEducativo' => (bool)$alumno['socio_educativo'],
            'escuela' => $alumno['escuela'],
            'anioEscolar' => $alumno['anio_escolar'],
            'familiares' => $familiares,
            'actividades' => $actividades
        ];
    
        return $resultado;
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
        $query = "UPDATE alumnos SET nombre = ?, apellido = ?, dni = ?, fecha_nacimiento = ?, direccion = ?, barrio = ?, socio_educativo = ?, escuela = ?, anio_escolar = ?
                  WHERE id = ?";
        $stmt = $this->db->prepare($query);

        $data['socioEducativo'] = $data['socioEducativo'] === '0' ? false : true;

        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['dni'],
            $data['fechaNac'],
            $data['direccion'],
            $data['barrio'],
            $data['socioEducativo'],
            $data['escuela'],
            $data['anioEscolar'],
          
            $id 
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM alumnos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
