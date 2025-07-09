<?php
// filepath: c:\xampp\htdocs\GECOPEC\controllers\usuarioController.php
require_once 'BaseController.php';
require_once '../models/UsuarioModel.php';

class UsuarioController extends BaseController {
    protected function initModel() {
        $this->model = new UsuarioModel();
    }

    public function store($data) {
        try {
            // Verificar se o email já existe
            if ($this->model->checkEmailExists($data['email'])) {
                $this->sendResponse(['error' => 'Email já está em uso'], 422);
                return;
            }

            // Validar dados
            $errors = $this->model->validate($data, $this->model->getValidationRules());
            
            if (!empty($errors)) {
                $this->sendResponse(['error' => 'Dados inválidos', 'validation_errors' => $errors], 422);
                return;
            }
            
            $id = $this->model->create($data);
            
            if ($id) {
                $newRecord = $this->model->findById($id);
                $this->sendResponse(['success' => true, 'message' => 'Usuário criado com sucesso', 'data' => $newRecord], 201);
            } else {
                $this->sendResponse(['error' => 'Erro ao criar usuário'], 500);
            }
        } catch (Exception $e) {
            $this->sendResponse(['error' => 'Erro ao criar usuário: ' . $e->getMessage()], 500);
        }
    }

    public function update($id, $data) {
        try {
            if (!$id) {
                $this->sendResponse(['error' => 'ID não fornecido'], 400);
                return;
            }
            
            // Verificar se o usuário existe
            $existing = $this->model->findById($id);
            if (!$existing) {
                $this->sendResponse(['error' => 'Usuário não encontrado'], 404);
                return;
            }
            
            // Verificar se o email já existe (excluindo o usuário atual)
            if (isset($data['email']) && $this->model->checkEmailExists($data['email'], $id)) {
                $this->sendResponse(['error' => 'Email já está em uso'], 422);
                return;
            }
            
            // Validar dados (exceto senha se vazia)
            $validationRules = $this->model->getValidationRules();
            if (empty($data['senha'])) {
                unset($validationRules['senha']);
            }
            
            $errors = $this->model->validate($data, $validationRules);
            
            if (!empty($errors)) {
                $this->sendResponse(['error' => 'Dados inválidos', 'validation_errors' => $errors], 422);
                return;
            }
            
            $success = $this->model->update($id, $data);
            
            if ($success) {
                $updatedRecord = $this->model->findById($id);
                $this->sendResponse(['success' => true, 'message' => 'Usuário atualizado com sucesso', 'data' => $updatedRecord]);
            } else {
                $this->sendResponse(['error' => 'Erro ao atualizar usuário'], 500);
            }
        } catch (Exception $e) {
            $this->sendResponse(['error' => 'Erro ao atualizar usuário: ' . $e->getMessage()], 500);
        }
    }

    public function login($email, $senha) {
        try {
            $usuario = $this->model->login($email, $senha);
            
            if ($usuario) {
                // Aqui você pode implementar sessões ou JWT
                $this->sendResponse(['success' => true, 'data' => $usuario, 'message' => 'Login realizado com sucesso']);
            } else {
                $this->sendResponse(['error' => 'Credenciais inválidas'], 401);
            }
        } catch (Exception $e) {
            $this->sendResponse(['error' => 'Erro no login: ' . $e->getMessage()], 500);
        }
    }

    public function getProfessores() {
        try {
            $professores = $this->model->getProfessores();
            $this->sendResponse(['success' => true, 'data' => $professores]);
        } catch (Exception $e) {
            $this->sendResponse(['error' => 'Erro ao buscar professores: ' . $e->getMessage()], 500);
        }
    }

    public function getCoordenadores() {
        try {
            $coordenadores = $this->model->getCoordenadores();
            $this->sendResponse(['success' => true, 'data' => $coordenadores]);
        } catch (Exception $e) {
            $this->sendResponse(['error' => 'Erro ao buscar coordenadores: ' . $e->getMessage()], 500);
        }
    }

    protected function handlePost() {
        $input = $this->getJsonInput();
        
        if (!$input) {
            $this->sendResponse(['error' => 'Dados inválidos'], 400);
            return;
        }
        
        $action = $input['action'] ?? 'create';
        
        switch ($action) {
            case 'login':
                $this->login($input['email'] ?? '', $input['senha'] ?? '');
                break;
            case 'getProfessores':
                $this->getProfessores();
                break;
            case 'getCoordenadores':
                $this->getCoordenadores();
                break;
            default:
                parent::handlePost();
                break;
        }
    }
}

// Instanciar e executar o controller
$controller = new UsuarioController();
$controller->handleRequest();
?>