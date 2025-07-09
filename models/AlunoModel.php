<?php
// filepath: c:\xampp\htdocs\GECOPEC\models\AlunoModel.php
require_once 'BaseModel.php';

class AlunoModel extends BaseModel {
    protected $table = 'alunos';
    protected $fillable = ['nome', 'email', 'cpf', 'matricula', 'data_nascimento', 'telefone', 'endereco', 'curso_id', 'semestre_atual', 'status'];

    public function getValidationRules() {
        return [
            'nome' => 'required|max:255',
            'email' => 'required|email|max:255',
            'cpf' => 'required|max:14',
            'matricula' => 'required|max:50',
            'curso_id' => 'required|numeric',
            'semestre_atual' => 'numeric'
        ];
    }

    public function getAlunosWithCurso() {
        $sql = "SELECT a.*, c.nome as curso_nome 
                FROM {$this->table} a 
                LEFT JOIN cursos c ON a.curso_id = c.id 
                ORDER BY a.nome";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAlunosByCurso($cursoId) {
        $sql = "SELECT * FROM {$this->table} WHERE curso_id = :curso_id ORDER BY nome";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':curso_id', $cursoId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMatriculasAluno($alunoId) {
        $sql = "SELECT m.*, t.nome as turma_nome, d.nome as disciplina_nome, d.codigo
                FROM matriculas m
                INNER JOIN turmas t ON m.turma_id = t.id
                INNER JOIN disciplinas d ON t.disciplina_id = d.id
                WHERE m.aluno_id = :aluno_id
                ORDER BY t.ano DESC, t.semestre DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':aluno_id', $alunoId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getHistoricoEscolar($alunoId) {
        $sql = "SELECT 
                    d.codigo, d.nome as disciplina_nome, d.carga_horaria,
                    m.status, m.nota_final, m.frequencia,
                    t.semestre, t.ano
                FROM matriculas m
                INNER JOIN turmas t ON m.turma_id = t.id
                INNER JOIN disciplinas d ON t.disciplina_id = d.id
                WHERE m.aluno_id = :aluno_id
                ORDER BY t.ano, t.semestre";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':aluno_id', $alunoId);
        $stmt->execute();
        return $stmt->fetchAll();
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

    public function checkEmailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function checkCpfExists($cpf, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE cpf = :cpf";
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cpf', $cpf);
        if ($excludeId) {
            $stmt->bindParam(':exclude_id', $excludeId);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
?>