<?php
// filepath: c:\xampp\htdocs\GECOPEC\models\PlanoEnsinoModel.php
require_once 'lib/Database.php';

class PlanoEnsinoModel {
    private $db;
    private $table = 'planos_ensino';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Método público para acessar a conexão do banco
    public function getConnection() {
        return $this->db;
    }
    
    public function create($dados) {
        try {
            error_log("PlanoEnsinoModel::create - Iniciando criação com dados: " . json_encode($dados));
            
            // Verificar se as entidades relacionadas existem
            $this->verificarEntidadesRelacionadas($dados);
            
            $sql = "INSERT INTO {$this->table} (
                disciplina_id, professor_id, curso_id, semestre, ano,
                objetivos_gerais, objetivos_especificos, metodologia,
                recursos_didaticos, avaliacao, bibliografia_basica,
                bibliografia_complementar, cronograma_detalhado,
                observacoes, status, created_at
            ) VALUES (
                :disciplina_id, :professor_id, :curso_id, :semestre, :ano,
                :objetivos_gerais, :objetivos_especificos, :metodologia,
                :recursos_didaticos, :avaliacao, :bibliografia_basica,
                :bibliografia_complementar, :cronograma_detalhado,
                :observacoes, :status, NOW()
            )";
            
            error_log("PlanoEnsinoModel::create - SQL preparado: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            
            if (!$stmt) {
                $errorInfo = $this->db->errorInfo();
                error_log("Erro ao preparar statement: " . json_encode($errorInfo));
                throw new Exception("Erro ao preparar query: " . $errorInfo[2]);
            }
            
            // Fazer bind dos parâmetros
            $stmt->bindParam(':disciplina_id', $dados['disciplina_id'], PDO::PARAM_INT);
            $stmt->bindParam(':professor_id', $dados['professor_id'], PDO::PARAM_INT);
            $stmt->bindParam(':curso_id', $dados['curso_id'], PDO::PARAM_INT);
            $stmt->bindParam(':semestre', $dados['semestre'], PDO::PARAM_INT);
            $stmt->bindParam(':ano', $dados['ano'], PDO::PARAM_INT);
            $stmt->bindParam(':objetivos_gerais', $dados['objetivos_gerais']);
            $stmt->bindParam(':objetivos_especificos', $dados['objetivos_especificos']);
            $stmt->bindParam(':metodologia', $dados['metodologia']);
            $stmt->bindParam(':recursos_didaticos', $dados['recursos_didaticos']);
            $stmt->bindParam(':avaliacao', $dados['avaliacao']);
            $stmt->bindParam(':bibliografia_basica', $dados['bibliografia_basica']);
            $stmt->bindParam(':bibliografia_complementar', $dados['bibliografia_complementar']);
            $stmt->bindParam(':cronograma_detalhado', $dados['cronograma_detalhado']);
            $stmt->bindParam(':observacoes', $dados['observacoes']);
            $stmt->bindParam(':status', $dados['status']);
            
            error_log("PlanoEnsinoModel::create - Executando query...");
            $result = $stmt->execute();
            
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("Erro ao executar query: " . json_encode($errorInfo));
                throw new Exception("Falha na execução da query: " . $errorInfo[2]);
            }
            
            $insertId = $this->db->lastInsertId();
            error_log("PlanoEnsinoModel::create - Plano criado com sucesso, ID: " . $insertId);
            
            return $insertId;
        } catch (Exception $e) {
            error_log("PlanoEnsinoModel::create - Erro: " . $e->getMessage());
            error_log("PlanoEnsinoModel::create - Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }
    
    // Método para verificar se as entidades relacionadas existem
    private function verificarEntidadesRelacionadas($dados) {
        // Verificar disciplina
        $sql = "SELECT COUNT(*) FROM disciplinas WHERE id = :id AND status = 'ativo'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $dados['disciplina_id']);
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            error_log("Disciplina ID {$dados['disciplina_id']} não existe ou está inativa");
            throw new Exception("Disciplina não encontrada ou inativa");
        }
        
        // Verificar professor
        $sql = "SELECT COUNT(*) FROM professores WHERE id = :id AND status = 'ativo'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $dados['professor_id']);
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            error_log("Professor ID {$dados['professor_id']} não existe ou está inativa");
            throw new Exception("Professor não encontrado ou inativo");
        }
        
        // Verificar curso
        $sql = "SELECT COUNT(*) FROM cursos WHERE id = :id AND status = 'ativo'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $dados['curso_id']);
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            error_log("Curso ID {$dados['curso_id']} não existe ou está inativo");
            throw new Exception("Curso não encontrado ou inativo");
        }
        
