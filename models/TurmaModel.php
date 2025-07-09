<?php
// filepath: c:\xampp\htdocs\GECOPEC\models\TurmaModel.php
require_once 'lib/Database.php';

class TurmaModel {
    private $db;
    private $table = 'turmas';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($dados) {
        try {
            // Validar semestre antes de inserir
            if (!isset($dados['semestre']) || $dados['semestre'] < 1 || $dados['semestre'] > 12) {
                throw new Exception("Semestre deve estar entre 1 e 12");
            }
            
            $sql = "INSERT INTO {$this->table} (
                nome, disciplina_id, professor_id, semestre, ano,
                vagas, horario, sala, status, created_at
            ) VALUES (
                :nome, :disciplina_id, :professor_id, :semestre, :ano,
                :vagas, :horario, :sala, :status, NOW()
            )";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($dados);
        } catch (Exception $e) {
            error_log("Erro ao criar turma: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY ano DESC, semestre DESC, nome ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar turmas: " . $e->getMessage());
            return [];
        }
    }
    
    public function getTurmasCompletas() {
        try {
            $sql = "SELECT t.*, 
                           d.nome as disciplina_nome, d.codigo as disciplina_codigo,
                           c.nome as curso_nome,
                           p.nome as professor_nome,
                           COUNT(m.id) as matriculados
                    FROM {$this->table} t
                    LEFT JOIN disciplinas d ON t.disciplina_id = d.id
                    LEFT JOIN cursos c ON d.curso_id = c.id
                    LEFT JOIN professores prof ON t.professor_id = prof.id
                    LEFT JOIN usuarios p ON prof.usuario_id = p.id
                    LEFT JOIN matriculas m ON t.id = m.turma_id AND m.status = 'matriculado'
                    GROUP BY t.id
                    ORDER BY t.ano DESC, t.semestre DESC, t.nome ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar turmas completas: " . $e->getMessage());
            return [];
        }
    }
    
    public function getTurmaCompleta($id) {
        try {
            $sql = "SELECT t.*, 
                           d.nome as disciplina_nome, d.codigo as disciplina_codigo,
                           c.nome as curso_nome,
                           p.nome as professor_nome,
                           COUNT(m.id) as matriculados
                    FROM {$this->table} t
                    LEFT JOIN disciplinas d ON t.disciplina_id = d.id
                    LEFT JOIN cursos c ON d.curso_id = c.id
                    LEFT JOIN professores prof ON t.professor_id = prof.id
                    LEFT JOIN usuarios p ON prof.usuario_id = p.id
                    LEFT JOIN matriculas m ON t.id = m.turma_id AND m.status = 'matriculado'
                    WHERE t.id = :id
                    GROUP BY t.id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar turma completa: " . $e->getMessage());
            return false;
        }
    }
    
    public function getById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar turma por ID: " . $e->getMessage());
            return false;
        }
    }
    
    public function update($id, $dados) {
        try {
            // Validar semestre antes de atualizar
            if (!isset($dados['semestre']) || $dados['semestre'] < 1 || $dados['semestre'] > 12) {
                throw new Exception("Semestre deve estar entre 1 e 12");
            }
            
            $sql = "UPDATE {$this->table} SET
                    nome = :nome,
                    disciplina_id = :disciplina_id,
                    professor_id = :professor_id,
                    semestre = :semestre,
                    ano = :ano,
                    vagas = :vagas,
                    horario = :horario,
                    sala = :sala,
                    status = :status,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $dados['id'] = $id;
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($dados);
        } catch (Exception $e) {
            error_log("Erro ao atualizar turma: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao excluir turma: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAlunosMatriculados($turmaId) {
        try {
            $sql = "SELECT a.*, u.nome, m.status as status_matricula, m.nota_final, m.frequencia, m.data_matricula
                    FROM alunos a
                    INNER JOIN usuarios u ON a.usuario_id = u.id
                    INNER JOIN matriculas m ON a.id = m.aluno_id
                    WHERE m.turma_id = :turma_id
                    ORDER BY u.nome";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':turma_id', $turmaId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar alunos matriculados: " . $e->getMessage());
            return [];
        }
    }

    public function matricularAluno($turmaId, $alunoId) {
        try {
            // Verificar se já está matriculado
            $sqlCheck = "SELECT COUNT(*) FROM matriculas WHERE turma_id = :turma_id AND aluno_id = :aluno_id";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->bindParam(':turma_id', $turmaId);
            $stmtCheck->bindParam(':aluno_id', $alunoId);
            $stmtCheck->execute();
            
            if ($stmtCheck->fetchColumn() > 0) {
                throw new Exception('Aluno já está matriculado nesta turma');
            }
            
            // Verificar vagas disponíveis
            $sqlVagas = "SELECT t.vagas, COUNT(m.id) as matriculados 
                         FROM turmas t 
                         LEFT JOIN matriculas m ON t.id = m.turma_id AND m.status = 'matriculado'
                         WHERE t.id = :turma_id 
                         GROUP BY t.id";
            $stmtVagas = $this->db->prepare($sqlVagas);
            $stmtVagas->bindParam(':turma_id', $turmaId);
            $stmtVagas->execute();
            $turmaInfo = $stmtVagas->fetch(PDO::FETCH_ASSOC);
            
            if ($turmaInfo && $turmaInfo['matriculados'] >= $turmaInfo['vagas']) {
                throw new Exception('Turma lotada');
            }
            
            $sql = "INSERT INTO matriculas (turma_id, aluno_id, status) VALUES (:turma_id, :aluno_id, 'matriculado')";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':turma_id', $turmaId);
            $stmt->bindParam(':aluno_id', $alunoId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao matricular aluno: " . $e->getMessage());
            return false;
        }
    }

    public function desmatricularAluno($turmaId, $alunoId) {
        try {
            $sql = "DELETE FROM matriculas WHERE turma_id = :turma_id AND aluno_id = :aluno_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':turma_id', $turmaId);
            $stmt->bindParam(':aluno_id', $alunoId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao desmatricular aluno: " . $e->getMessage());
            return false;
        }
    }
    
    // Método para verificar consultas (usado em validações)
    public function verificarConsulta($sql) {
        return $this->db->prepare($sql);
    }
    
    // Métodos de transação
    public function beginTransaction() {
        return $this->db->beginTransaction();
    }
    
    public function commit() {
        return $this->db->commit();
    }
    
    public function rollback() {
        return $this->db->rollback();
    }
}
?>