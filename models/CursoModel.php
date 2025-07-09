<?php
// filepath: c:\xampp\htdocs\GECOPEC\models\CursoModel.php
require_once 'BaseModel.php';

class CursoModel extends BaseModel {
    protected $table = 'cursos';
    protected $fillable = ['nome', 'carga_horaria', 'ementa', 'objetivos', 'status', 'coordenador_id'];

    public function getValidationRules() {
        return [
            'nome' => 'required|max:255',
            'carga_horaria' => 'required|numeric',
            'ementa' => 'max:5000',
            'objetivos' => 'max:5000'
        ];
    }

    public function getCursosWithCoordenador() {
        $sql = "SELECT c.*, u.nome as coordenador_nome 
                FROM {$this->table} c 
                LEFT JOIN usuarios u ON c.coordenador_id = u.id 
                ORDER BY c.nome";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDisciplinasByCurso($cursoId) {
        $sql = "SELECT * FROM disciplinas WHERE curso_id = :curso_id AND status = 'ativo' ORDER BY nome";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':curso_id', $cursoId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getEstatisticasCurso($cursoId) {
        $sql = "SELECT 
                    COUNT(DISTINCT d.id) as total_disciplinas,
                    COUNT(DISTINCT t.id) as total_turmas,
                    COUNT(DISTINCT m.aluno_id) as total_alunos,
                    SUM(d.carga_horaria) as carga_horaria_total
                FROM cursos c
                LEFT JOIN disciplinas d ON c.id = d.curso_id
                LEFT JOIN turmas t ON d.id = t.disciplina_id
                LEFT JOIN matriculas m ON t.id = m.turma_id
                WHERE c.id = :curso_id AND c.status = 'ativo'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':curso_id', $cursoId);
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>