<?php
// filepath: c:\xampp\htdocs\GECOPEC\models\ProfessorModel.php
require_once 'BaseModel.php';
require_once 'lib/Database.php';

class ProfessorModel extends BaseModel {
    protected $table = 'professores';
    protected $fillable = ['usuario_id', 'matricula', 'data_nascimento', 'telefone', 'endereco', 'departamento', 'especializacao', 'lattes_url', 'status'];

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getValidationRules() {
        return [
            'usuario_id' => 'required|numeric',
            'matricula' => 'required|max:14',
            'departamento' => 'required|max:255',
            'telefone' => 'max:20'
        ];
    }

    public function getProfessoresWithUsuario() {
        $sql = "SELECT p.*, u.nome, u.email 
                FROM {$this->table} p 
                INNER JOIN usuarios u ON p.usuario_id = u.id 
                ORDER BY u.nome";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getTurmasProfessor($professorId) {
        $sql = "SELECT t.*, d.nome as disciplina_nome, d.codigo as disciplina_codigo
                FROM turmas t
                INNER JOIN disciplinas d ON t.disciplina_id = d.id
                WHERE t.professor_id = :professor_id
                ORDER BY t.ano DESC, t.semestre DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':professor_id', $professorId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getEstatisticasProfessor($professorId) {
        $sql = "SELECT 
                    COUNT(DISTINCT t.id) as total_turmas,
                    COUNT(DISTINCT d.id) as total_disciplinas,
                    COUNT(DISTINCT m.aluno_id) as total_alunos
                FROM professores p
                LEFT JOIN turmas t ON p.id = t.professor_id
                LEFT JOIN disciplinas d ON t.disciplina_id = d.id
                LEFT JOIN matriculas m ON t.id = m.turma_id
                WHERE p.id = :professor_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':professor_id', $professorId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function checkMatriculaExists($matricula, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE matricula = :matricula";
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

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByField($field, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = :value LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para verificar se usuário existe
    public function verificarUsuarioExiste($usuarioId) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE id = :usuario_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Override do método create simplificado
    public function create($data) {
        // Debug logs comentados
        // error_log("ProfessorModel::create chamado com dados: " . print_r($data, true));
        
        // Filtrar apenas campos permitidos
        $filteredData = [];
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $filteredData[$field] = $data[$field];
            }
        }
        
        // Debug logs comentados
        // error_log("Dados filtrados: " . print_r($filteredData, true));
        
        // Verificar se usuario_id é válido
        if (isset($filteredData['usuario_id'])) {
            $userExists = $this->verificarUsuarioExiste($filteredData['usuario_id']);
            
            // Debug logs comentados
            // error_log("Verificação de usuário - ID: " . $filteredData['usuario_id'] . ", Existe: " . ($userExists ? 'SIM' : 'NÃO'));
            
            if (!$userExists) {
                throw new Exception("Usuário com ID " . $filteredData['usuario_id'] . " não existe");
            }
        }
        
        try {
            return parent::create($filteredData);
        } catch (Exception $e) {
            // Debug logs comentados
            // error_log("Erro no ProfessorModel::create: " . $e->getMessage());
            throw $e;
        }
    }

    public function verificarConsulta($sql) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt;
    }
    
    public function beginTransaction() {
        return $this->db->beginTransaction();
    }
    
    public function commit() {
        return $this->db->commit();
    }
    
    public function rollback() {
        return $this->db->rollback();
    }
    
    public function createWithValidation($data) {
        // Debug logs comentados
        // error_log("ProfessorModel::createWithValidation chamado com dados: " . print_r($data, true));
        
        // Filtrar apenas campos permitidos
        $filteredData = [];
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $filteredData[$field] = $data[$field];
            }
        }
        
        // Debug logs comentados
        // error_log("Dados filtrados: " . print_r($filteredData, true));
        
        // Verificação final se usuario_id é válido
        if (isset($filteredData['usuario_id'])) {
            $userExists = $this->verificarUsuarioExiste($filteredData['usuario_id']);
            
            // Debug logs comentados
            // error_log("Verificação final de usuário - ID: " . $filteredData['usuario_id'] . ", Existe: " . ($userExists ? 'SIM' : 'NÃO'));
            
            if (!$userExists) {
                throw new Exception("Usuário com ID " . $filteredData['usuario_id'] . " não existe na verificação final");
            }
            
            // Verificar se já existe professor com este usuario_id
            $existingProf = $this->getByField('usuario_id', $filteredData['usuario_id']);
            if ($existingProf) {
                throw new Exception("Usuário com ID " . $filteredData['usuario_id'] . " já é professor");
            }
        }
        