        error_log("Todas as entidades relacionadas verificadas com sucesso");
    }
    
    public function getPlanosCompletos() {
        try {
            $sql = "SELECT pe.*, 
                           d.nome as disciplina_nome, d.codigo as disciplina_codigo,
                           c.nome as curso_nome,
                           u.nome as professor_nome
                    FROM {$this->table} pe
                    LEFT JOIN disciplinas d ON pe.disciplina_id = d.id
                    LEFT JOIN cursos c ON pe.curso_id = c.id
                    LEFT JOIN professores prof ON pe.professor_id = prof.id
                    LEFT JOIN usuarios u ON prof.usuario_id = u.id
                    ORDER BY pe.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar planos de ensino: " . $e->getMessage());
            return [];
        }
    }
    
    public function getPlanosByProfessor($professorId) {
        try {
            $sql = "SELECT pe.*, 
                           d.nome as disciplina_nome, d.codigo as disciplina_codigo,
                           c.nome as curso_nome
                    FROM {$this->table} pe
                    LEFT JOIN disciplinas d ON pe.disciplina_id = d.id
                    LEFT JOIN cursos c ON pe.curso_id = c.id
                    WHERE pe.professor_id = :professor_id
                    ORDER BY pe.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':professor_id', $professorId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar planos do professor: " . $e->getMessage());
            return [];
        }
    }
    
    public function getById($id) {
        try {
            $sql = "SELECT pe.*, 
                           d.nome as disciplina_nome, d.codigo as disciplina_codigo,
                           c.nome as curso_nome,
                           u.nome as professor_nome
                    FROM {$this->table} pe
                    LEFT JOIN disciplinas d ON pe.disciplina_id = d.id
                    LEFT JOIN cursos c ON pe.curso_id = c.id
                    LEFT JOIN professores prof ON pe.professor_id = prof.id
                    LEFT JOIN usuarios u ON prof.usuario_id = u.id
                    WHERE pe.id = :id";
        
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar plano de ensino: " . $e->getMessage());
            return false;
        }
    }
    
    public function update($id, $dados) {
        try {
            $sql = "UPDATE {$this->table} SET
                    disciplina_id = :disciplina_id,
                    professor_id = :professor_id,
                    curso_id = :curso_id,
                    semestre = :semestre,
                    ano = :ano,
                    objetivos_gerais = :objetivos_gerais,
                    objetivos_especificos = :objetivos_especificos,
                    metodologia = :metodologia,
                    recursos_didaticos = :recursos_didaticos,
                    avaliacao = :avaliacao,
                    bibliografia_basica = :bibliografia_basica,
                    bibliografia_complementar = :bibliografia_complementar,
                    cronograma_detalhado = :cronograma_detalhado,
                    observacoes = :observacoes,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $dados['id'] = $id;
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($dados);
        } catch (Exception $e) {
            error_log("Erro ao atualizar plano de ensino: " . $e->getMessage());
            return false;
        }
    }
    
    // Método para atualizar apenas o status do plano
    public function updateStatus($id, $dados) {
        try {
            $sql = "UPDATE {$this->table} SET ";
            $params = [];
            $setParts = [];
            
            if (isset($dados['status'])) {
                $setParts[] = "status = :status";
                $params['status'] = $dados['status'];
            }
            
            if (isset($dados['aprovado_por'])) {
                $setParts[] = "aprovado_por = :aprovado_por";
                $params['aprovado_por'] = $dados['aprovado_por'];
            }
            
            if (isset($dados['data_aprovacao'])) {
                $setParts[] = "data_aprovacao = :data_aprovacao";
                $params['data_aprovacao'] = $dados['data_aprovacao'];
            }
            
            if (isset($dados['observacoes_rejeicao'])) {
                $setParts[] = "observacoes = :observacoes_rejeicao";
                $params['observacoes_rejeicao'] = $dados['observacoes_rejeicao'];
            }
            
            $setParts[] = "updated_at = NOW()";
            
            $sql .= implode(', ', $setParts);
            $sql .= " WHERE id = :id";
            $params['id'] = $id;
            
            error_log("PlanoEnsinoModel::updateStatus - SQL: " . $sql);
            error_log("PlanoEnsinoModel::updateStatus - Params: " . json_encode($params));
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("Erro ao atualizar status: " . json_encode($errorInfo));
                throw new Exception("Erro na execução da query: " . $errorInfo[2]);
            }
            
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("PlanoEnsinoModel::updateStatus - Erro: " . $e->getMessage());
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
            error_log("Erro ao excluir plano de ensino: " . $e->getMessage());
            return false;
        }
    }
    
    public function getPlanosByStatus($status) {
        try {
            $sql = "SELECT pe.*, 
                           d.nome as disciplina_nome, d.codigo as disciplina_codigo,
                           c.nome as curso_nome,
                           u.nome as professor_nome
                    FROM {$this->table} pe
                    LEFT JOIN disciplinas d ON pe.disciplina_id = d.id
                    LEFT JOIN cursos c ON pe.curso_id = c.id
                    LEFT JOIN professores prof ON pe.professor_id = prof.id
                    LEFT JOIN usuarios u ON prof.usuario_id = u.id
                    WHERE pe.status = :status
                    ORDER BY pe.updated_at DESC";
        
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar planos por status: " . $e->getMessage());
            return [];
        }
    }
}
?>