<?php

include_once "vendor/kktsvetkov/krumo/class.krumo.php";


/**
 * Database manipulation class
 */
class DB
{
    //<editor-fold desc="Variables">
    private static ?DB $instance = null;
    private PDO $connection;
    private string $username = DB_USER;
    private string $password = DB_PASSWORD;
    private string $servername = DB_HOST;
    private string $database = DB_NAME;
    private string $dsn;

    //</editor-fold>

    private function __construct()
    {
        $this->dsn = "mysql:host=$this->servername;dbname=$this->database";
        try {
            $this->connection = new PDO($this->dsn, $this->username, $this->password);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance(): DB
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param string $query sql query
     * @param array $params input parameters
     *
     * @return array
     */
    public function get_query(string $query, array $params): array
    {
        $result = null;
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result["data"] = $stmt->fetchAll();
            $result['stats']['affected_rows'] = $stmt->rowCount();
        } catch (PDOException $e) {
            if (IS_DEBUG) {
                krumo($result);
            }
            $result["error"] = $e->getMessage();
        }

        return $result;
    }

    public function get_scalar(string $query):string
    {
        $statement = $this->connection->query($query);
        return $statement->fetchColumn();
    }

    /**
     * Executes a sql query
     *
     * @param string $sql sql query
     * @param array $values input parameters
     *
     * @return bool
     */
    public function execute_query(string $sql, array $values): bool
    {
        $result = null;
        try {
            $result = $this->connection->prepare($sql)->execute($values);
        } catch (Exception $e) {
            $result = $e;
        }

        return $result;
    }
}