<?php
// filepath: c:\xampp\htdocs\GECOPEC\models\CronogramaModel.php
require_once 'lib/Database.php';

class CronogramaModel {
    private $db;
    private $table = 'cronogramas';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Criar novo cronograma (encontro)
    public function create($dados) {
        try {
            $sql = "INSERT INTO {$this->table} (
                plano_ensino_id, encontro_numero, data_encontro, 
                assunto, conteudo, atividade, metodologia, recursos, observacoes
            ) VALUES (
                :plano_ensino_id, :encontro_numero, :data_encontro,
                :assunto, :conteudo, :atividade, :metodologia, :recursos, :observacoes
            )";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($dados);
        } catch (Exception $e) {
            error_log("Erro ao criar cronograma: " . $e->getMessage());
            return false;
        }
    }
    
    // Obter todos os cronogramas com dados relacionados
    public function getCronogramasCompletos() {
        try {
            $sql = "SELECT c.*, 
                           pe.disciplina_id, pe.professor_id, pe.curso_id,
                           d.nome as disciplina_nome, d.codigo as disciplina_codigo,
                           cur.nome as curso_nome,
                           u.nome as professor_nome
                    FROM {$this->table} c
                    LEFT JOIN planos_ensino pe ON c.plano_ensino_id = pe.id
                    LEFT JOIN disciplinas d ON pe.disciplina_id = d.id
                    LEFT JOIN cursos cur ON pe.curso_id = cur.id
                    LEFT JOIN professores p ON pe.professor_id = p.id
                    LEFT JOIN usuarios u ON p.usuario_id = u.id
                    ORDER BY c.data_encontro ASC, c.encontro_numero ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar cronogramas: " . $e->getMessage());
            return [];
        }
    }
    
    // Obter cronogramas por plano de ensino
    public function getCronogramasByPlano($planoEnsinoId) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE plano_ensino_id = :plano_ensino_id 
                    ORDER BY encontro_numero ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':plano_ensino_id', $planoEnsinoId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar cronogramas por plano: " . $e->getMessage());
            return [];
        }
    }
    
    // Obter cronograma por ID
    public function getById($id) {
        try {
            $sql = "SELECT c.*, 
                           pe.disciplina_id, pe.professor_id, pe.curso_id,
                           d.nome as disciplina_nome, d.codigo as disciplina_codigo,
                           cur.nome as curso_nome,
                           u.nome as professor_nome
                    FROM {$this->table} c
                    LEFT JOIN planos_ensino pe ON c.plano_ensino_id = pe.id
                    LEFT JOIN disciplinas d ON pe.disciplina_id = d.id
                    LEFT JOIN cursos cur ON pe.curso_id = cur.id
                    LEFT JOIN professores p ON pe.professor_id = p.id
                    LEFT JOIN usuarios u ON p.usuario_id = u.id
                    WHERE c.id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar cronograma por ID: " . $e->getMessage());
            return false;
        }
    }
    
    // Atualizar cronograma
    public function update($id, $dados) {
        try {
            $sql = "UPDATE {$this->table} SET
                    plano_ensino_id = :plano_ensino_id,
                    encontro_numero = :encontro_numero,
                    data_encontro = :data_encontro,
                    assunto = :assunto,
                    conteudo = :conteudo,
                    atividade = :atividade,
                    metodologia = :metodologia,
                    recursos = :recursos,
                    observacoes = :observacoes,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $dados['id'] = $id;
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($dados);
        } catch (Exception $e) {
            error_log("Erro ao atualizar cronograma: " . $e->getMessage());
            return false;
        }
    }
    
    // Excluir cronograma
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao excluir cronograma: " . $e->getMessage());
            return false;
        }
    }
    
    // Criar cronograma completo do semestre
    public function createCronogramaCompleto($planoEnsinoId, $encontros) {
        try {
            $this->db->beginTransaction();
            
            // Remover cronogramas existentes do plano
            $sqlDelete = "DELETE FROM {$this->table} WHERE plano_ensino_id = :plano_ensino_id";
            $stmtDelete = $this->db->prepare($sqlDelete);
            $stmtDelete->bindParam(':plano_ensino_id', $planoEnsinoId);
            $stmtDelete->execute();
            
            // Inserir novos encontros
            foreach ($encontros as $encontro) {
                $encontro['plano_ensino_id'] = $planoEnsinoId;
                if (!$this->create($encontro)) {
                    throw new Exception("Erro ao inserir encontro número " . $encontro['encontro_numero']);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Erro ao criar cronograma completo: " . $e->getMessage());
            return false;
        }
    }
    
    // Obter estatísticas do cronograma
    public function getEstatisticasByPlano($planoEnsinoId) {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_encontros,
                        COUNT(CASE WHEN assunto LIKE '%AP%' OR assunto LIKE '%Avaliação%' THEN 1 END) as avaliacoes,
                        COUNT(CASE WHEN assunto LIKE '%Feriado%' THEN 1 END) as feriados,
                        COUNT(CASE WHEN assunto LIKE '%Revisão%' THEN 1 END) as revisoes
                    FROM {$this->table} 
                    WHERE plano_ensino_id = :plano_ensino_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':plano_ensino_id', $planoEnsinoId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar estatísticas: " . $e->getMessage());
            return [];
        }
    }
}
?>