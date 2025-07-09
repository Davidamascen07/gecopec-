<?php
require_once 'lib/Database.php';

class Curso {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Obter todos os cursos com possível filtro
    public function getAllCursos($filtros = []) {
        try {
            $sql = "SELECT * FROM cursos WHERE 1=1";
            $params = [];
            
            if (!empty($filtros['busca'])) {
                $sql .= " AND (nome LIKE :busca OR ementa LIKE :busca)";
                $params['busca'] = '%' . $filtros['busca'] . '%';
            }
            
            if (!empty($filtros['status'])) {
                $sql .= " AND status = :status";
                $params['status'] = $filtros['status'];
            }
            
            $sql .= " ORDER BY nome ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Erro ao buscar cursos: " . $e->getMessage());
            return [];
        }
    }

    // Obter curso pelo ID
    public function getCursoById($id) {
        try {
            $sql = 'SELECT c.*, u.nome as coordenador_nome 
                    FROM cursos c 
                    LEFT JOIN usuarios u ON c.coordenador_id = u.id 
                    WHERE c.id = :id';
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Erro ao buscar curso por ID: " . $e->getMessage());
            return false;
        }
    }

    // Criar novo curso
    public function create($data) {
        try {
            $sql = "INSERT INTO cursos (nome, carga_horaria, ementa, objetivos, status, coordenador_id, created_at) 
                    VALUES (:nome, :carga_horaria, :ementa, :objetivos, :status, :coordenador_id, NOW())";
            
            $stmt = $this->db->prepare($sql);
            
            // Bind dos parâmetros
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':carga_horaria', $data['carga_horaria']);
            $stmt->bindParam(':ementa', $data['ementa']);
            $stmt->bindParam(':objetivos', $data['objetivos']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':coordenador_id', $data['coordenador_id']);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Erro ao criar curso: " . $e->getMessage());
            return false;
        }
    }

    // Atualizar curso
    public function update($data) {
        try {
            $sql = 'UPDATE cursos 
                    SET nome = :nome, 
                        carga_horaria = :carga_horaria, 
                        ementa = :ementa, 
                        objetivos = :objetivos, 
                        status = :status, 
                        coordenador_id = :coordenador_id,
                        updated_at = NOW()
                    WHERE id = :id';
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':carga_horaria', $data['carga_horaria']);
            $stmt->bindParam(':ementa', $data['ementa']);
            $stmt->bindParam(':objetivos', $data['objetivos']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':coordenador_id', !empty($data['coordenador_id']) ? $data['coordenador_id'] : null);
            $stmt->bindParam(':id', $data['id']);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao atualizar curso: " . $e->getMessage());
            return false;
        }
    }

    // Excluir curso
    public function delete($id) {
        try {
            $sql = 'DELETE FROM cursos WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao excluir curso: " . $e->getMessage());
            return false;
        }
    }

    // Obter cursos por coordenador
    public function getCursosByCoordenador($coordenadorId) {
        try {
            $sql = 'SELECT * FROM cursos WHERE coordenador_id = :coordenador_id AND status = "ativo"';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':coordenador_id', $coordenadorId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Erro ao buscar cursos por coordenador: " . $e->getMessage());
            return [];
        }
    }
    
    // Obter disciplinas de um curso
    public function getDisciplinasByCurso($cursoId) {
        try {
            $sql = 'SELECT * FROM disciplinas WHERE curso_id = :curso_id ORDER BY nome';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':curso_id', $cursoId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Erro ao buscar disciplinas do curso: " . $e->getMessage());
            return [];
        }
    }
    
    // Verificar se curso existe
    public function cursoExists($id) {
        try {
            $sql = 'SELECT id FROM cursos WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Erro ao verificar se curso existe: " . $e->getMessage());
            return false;
        }
    }
    
    // Método auxiliar para obter todos os cursos (compatibilidade)
    public function getAll() {
        return $this->getAllCursos();
    }
}
?>
