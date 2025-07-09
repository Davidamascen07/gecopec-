<?php
require_once 'lib/Database.php';

class DashboardModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Contar disciplinas ativas
    public function countDisciplinas() {
        try {
            $sql = "SELECT COUNT(*) FROM disciplinas WHERE status = 'ativo'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erro ao contar disciplinas: " . $e->getMessage());
            return 0;
        }
    }
    
    // Contar planos de ensino pendentes
    public function countPlanosPendentes() {
        try {
            $sql = "SELECT COUNT(*) FROM planos_ensino WHERE status = 'pendente'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erro ao contar planos pendentes: " . $e->getMessage());
            return 0;
        }
    }
    
    // Contar planos de ensino aprovados
    public function countPlanosAprovados() {
        try {
            $sql = "SELECT COUNT(*) FROM planos_ensino WHERE status = 'aprovado'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erro ao contar planos aprovados: " . $e->getMessage());
            return 0;
        }
    }
    
    // Contar próximos encontros
    public function countProximosEncontros() {
        try {
            $hoje = date('Y-m-d');
            $proximaSemana = date('Y-m-d', strtotime('+7 days'));
            
            $sql = "SELECT COUNT(*) FROM cronogramas WHERE data_encontro BETWEEN ? AND ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$hoje, $proximaSemana]);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            error_log("Erro ao contar próximos encontros: " . $e->getMessage());
            return 0;
        }
    }
    
    // Obter atividades recentes
    public function getAtividadesRecentes() {
        try {
            // Query simplificada para evitar problemas de tabelas que podem não existir
            $sql = "
                SELECT 
                    'plano' as tipo,
                    pe.id,
                    COALESCE(d.nome, 'Disciplina') as titulo,
                    CASE 
                        WHEN pe.status = 'pendente' THEN 'Plano de Ensino criado'
                        WHEN pe.status = 'aprovado' THEN 'Plano de Ensino aprovado'
                        ELSE 'Plano de Ensino atualizado'
                    END as descricao,
                    COALESCE(u.nome, 'Professor') as autor,
                    pe.updated_at as data
                FROM planos_ensino pe
                LEFT JOIN disciplinas d ON pe.disciplina_id = d.id
                LEFT JOIN professores p ON pe.professor_id = p.id
                LEFT JOIN usuarios u ON p.usuario_id = u.id
                ORDER BY pe.updated_at DESC
                LIMIT 3
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar atividades recentes: " . $e->getMessage());
            return [];
        }
    }
    
    // Obter planos pendentes de aprovação
    public function getPlanosPendentes() {
        try {
            $sql = "SELECT pe.id, 
                           COALESCE(d.nome, 'Disciplina') as disciplina_nome, 
                           COALESCE(u.nome, 'Professor') as professor_nome 
                    FROM planos_ensino pe
                    LEFT JOIN disciplinas d ON pe.disciplina_id = d.id
                    LEFT JOIN professores p ON pe.professor_id = p.id
                    LEFT JOIN usuarios u ON p.usuario_id = u.id
                    WHERE pe.status = 'pendente'
                    ORDER BY pe.created_at DESC
                    LIMIT 5";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar planos pendentes: " . $e->getMessage());
            return [];
        }
    }
    
    // Obter próximos eventos
    public function getProximosEventos() {
        try {
            $hoje = date('Y-m-d');
            $proximosDias = date('Y-m-d', strtotime('+14 days'));
            
            $sql = "SELECT c.id, 
                           COALESCE(c.assunto, 'Encontro') as assunto, 
                           c.data_encontro, 
                           COALESCE(d.nome, 'Disciplina') as disciplina_nome
                    FROM cronogramas c
                    LEFT JOIN planos_ensino pe ON c.plano_ensino_id = pe.id
                    LEFT JOIN disciplinas d ON pe.disciplina_id = d.id
                    WHERE c.data_encontro BETWEEN ? AND ?
                    ORDER BY c.data_encontro ASC
                    LIMIT 5";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$hoje, $proximosDias]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar próximos eventos: " . $e->getMessage());
            return [];
        }
    }
}
?>
