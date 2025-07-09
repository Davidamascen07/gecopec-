<?php
require_once 'lib/Database.php';

class Usuario {
    private $db;
    private $table = 'usuarios';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getValidationRules() {
        return [
            'nome' => 'required|max:255',
            'email' => 'required|email|max:255',
            'senha' => 'required|min:6',
            'tipo' => 'required'
        ];
    }
    
    public function create($data) {
        try {
            // Hash da senha antes de salvar
            if (isset($data['senha'])) {
                $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
            }
            
            $sql = "INSERT INTO {$this->table} (nome, email, senha, tipo, status, created_at) 
                    VALUES (:nome, :email, :senha, :tipo, :status, NOW())";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (Exception $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        try {
            // Hash da senha se fornecida
            if (isset($data['senha']) && !empty($data['senha'])) {
                $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
            } else {
                // Remove senha do array se estiver vazia para não atualizar
                unset($data['senha']);
            }
            
            $setParts = [];
            foreach ($data as $key => $value) {
                $setParts[] = "{$key} = :{$key}";
            }
            
            $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts) . " WHERE id = :id";
            $data['id'] = $id;
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (Exception $e) {
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAllUsuarios($filtros = []) {
        try {
            $sql = "SELECT u.* FROM {$this->table} u WHERE 1=1";
            $params = [];
            
            if (!empty($filtros['tipo'])) {
                $sql .= " AND u.tipo = :tipo";
                $params['tipo'] = $filtros['tipo'];
            }
            
            if (!empty($filtros['status'])) {
                $sql .= " AND u.status = :status";
                $params['status'] = $filtros['status'];
            }
            
            // Excluir usuários que já são professores se solicitado
            if (isset($filtros['exclude_professores']) && $filtros['exclude_professores']) {
                $sql .= " AND u.id NOT IN (
                    SELECT DISTINCT p.usuario_id 
                    FROM professores p 
                    WHERE p.usuario_id IS NOT NULL
                )";
            }
            
            $sql .= " ORDER BY u.nome";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar usuários: " . $e->getMessage());
            return [];
        }
    }
    
    public function getUsuariosDisponiveis() {
        try {
            // Método específico para obter apenas usuários que podem ser professores
            $sql = "SELECT u.* 
                    FROM {$this->table} u 
                    LEFT JOIN professores p ON u.id = p.usuario_id 
                    WHERE u.status = 'ativo' 
                    AND p.usuario_id IS NULL 
                    ORDER BY u.nome";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar usuários disponíveis: " . $e->getMessage());
            return [];
        }
    }
    
    public function verificarUsuarioExiste($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao verificar usuário: " . $e->getMessage());
            return false;
        }
    }
    
    public function getUsuarioById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Erro ao buscar usuário por ID: " . $e->getMessage());
            return false;
        }
    }
    
    public function checkEmailExists($email, $excludeId = null) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
            $params = ['email' => $email];
            
            if ($excludeId) {
                $sql .= " AND id != :exclude_id";
                $params['exclude_id'] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("Erro ao verificar email: " . $e->getMessage());
            return false;
        }
    }
    
    public function getProfessores() {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE tipo = 'professor' AND status = 'ativo' ORDER BY nome";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar professores: " . $e->getMessage());
            return [];
        }
    }
    
    public function getCoordenadores() {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE tipo = 'coordenador' AND status = 'ativo' ORDER BY nome";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar coordenadores: " . $e->getMessage());
            return [];
        }
    }
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY nome";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar todos usuários: " . $e->getMessage());
            return [];
        }
    }
}
?>
