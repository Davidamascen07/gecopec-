<?php
// filepath: c:\xampp\htdocs\GECOPEC\models\BaseModel.php
require_once __DIR__ . '/../lib/database.php';

abstract class BaseModel {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll($conditions = [], $orderBy = '', $limit = '') {
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $field => $value) {
                $whereClause[] = "{$field} = :{$field}";
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                $stmt->bindParam(":{$field}", $value);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($data) {
        $filteredData = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $filteredData[$key] = $value;
            }
        }

        $fields = implode(', ', array_keys($filteredData));
        $placeholders = ':' . implode(', :', array_keys($filteredData));
        
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        foreach ($filteredData as $key => $value) {
            $stmt->bindParam(":{$key}", $value);
        }
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    public function update($id, $data) {
        $filteredData = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $filteredData[$key] = $value;
            }
        }

        $setParts = [];
        foreach ($filteredData as $key => $value) {
            $setParts[] = "{$key} = :{$key}";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts) . " WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        
        foreach ($filteredData as $key => $value) {
            $stmt->bindParam(":{$key}", $value);
        }
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "UPDATE {$this->table} SET status = 'inativo' WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function forceDelete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function search($term) {
        // Implementação básica de busca
        $sql = "SELECT * FROM {$this->table} WHERE ";
        $searchFields = [];
        
        foreach ($this->fillable as $field) {
            if (!in_array($field, ['senha', 'password'])) {
                $searchFields[] = "{$field} LIKE :term";
            }
        }
        
        $sql .= implode(' OR ', $searchFields);
        
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%{$term}%";
        $stmt->bindParam(':term', $searchTerm);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $ruleArray = explode('|', $rule);
            
            foreach ($ruleArray as $singleRule) {
                if ($singleRule === 'required' && empty($data[$field])) {
                    $errors[$field][] = "O campo {$field} é obrigatório";
                }
                
                if (strpos($singleRule, 'max:') === 0) {
                    $maxLength = intval(substr($singleRule, 4));
                    if (isset($data[$field]) && strlen($data[$field]) > $maxLength) {
                        $errors[$field][] = "O campo {$field} deve ter no máximo {$maxLength} caracteres";
                    }
                }
                
                if (strpos($singleRule, 'min:') === 0) {
                    $minLength = intval(substr($singleRule, 4));
                    if (isset($data[$field]) && strlen($data[$field]) < $minLength) {
                        $errors[$field][] = "O campo {$field} deve ter no mínimo {$minLength} caracteres";
                    }
                }
                
                if ($singleRule === 'email' && isset($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "O campo {$field} deve ser um email válido";
                }
                
                if ($singleRule === 'numeric' && isset($data[$field]) && !is_numeric($data[$field])) {
                    $errors[$field][] = "O campo {$field} deve ser numérico";
                }
            }
        }
        
        return $errors;
    }

    abstract public function getValidationRules();
}
?>