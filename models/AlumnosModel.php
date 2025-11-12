<?php
require_once "./config/Database.php";

class Alumno {
    

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    public function getAll($params = []) {
        $sql = "SELECT SQL_CALC_FOUND_ROWS a.id,a.nombre,a.apellido,a.fecha_nacimiento,a.dni,a.barrio,a.direccion,a.escuela,a.prioridad,ac.nombre as actividad, ac.id as actividadId FROM alumnos a left join inscripciones i on a.id = i.alumno_id left join actividades ac on i.actividad_id = ac.id where true";
        $queryParams = [];
    
        // Búsqueda por nombre, apellido o dni
        if (!empty($params['busqueda'])) {
            $sql .= " AND (a.nombre LIKE :busqueda OR a.apellido LIKE :busqueda OR a.dni LIKE :busqueda)";
            $queryParams[':busqueda'] = '%' . $params['busqueda'] . '%';
        }
    
        // Filtro por barrio
        if (!empty($params['barrio'])) {
            $sql .= " AND barrio = :barrio";
            $queryParams[':barrio'] = $params['barrio'];
        }
    
        if (!empty($params['actividad'])) {
    $sql .= " AND ac.id = :actividad";
    $queryParams[':actividad'] = (int)$params['actividad'];
}
       /*  $sql .= " ORDER BY 'apellido' DESC"; */
        // Limit y offset
        if (isset($params['orden']) && isset($params['orderDirection'])) {
    // Validar que el campo y la dirección sean válidos
    $camposPermitidos = ['apellido', 'nombre', 'fecha_nacimiento', 'dni', 'barrio', 'direccion', 'escuela', 'prioridad'];
    $direccionesPermitidas = ['ASC', 'DESC'];

    if (in_array($params['orden'], $camposPermitidos) && in_array(strtoupper($params['orderDirection']), $direccionesPermitidas)) {
        $sql .= " ORDER BY {$params['orden']} {$params['orderDirection']}";
    }
}
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
        $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Obtener total sin limitar
        $total = $this->db->query("SELECT FOUND_ROWS()")->fetchColumn();
    
        return [
            'data' => $alumnos,
            'total' => (int)$total,
            'query' => $sql,
            'params' => $params
           
        ];
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
                   a.fecha_inicio, a.fecha_fin, a.estado,aa.en_lista_espera
            FROM actividades a
            INNER JOIN inscripciones aa ON aa.actividad_id = a.id
            WHERE aa.alumno_id = ?
        ");
        $stmtActividades->execute([$id]);
        $actividades = $stmtActividades->fetchAll(PDO::FETCH_ASSOC);
    
        $stmtDocumentacion = $this->db->prepare("
            SELECT d.id, d.tipo, d.url
            FROM documentacion_adjunta d
            WHERE d.alumno_id = ?
        ");
        $stmtDocumentacion->execute([$id]);
        $documentacion = $stmtDocumentacion->fetchAll(PDO::FETCH_ASSOC);


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
            'frecuenciaEscuela' => $alumno['frecuenciaEscuela'],
            'canastaBasica' => $alumno['canastaBasica'],
            'repitencia' => $alumno['repitencia'],
            'ingresosHogar' => $alumno['ingresosHogar'],
            'prioridad' => $alumno['prioridad'],
            'familiares' => $familiares,
            'actividades' => $actividades,
            'documentacion' => $documentacion,
        ];
    
        return $resultado;
    }

   public function create($data) {
    try {
        // Query con todos los campos
        $query = "INSERT INTO alumnos (
            nombre, 
            apellido, 
            dni, 
            fecha_nacimiento, 
            direccion, 
            barrio, 
            socio_educativo, 
            escuela, 
            anio_escolar,
            telefono,
            turno,
            ingresosHogar,
            canastaBasica,
            repitencia,
            frecuenciaEscuela,
            prioridad
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        // Normalización de valores
        $data['socio_educativo'] = isset($data['socio_educativo']) && $data['socio_educativo'] ? 1 : 0;
        $data['ingresosHogar'] = $data['ingresosHogar'] ?? null;
        $data['canastaBasica'] = $data['canastaBasica'] ?? null;
        $data['repitencia'] = $data['repitencia'] ?? null;
        $data['frecuenciaEscuela'] = $data['frecuenciaEscuela'] ?? null;
        $total = $data['frecuenciaEscuela'] + $data['canastaBasica'] + $data['repitencia'] + $data['ingresosHogar'] + 1;
        // Ejecución
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
            $data['telefono'],
            $data['turno'],
            $data['ingresosHogar'],
            $data['canastaBasica'],
            $data['repitencia'],
            $data['frecuenciaEscuela'],
            $total
        ]);

        return ['success' => true, 'message' => 'Alumno creado correctamente'];

    } catch (Exception $e) {
        echo json_encode(['error' => 'Error al crear el alumno: ' . $e->getMessage()]);
        return false;
    }
}

    public function update($id, $data) {
        $query = "UPDATE alumnos SET nombre = ?, apellido = ?, dni = ?, fecha_nacimiento = ?, direccion = ?, barrio = ?, socio_educativo = ?, escuela = ?, anio_escolar = ?, telefono = ?, turno = ?
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
            $data['telefono'],
            $data['turno'],
            $id 
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM alumnos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updatePrioridad($id, $data) {
        
        $frecuenciaEscuela = $data['frecuenciaEscuela'];
        $canastaBasica = $data['canastaBasica'];
        $repitencia = $data['repitencia'];
        $ingresosHogar = $data['ingresosHogar'];
        $total = $frecuenciaEscuela + $canastaBasica + $repitencia + $ingresosHogar + 1;
        $query = "UPDATE alumnos SET frecuenciaEscuela = ?, canastaBasica = ?, repitencia = ?, ingresosHogar = ?, prioridad = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$frecuenciaEscuela,$canastaBasica,$repitencia,$ingresosHogar,$total, $id]);
    }
}
