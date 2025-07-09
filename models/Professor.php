<?php
require_once 'config/config.php';

class Professor {
    private $db;
    
    public function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch(PDOException $e) {
            throw new Exception("Erro de conexão: " . $e->getMessage());
        }
    }
    
    public function getAllProfessores($filtros = []) {
        $sql = "SELECT p.*, u.nome, u.email, u.status as usuario_status
                FROM professores p 
                INNER JOIN usuarios u ON p.usuario_id = u.id";
        
        $conditions = [];
        $params = [];
        
        if (!empty($filtros['busca'])) {
            $conditions[] = "(u.nome LIKE :busca OR p.matricula LIKE :busca OR u.email LIKE :busca)";
            $params[':busca'] = '%' . $filtros['busca'] . '%';
        }
        
        if (!empty($filtros['departamento'])) {
            $conditions[] = "p.departamento = :departamento";
            $params[':departamento'] = $filtros['departamento'];
        }
        
        if (!empty($filtros['status'])) {
            $conditions[] = "p.status = :status";
            $params[':status'] = $filtros['status'];
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY u.nome";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch(PDOException $e) {
            throw new Exception("Erro ao buscar professores: " . $e->getMessage());
        }
    }
    
    public function getProfessorById($id) {
        $sql = "SELECT p.*, u.nome, u.email, u.status as usuario_status
                FROM professores p 
                INNER JOIN usuarios u ON p.usuario_id = u.id 
                WHERE p.id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch(PDOException $e) {
            throw new Exception("Erro ao buscar professor: " . $e->getMessage());
        }
    }
    
    public function create($data) {
        // Validar dados
        $errors = $this->validate($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Verificar se matrícula já existe
        if ($this->matriculaExists($data['matricula'])) {
            return ['success' => false, 'errors' => ['Matrícula já existe']];
        }
        
        // Verificar se usuário já é professor
        if ($this->usuarioJaEProfessor($data['usuario_id'])) {
            return ['success' => false, 'errors' => ['Usuário já é cadastrado como professor']];
        }
        
        $sql = "INSERT INTO professores (usuario_id, matricula, data_nascimento, telefone, endereco, departamento, especializacao, lattes_url, status) 
                VALUES (:usuario_id, :matricula, :data_nascimento, :telefone, :endereco, :departamento, :especializacao, :lattes_url, :status)";
        
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                ':usuario_id' => $data['usuario_id'],
                ':matricula' => $data['matricula'],
                ':data_nascimento' => !empty($data['data_nascimento']) ? $data['data_nascimento'] : null,
                ':telefone' => $data['telefone'] ?? null,
                ':endereco' => $data['endereco'] ?? null,
                ':departamento' => $data['departamento'],
                ':especializacao' => $data['especializacao'] ?? null,
                ':lattes_url' => $data['lattes_url'] ?? null,
                ':status' => $data['status'] ?? 'ativo'
            ]);
            
            return $result;
        } catch(PDOException $e) {
            throw new Exception("Erro ao criar professor: " . $e->getMessage());
        }
    }
    
    public function update($data) {
        // Validar dados
        $errors = $this->validate($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Verificar se matrícula já existe (excluindo o próprio professor)
        if ($this->matriculaExists($data['matricula'], $data['id'])) {
            return ['success' => false, 'errors' => ['Matrícula já existe']];
        }
        
        $sql = "UPDATE professores SET 
                usuario_id = :usuario_id,
                matricula = :matricula, 
                data_nascimento = :data_nascimento,
                telefone = :telefone,
                endereco = :endereco,
                departamento = :departamento,
                especializacao = :especializacao,
                lattes_url = :lattes_url,
                status = :status,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                ':id' => $data['id'],
                ':usuario_id' => $data['usuario_id'],
                ':matricula' => $data['matricula'],
                ':data_nascimento' => !empty($data['data_nascimento']) ? $data['data_nascimento'] : null,
                ':telefone' => $data['telefone'] ?? null,
                ':endereco' => $data['endereco'] ?? null,
                ':departamento' => $data['departamento'],
                ':especializacao' => $data['especializacao'] ?? null,
                ':lattes_url' => $data['lattes_url'] ?? null,
                ':status' => $data['status'] ?? 'ativo'
            ]);
            
            return $result;
        } catch(PDOException $e) {
            throw new Exception("Erro ao atualizar professor: " . $e->getMessage());
        }
    }
    
    public function delete($id) {
        // Verificar se professor tem turmas vinculadas
        if ($this->professorTemTurmas($id)) {
            return false;
        }
        
        $sql = "DELETE FROM professores WHERE id = :id";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch(PDOException $e) {
            throw new Exception("Erro ao excluir professor: " . $e->getMessage());
        }
    }
    
    public function professorExists($id) {
        $sql = "SELECT COUNT(*) FROM professores WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    
    public function matriculaExists($matricula, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM professores WHERE matricula = :matricula";
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':matricula', $matricula);
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    
    public function usuarioJaEProfessor($usuarioId, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM professores WHERE usuario_id = :usuario_id";
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId);
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    
    public function professorTemTurmas($professorId) {
        $sql = "SELECT COUNT(*) FROM turmas WHERE professor_id = :professor_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':professor_id', $professorId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    
    public function getTurmasProfessor($professorId) {
        $sql = "SELECT t.*, d.nome as disciplina_nome, d.codigo as disciplina_codigo, c.nome as curso_nome
                FROM turmas t
                INNER JOIN disciplinas d ON t.disciplina_id = d.id
                INNER JOIN cursos c ON d.curso_id = c.id
                WHERE t.professor_id = :professor_id
                ORDER BY t.ano DESC, t.semestre DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':professor_id', $professorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getDepartamentos() {
        $sql = "SELECT DISTINCT departamento FROM professores WHERE departamento IS NOT NULL AND departamento != '' ORDER BY departamento";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    private function validate($data) {
        $errors = [];
        
        if (empty($data['usuario_id'])) {
            $errors[] = 'Usuário é obrigatório';
        }
        
        if (empty($data['matricula'])) {
            $errors[] = 'Matrícula é obrigatória';
        } elseif (strlen($data['matricula']) > 14) {
            $errors[] = 'Matrícula deve ter no máximo 14 caracteres';
        }
        
        if (empty($data['departamento'])) {
            $errors[] = 'Departamento é obrigatório';
        } elseif (strlen($data['departamento']) > 255) {
            $errors[] = 'Departamento deve ter no máximo 255 caracteres';
        }
        
        if (!empty($data['telefone']) && strlen($data['telefone']) > 20) {
            $errors[] = 'Telefone deve ter no máximo 20 caracteres';
        }
        
        if (!empty($data['lattes_url']) && strlen($data['lattes_url']) > 500) {
            $errors[] = 'URL do Lattes deve ter no máximo 500 caracteres';
        }
        
        if (!empty($data['data_nascimento'])) {
            $data_nascimento = DateTime::createFromFormat('Y-m-d', $data['data_nascimento']);
            if (!$data_nascimento) {
                $errors[] = 'Data de nascimento inválida';
            }
        }
        
        return $errors;
    }
}
?>
