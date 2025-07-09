<?php
// filepath: c:\xampp\htdocs\GECOPEC\models\DisciplinaModel.php
require_once 'lib/Database.php';

class DisciplinaModel {
    private $db;
    private $table = 'disciplinas';
    protected $fillable = ['nome', 'codigo', 'carga_horaria', 'ementa', 'prerequisitos', 'curso_id', 'status'];

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getValidationRules() {
        return [
            'nome' => 'required|max:255',
            'codigo' => 'required|max:50',
            'carga_horaria' => 'required|numeric',
            'curso_id' => 'required|numeric',
            'ementa' => 'max:5000',
            'prerequisitos' => 'max:1000'
        ];
    }

    public function getDisciplinasWithCurso() {
        $sql = "SELECT d.*, c.nome as curso_nome 
                FROM {$this->table} d 
                LEFT JOIN cursos c ON d.curso_id = c.id 
                ORDER BY c.nome, d.nome";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTurmasByDisciplina($disciplinaId) {
        $sql = "SELECT t.*, 
                       u.nome as professor_nome,
                       p.matricula as professor_matricula
                FROM turmas t 
                LEFT JOIN professores p ON t.professor_id = p.id 
                LEFT JOIN usuarios u ON p.usuario_id = u.id
                WHERE t.disciplina_id = :disciplina_id 
                ORDER BY t.ano DESC, t.semestre DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':disciplina_id', $disciplinaId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function checkCodigoExists($codigo, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE codigo = :codigo";
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    
    public function countActiveItems() {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE status = 'ativo'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    public function countTotalItems() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY nome ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar disciplinas: " . $e->getMessage());
            return [];
        }
    }
    
    public function getDisciplinaById($id) {
        try {
            $sql = "SELECT d.*, c.nome as curso_nome 
                    FROM {$this->table} d 
                    LEFT JOIN cursos c ON d.curso_id = c.id 
                    WHERE d.id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar disciplina por ID: " . $e->getMessage());
            return false;
        }
    }
    
    public function getById($id) {
        return $this->getDisciplinaById($id);
    }
    
    public function create($dados) {
        try {
            $sql = "INSERT INTO {$this->table} (
                nome, codigo, carga_horaria, ementa, 
                prerequisitos, curso_id, status, created_at
            ) VALUES (
                :nome, :codigo, :carga_horaria, :ementa,
                :prerequisitos, :curso_id, :status, NOW()
            )";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($dados);
        } catch (Exception $e) {
            error_log("Erro ao criar disciplina: " . $e->getMessage());
            return false;
        }
    }
    
    public function update($id, $dados) {
        try {
            $sql = "UPDATE {$this->table} SET
                    nome = :nome,
                    codigo = :codigo,
                    carga_horaria = :carga_horaria,
                    ementa = :ementa,
                    prerequisitos = :prerequisitos,
                    curso_id = :curso_id,
                    status = :status,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $dados['id'] = $id;
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($dados);
        } catch (Exception $e) {
            error_log("Erro ao atualizar disciplina: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao excluir disciplina: " . $e->getMessage());
            return false;
        }
    }
    
    public function disciplinaExists($id) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("Erro ao verificar existência da disciplina: " . $e->getMessage());
            return false;
        }
    }
}
?>