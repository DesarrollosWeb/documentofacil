<?php
include_once __DIR__ . "/vendor/autoload.php";

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
    public function get_query(string $query, array $params = []): array
    {
        $result = null;
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result["data"] = $stmt->fetchAll();
            $result['stats']['affected_rows'] = $stmt->rowCount();
        } catch (PDOException $e) {
            if (IS_DEVELOPMENT) {
                $stmt->debugDumpParams();
                krumo($query, $params, $result);
                krumo($e);
            }
            $result["error"] = $e->getMessage();
        }

        return $result;
    }

    /**
     * Get an scalar value from the table
     * @param string $query sql query
     * @return string return an scalar value
     */
    public function get_scalar(string $query): string
    {
        $result = null;
        $statement = null;
        try {
            $statement = $this->connection->query($query);
            $result = $statement->fetchColumn();
        } catch (Exception $e) {
            $statement->debugDumpParams();
            krumo($e);
        }
        return $result;
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
        try {
            $result = $this->connection->prepare($sql)->execute($values);
            if (IS_DEVELOPMENT) {
                krumo($sql, $values);
            }
        } catch (Exception $e) {
            $result = $e;
        }

        return $result;
    }
}
