<?php
// filepath: c:\xampp\htdocs\GECOPEC\controllers\BaseController.php
abstract class BaseController {
    protected $model;

    public function __construct() {
        // Só define JSON header se for uma requisição AJAX
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
        }
        
        $this->initModel();
    }

    abstract protected function initModel();

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {
            case 'GET':
                $this->handleGet();
                break;
            case 'POST':
                $this->handlePost();
                break;
            case 'PUT':
                $this->handlePut();
                break;
            case 'DELETE':
                $this->handleDelete();
                break;
            default:
                $this->sendResponse(['error' => 'Método não permitido'], 405);
                break;
        }
    }

    protected function handleGet() {
        if (isset($_GET['id'])) {
            $this->show($_GET['id']);
        } else {
            $this->index();
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
            case 'create':
                $this->store($input['data'] ?? []);
                break;
            case 'read':
                if (isset($input['id'])) {
                    $this->show($input['id']);
                } else {
                    $this->index();
                }
                break;
            case 'update':
                $this->update($input['id'] ?? null, $input['data'] ?? []);
                break;
            case 'delete':
                $this->destroy($input['id'] ?? null);
                break;
            case 'search':
                $this->search($input['term'] ?? '');
                break;
            default:
                $this->sendResponse(['error' => 'Ação não reconhecida'], 400);
                break;
        }
    }

    protected function handlePut() {
        $input = $this->getJsonInput();
        $id = $_GET['id'] ?? null;
        $this->update($id, $input);
    }

    protected function handleDelete() {
        $id = $_GET['id'] ?? null;
        $this->destroy($id);
    }

    public function index() {
        try {
            $data = $this->model->findAll();
            $this->sendResponse(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            $this->sendResponse(['error' => 'Erro ao buscar dados: ' . $e->getMessage()], 500);
        }
    }

    public function show($id) {
        try {
            if (!$id) {
                $this->sendResponse(['error' => 'ID não fornecido'], 400);
                return;
            }
            
            $data = $this->model->findById($id);
            
            if (!$data) {
                $this->sendResponse(['error' => 'Registro não encontrado'], 404);
                return;
            }
            
            $this->sendResponse(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            $this->sendResponse(['error' => 'Erro ao buscar registro: ' . $e->getMessage()], 500);
        }
    }

    public function store($data) {
        try {
            // Validar dados
            $errors = $this->model->validate($data, $this->model->getValidationRules());
            
            if (!empty($errors)) {
                $this->sendResponse(['error' => 'Dados inválidos', 'validation_errors' => $errors], 422);
                return;
            }
            
            $id = $this->model->create($data);
            
            if ($id) {
                $newRecord = $this->model->findById($id);
                $this->sendResponse(['success' => true, 'message' => 'Registro criado com sucesso', 'data' => $newRecord], 201);
            } else {
                $this->sendResponse(['error' => 'Erro ao criar registro'], 500);
            }
        } catch (Exception $e) {
            $this->sendResponse(['error' => 'Erro ao criar registro: ' . $e->getMessage()], 500);
        }
    }

    public function update($id, $data) {
        try {
            if (!$id) {
                $this->sendResponse(['error' => 'ID não fornecido'], 400);
                return;
            }
            
            // Verificar se o registro existe
            $existing = $this->model->findById($id);
            if (!$existing) {
                $this->sendResponse(['error' => 'Registro não encontrado'], 404);
                return;
            }
            
            // Validar dados
            $errors = $this->model->validate($data, $this->model->getValidationRules());
            
            if (!empty($errors)) {
                $this->sendResponse(['error' => 'Dados inválidos', 'validation_errors' => $errors], 422);
                return;
            }
            
            $success = $this->model->update($id, $data);
            
            if ($success) {
                $updatedRecord = $this->model->findById($id);
                $this->sendResponse(['success' => true, 'message' => 'Registro atualizado com sucesso', 'data' => $updatedRecord]);
            } else {
                $this->sendResponse(['error' => 'Erro ao atualizar registro'], 500);
            }
        } catch (Exception $e) {
            $this->sendResponse(['error' => 'Erro ao atualizar registro: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id) {
        try {
            if (!$id) {
                $this->sendResponse(['error' => 'ID não fornecido'], 400);
                return;
            }
            
            // Verificar se o registro existe
            $existing = $this->model->findById($id);
            if (!$existing) {
                $this->sendResponse(['error' => 'Registro não encontrado'], 404);
                return;
            }
            
            $success = $this->model->delete($id);
            
            if ($success) {
                $this->sendResponse(['success' => true, 'message' => 'Registro excluído com sucesso']);
            } else {
                $this->sendResponse(['error' => 'Erro ao excluir registro'], 500);
            }
        } catch (Exception $e) {
            $this->sendResponse(['error' => 'Erro ao excluir registro: ' . $e->getMessage()], 500);
        }
    }

    public function search($term) {
        try {
            $data = $this->model->search($term);
            $this->sendResponse(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            $this->sendResponse(['error' => 'Erro na busca: ' . $e->getMessage()], 500);
        }
    }

    protected function getJsonInput() {
        $input = file_get_contents('php://input');
        return json_decode($input, true);
    }

    protected function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    protected function validateRequired($data, $fields) {
        $missing = [];
        
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $missing[] = $field;
            }
        }
        
        return $missing;
    }
}
?>