        // Inserir dados manualmente para maior controle
        $sql = "INSERT INTO {$this->table} (usuario_id, matricula, data_nascimento, telefone, endereco, departamento, especializacao, lattes_url, status) 
                VALUES (:usuario_id, :matricula, :data_nascimento, :telefone, :endereco, :departamento, :especializacao, :lattes_url, :status)";
        
        $stmt = $this->db->prepare($sql);
        
        $params = [
            ':usuario_id' => $filteredData['usuario_id'],
            ':matricula' => $filteredData['matricula'],
            ':data_nascimento' => $filteredData['data_nascimento'],
            ':telefone' => $filteredData['telefone'] ?? '',
            ':endereco' => $filteredData['endereco'] ?? '',
            ':departamento' => $filteredData['departamento'],
            ':especializacao' => $filteredData['especializacao'] ?? '',
            ':lattes_url' => $filteredData['lattes_url'] ?? '',
            ':status' => $filteredData['status'] ?? 'ativo'
        ];
        
        // Debug logs comentados
        // error_log("SQL: " . $sql);
        // error_log("Parâmetros: " . print_r($params, true));
        
        try {
            $resultado = $stmt->execute($params);
            // Debug logs comentados
            // error_log("Resultado da execução: " . ($resultado ? 'SUCCESS' : 'FAILED'));
            
            if ($resultado) {
                $lastId = $this->db->lastInsertId();
                // Debug logs comentados
                // error_log("ID do professor criado: " . $lastId);
                return $lastId;
            }
            
            return false;
        } catch (Exception $e) {
            // Debug logs comentados
            // error_log("Erro na execução SQL: " . $e->getMessage());
            throw $e;
        }
    }

    public function getAllProfessores($filtros = []) {
        try {
            $sql = "SELECT p.*, u.nome, u.email 
                    FROM {$this->table} p
                    INNER JOIN usuarios u ON p.usuario_id = u.id
                    WHERE 1=1";
            
            $params = [];
            
            if (!empty($filtros['busca'])) {
                $sql .= " AND (u.nome LIKE :busca OR p.matricula LIKE :busca OR u.email LIKE :busca)";
                $params['busca'] = '%' . $filtros['busca'] . '%';
            }
            
            $sql .= " ORDER BY u.nome ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar professores: " . $e->getMessage());
            return [];
        }
    }
    
    // Adicionar o método getById que estava faltando
    public function getById($id) {
        try {
            $sql = "SELECT p.*, u.nome, u.email 
                    FROM {$this->table} p
                    INNER JOIN usuarios u ON p.usuario_id = u.id
                    WHERE p.id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar professor por ID: " . $e->getMessage());
            return false;
        }
    }
    
    // Método para verificar se professor existe
    public function professorExists($id) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("Erro ao verificar existência do professor: " . $e->getMessage());
            return false;
        }
    }
    
    // Método para buscar professor por usuario_id
    public function getByUsuarioId($usuarioId) {
        try {
            $sql = "SELECT p.*, u.nome, u.email 
                    FROM {$this->table} p
                    INNER JOIN usuarios u ON p.usuario_id = u.id
                    WHERE p.usuario_id = :usuario_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar professor por usuario_id: " . $e->getMessage());
            return false;
        }
    }
    
    // Override do método update para incluir joins necessários
    public function update($id, $data) {
        try {
            // Filtrar apenas campos permitidos
            $filteredData = [];
            foreach ($this->fillable as $field) {
                if (isset($data[$field])) {
                    $filteredData[$field] = $data[$field];
                }
            }
            
            $setParts = [];
            foreach ($filteredData as $key => $value) {
                $setParts[] = "{$key} = :{$key}";
            }
            
            $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts) . ", updated_at = NOW() WHERE id = :id";
            $filteredData['id'] = $id;
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($filteredData);
        } catch (Exception $e) {
            error_log("Erro ao atualizar professor: " . $e->getMessage());
            return false;
        }
    }
    
    // Override do método delete para soft delete
    public function delete($id) {
        try {
            $sql = "UPDATE {$this->table} SET status = 'inativo', updated_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao excluir professor: " . $e->getMessage());
            return false;
        }
    }
    
    // Método para exclusão permanente (caso necessário)
    public function forceDelete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao excluir professor permanentemente: " . $e->getMessage());
            return false;
        }
    }
}
?>