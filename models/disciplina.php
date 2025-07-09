<?php
require_once 'lib/Database.php';

class Disciplina {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obter todas as disciplinas com possível filtro
    public function getAllDisciplinas($filtros = []) {
        try {
            $sql = 'SELECT d.*, c.nome as curso_nome 
                    FROM disciplinas d 
                    LEFT JOIN cursos c ON d.curso_id = c.id 
                    WHERE 1=1';
            
            $params = [];
            
            // Aplicar filtros se houver
            if (!empty($filtros['busca'])) {
                $sql .= ' AND (d.nome LIKE :busca OR d.codigo LIKE :busca)';
                $params['busca'] = '%' . $filtros['busca'] . '%';
            }
            
            if (!empty($filtros['curso_id'])) {
                $sql .= ' AND d.curso_id = :curso_id';
                $params['curso_id'] = $filtros['curso_id'];
            }
            
            if (!empty($filtros['status'])) {
                $sql .= ' AND d.status = :status';
                $params['status'] = $filtros['status'];
            }
            
            $sql .= ' ORDER BY c.nome, d.nome';
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Erro ao buscar disciplinas: " . $e->getMessage());
            return [];
        }
    }

    // Obter disciplina pelo ID
    public function getDisciplinaById($id) {
        try {
            $sql = 'SELECT d.*, c.nome as curso_nome 
                    FROM disciplinas d 
                    LEFT JOIN cursos c ON d.curso_id = c.id 
                    WHERE d.id = :id';
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Erro ao buscar disciplina por ID: " . $e->getMessage());
            return false;
        }
    }

    // Obter disciplinas por curso (método do DisciplinaModel)
    public function getDisciplinasByCurso($cursoId) {
        try {
            $sql = 'SELECT * FROM disciplinas WHERE curso_id = :curso_id ORDER BY nome';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':curso_id', $cursoId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Erro ao buscar disciplinas por curso: " . $e->getMessage());
            return [];
        }
    }

    // Criar nova disciplina com validação
    public function create($data) {
        try {
            // Validações básicas
            $errors = $this->validateData($data);
            if (!empty($errors)) {
                return ['success' => false, 'errors' => $errors];
            }

            $sql = 'INSERT INTO disciplinas (nome, codigo, carga_horaria, ementa, prerequisitos, curso_id, status) 
                    VALUES (:nome, :codigo, :carga_horaria, :ementa, :prerequisitos, :curso_id, :status)';
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':codigo', $data['codigo']);
            $stmt->bindParam(':carga_horaria', $data['carga_horaria']);
            $stmt->bindParam(':ementa', $data['ementa']);
            $stmt->bindParam(':prerequisitos', $data['prerequisitos']);
            $stmt->bindParam(':curso_id', $data['curso_id']);
            $stmt->bindParam(':status', $data['status']);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log("Erro ao criar disciplina: " . $e->getMessage());
            return false;
        }
    }

    // Atualizar disciplina com validação
    public function update($data) {
        try {
            // Validações básicas
            $errors = $this->validateData($data, true);
            if (!empty($errors)) {
                return ['success' => false, 'errors' => $errors];
            }

            $sql = 'UPDATE disciplinas 
                    SET nome = :nome, 
                        codigo = :codigo, 
                        carga_horaria = :carga_horaria, 
                        ementa = :ementa, 
                        prerequisitos = :prerequisitos, 
                        curso_id = :curso_id, 
                        status = :status 
                    WHERE id = :id';
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nome', $data['nome']);
            $stmt->bindParam(':codigo', $data['codigo']);
            $stmt->bindParam(':carga_horaria', $data['carga_horaria']);
            $stmt->bindParam(':ementa', $data['ementa']);
            $stmt->bindParam(':prerequisitos', $data['prerequisitos']);
            $stmt->bindParam(':curso_id', $data['curso_id']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':id', $data['id']);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao atualizar disciplina: " . $e->getMessage());
            return false;
        }
    }

    // Excluir disciplina
    public function delete($id) {
        try {
            $sql = 'DELETE FROM disciplinas WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao excluir disciplina: " . $e->getMessage());
            return false;
        }
    }

    // Obter turmas de uma disciplina (método específico)
    public function getTurmasByDisciplina($disciplinaId) {
        try {
            $sql = 'SELECT t.*, 
                           u.nome as professor_nome,
                           p.matricula as professor_matricula
                    FROM turmas t 
                    LEFT JOIN professores p ON t.professor_id = p.id 
                    LEFT JOIN usuarios u ON p.usuario_id = u.id
                    WHERE t.disciplina_id = :disciplina_id 
                    ORDER BY t.ano DESC, t.semestre DESC';
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':disciplina_id', $disciplinaId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Erro ao buscar turmas da disciplina: " . $e->getMessage());
            return [];
        }
    }
    
    // Verificar se disciplina existe
    public function disciplinaExists($id) {
        try {
            $sql = 'SELECT id FROM disciplinas WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Erro ao verificar se disciplina existe: " . $e->getMessage());
            return false;
        }
    }
    
    // Verificar se código existe (melhorado do DisciplinaModel)
    public function codigoExists($codigo, $excludeId = null) {
        try {
            $sql = 'SELECT id FROM disciplinas WHERE codigo = :codigo';
            $params = ['codigo' => $codigo];
            
            if ($excludeId) {
                $sql .= ' AND id != :exclude_id';
                $params['exclude_id'] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Erro ao verificar código da disciplina: " . $e->getMessage());
            return false;
        }
    }

    // Método de validação inspirado no DisciplinaModel
    private function validateData($data, $isUpdate = false) {
        $errors = [];

        // Nome é obrigatório
        if (empty($data['nome'])) {
            $errors[] = 'O nome da disciplina é obrigatório';
        } elseif (strlen($data['nome']) > 255) {
            $errors[] = 'O nome da disciplina não pode ter mais de 255 caracteres';
        }

        // Código é obrigatório e único
        if (empty($data['codigo'])) {
            $errors[] = 'O código da disciplina é obrigatório';
        } elseif (strlen($data['codigo']) > 50) {
            $errors[] = 'O código da disciplina não pode ter mais de 50 caracteres';
        } else {
            // Verificar se código já existe
            $excludeId = $isUpdate ? $data['id'] : null;
            if ($this->codigoExists($data['codigo'], $excludeId)) {
                $errors[] = 'Este código de disciplina já existe';
            }
        }

        // Carga horária é obrigatória e numérica
        if (!isset($data['carga_horaria']) || !is_numeric($data['carga_horaria']) || $data['carga_horaria'] <= 0) {
            $errors[] = 'A carga horária deve ser um número positivo';
        }

        // Curso é obrigatório
        if (empty($data['curso_id']) || !is_numeric($data['curso_id'])) {
            $errors[] = 'O curso é obrigatório';
        }

        // Validar tamanho da ementa
        if (!empty($data['ementa']) && strlen($data['ementa']) > 5000) {
            $errors[] = 'A ementa não pode ter mais de 5000 caracteres';
        }

        // Validar tamanho dos pré-requisitos
        if (!empty($data['prerequisitos']) && strlen($data['prerequisitos']) > 1000) {
            $errors[] = 'Os pré-requisitos não podem ter mais de 1000 caracteres';
        }

        return $errors;
    }
}
?>
