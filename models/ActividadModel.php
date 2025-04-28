<?php
require_once "./config/Database.php";

class Actividad {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

   public function getAll($params = []) {
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM actividades where 1=1";
        $queryParams = [];
        if(isset($params['estado'])) {
            $sql .= " AND estado = 'Activa'";
        }
        // Limit y offset
        if (isset($params['limit'])) {
            $sql .= " LIMIT :limit";
            $queryParams[':limit'] = (int) $params['limit'];
        }
    
        if (isset($params['offset'])) {
            $sql .= " OFFSET :offset";
            $queryParams[':offset'] = (int) $params['offset'];
        }

    
        $stmt = $this->db->prepare($sql);
    
        // Bind seguro para evitar problemas con LIMIT/OFFSET que no aceptan bindParam de strings
        foreach ($queryParams as $key => &$val) {
            if (in_array($key, [':limit', ':offset'])) {
                $stmt->bindValue($key, $val, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $val);
            }
        }
    
        $stmt->execute();
        $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total = $this->db->query("SELECT FOUND_ROWS()")->fetchColumn();
    
        return [
            'data' => $actividades,
            'total' => (int)$total
        ];
    }
    public function create($data) {
        $query = "INSERT INTO actividades (nombre, tipo, descripcion, cupo, turno, fecha_inicio, fecha_fin, estado)
                  VALUES (:nombre, :tipo, :descripcion, :cupo, :turno, :fecha_inicio, :fecha_fin, :estado)";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':tipo', $data['tipo']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':cupo', $data['cupo']);
        $stmt->bindParam(':turno', $data['turno']);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':fecha_fin', $data['fecha_fin']);
        $stmt->bindParam(':estado', $data['estado']);
    
        if ($stmt->execute()) {
            return $this->db->lastInsertId(); // Devuelve el ID de la actividad creada
        } else {
            return false;
        }
    }
    public function update($id,$data){
        $query = "UPDATE actividades SET nombre = ?, tipo = ?, descripcion = ?, cupo = ?, fecha_inicio = ?, fecha_fin = ?, estado = ?
                  WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $data['nombre'],
            $data['tipo'],
            $data['descripcion'],
            $data['cupo'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['estado'],
            $id 
        ]);
    }
    public function updateEstado($id,$estado){
        $query = "UPDATE actividades SET estado = ?
                  WHERE id = ?";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $estado,
            $id 
        ]);
    }
    
}

