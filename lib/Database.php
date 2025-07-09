<?php
class Database {
    private static $instance = null;
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    private function __construct() {
        // Configurar DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Criar instância PDO
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo 'Erro de conexão: ' . $this->error;
        }
    }

    // Implementar padrão Singleton
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->dbh;
    }

    // Métodos auxiliares para compatibilidade com código legado
    public static function getWrapper() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Preparar statement
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind values
    public function bind($param, $value, $type = null) {
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute a prepared statement
    public function execute() {
        return $this->stmt->execute();
    }

    // Obter resultados como array de objetos
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Obter registro único como objeto
    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Obter contagem de linhas
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    // Obter o último ID inserido
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }

    // Obter a conexão PDO direta (para casos especiais)
    public function getConnection() {
        return $this->dbh;
    }

    // Iniciar transação
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    // Confirmar transação
    public function commit() {
        return $this->dbh->commit();
    }

    // Desfazer transação
    public function rollBack() {
        return $this->dbh->rollBack();
    }

    // Verificar se está em transação
    public function inTransaction() {
        return $this->dbh->inTransaction();
    }
}
?>
