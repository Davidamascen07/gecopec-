<?php
require_once 'lib/Database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Encontrar usuário por email
    public function findUserByEmail($email) {
        try {
            $sql = 'SELECT * FROM usuarios WHERE email = :email AND status = "ativo"';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_OBJ);
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar usuário por email: " . $e->getMessage());
            return false;
        }
    }

    // Login de usuário
    public function login($email, $password) {
        $user = $this->findUserByEmail($email);

        if($user) {
            // Verificar senha usando hash
            if(password_verify($password, $user->senha)) {
                return $user;
            }
        }

        return false;
    }
    
    // Obter todos os usuários
    public function getAllUsers() {
        try {
            $sql = 'SELECT * FROM usuarios ORDER BY nome';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Erro ao buscar todos os usuários: " . $e->getMessage());
            return [];
        }
    }
    
    // Obter usuário pelo ID
    public function getUserById($id) {
        try {
            $sql = 'SELECT * FROM usuarios WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Erro ao buscar usuário por ID: " . $e->getMessage());
            return false;
        }
    }
    
    // Obter todos os professores
    public function getAllProfessores() {
        try {
            $sql = 'SELECT u.*, p.* 
                    FROM usuarios u 
                    JOIN professores p ON u.id = p.usuario_id
                    WHERE u.tipo = "professor" AND u.status = "ativo"
                    ORDER BY u.nome';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Erro ao buscar professores: " . $e->getMessage());
            return [];
        }
    }
    
    // Obter todos os coordenadores
    public function getAllCoordenadores() {
        try {
            $sql = 'SELECT * 
                    FROM usuarios 
                    WHERE tipo = "coordenador" AND status = "ativo"
                    ORDER BY nome';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Erro ao buscar coordenadores: " . $e->getMessage());
            return [];
        }
    }
    
    // Verificar se usuário existe
    public function verificarUsuarioExiste($id) {
        try {
            $sql = "SELECT * FROM usuarios WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao verificar usuário: " . $e->getMessage());
            return false;
        }
    }
    
    // Obter usuários disponíveis para serem professores
    public function getUsuariosDisponiveis() {
        try {
            $sql = "SELECT u.* 
                    FROM usuarios u 
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
    
    // Obter todos os usuários com filtros
    public function getAllUsuarios($filtros = []) {
        try {
            $sql = "SELECT u.* FROM usuarios u WHERE 1=1";
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
            error_log("Erro ao buscar usuários com filtros: " . $e->getMessage());
            return [];
        }
    }
}
?>
