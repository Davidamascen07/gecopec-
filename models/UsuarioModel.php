<?php
// filepath: c:\xampp\htdocs\GECOPEC\models\UsuarioModel.php
require_once 'BaseModel.php';

class UsuarioModel extends BaseModel {
    protected $table = 'usuarios';
    protected $fillable = ['nome', 'email', 'senha', 'tipo', 'status'];
    protected $hidden = ['senha'];

    public function getValidationRules() {
        return [
            'nome' => 'required|max:255',
            'email' => 'required|email|max:255',
            'senha' => 'required|min:6',
            'tipo' => 'required'
        ];
    }

    public function create($data) {
        // Hash da senha antes de salvar
        if (isset($data['senha'])) {
            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }
        
        return parent::create($data);
    }

    public function update($id, $data) {
        // Hash da senha se fornecida
        if (isset($data['senha']) && !empty($data['senha'])) {
            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        } else {
            // Remove senha do array se estiver vazia para não atualizar
            unset($data['senha']);
        }
        
        return parent::update($id, $data);
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
    
    public function login($email, $senha) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email AND status = 'ativo'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($senha, $user['senha'])) {
            return $user;
        }
        
        return false;
    }
    
    public function getProfessores() {
        $sql = "SELECT * FROM {$this->table} WHERE tipo = 'professor' AND status = 'ativo' ORDER BY nome";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getCoordenadores() {
        $sql = "SELECT * FROM {$this->table} WHERE tipo = 'coordenador' AND status = 'ativo' ORDER BY nome";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>