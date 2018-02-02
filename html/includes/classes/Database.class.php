<?php

class Database {

    private $isConn = false;
    private $dbh;
    private $error;
    private $stmt;
    private $settings;
    // establish database connection

    function __construct() {
        $this->db_connect();
    }

    /**
     * Database connection
     */
    public function db_connect() {
        $this->settings = parse_ini_file("settings.ini.php");
        $dsn = "mysql:host=" . $this->settings["host"] . ";dbname=" . $this->settings["dbname"] . ";charset=" . $this->settings["charset"];
        $options = array(
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES  => false,
        );
        try {
            $this->dbh = new PDO($dsn, $this->settings["user"], $this->settings["password"], $options);
            $this->isConn = true;
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo "<h1>Unable to connect</h1>";
            die();
        }
    }

    /**
     * Prepares a statement for execution and returns a statement object
     * @param string $query [[The query string]]
     * @return Object [[PDOStatement object. If the database server cannot successfully prepare the statement, PDO::prepare() returns FALSE or emits PDOException]]
     */
    public function query($query) {
        $this->stmt = $this->dbh->prepare($query);
    }

    /**
     * Adds parameters to the parameter array binded to their data types
     * @param Array $params
     */
    public function bind($params) {
        if (!empty($params)) {
            foreach($params as $param => $value) {
                if(is_int($value)) {
                    $type = PDO::PARAM_INT;
                } else if(is_bool($value)) {
                    $type = PDO::PARAM_BOOL;
                } else if(is_null($value)) {
                    $type = PDO::PARAM_NULL;
                } else {
                    $type = PDO::PARAM_STR;
                }
                $this->stmt->bindValue($param, $value, $type);
            }
        }
    }

    /**
     * Executes the prepared statement
     * @return Boolean
     */
    public function execute() {
        return $this->stmt->execute();
    }

    /**
     * Executes non-prepared statement that doesn't return result sets
     * @return Boolean
     */
    public function imExec($query) {
        $this->dbh->exec($query);
    }

    /**
     * 1. Calls prepare statement method
     * 2. Binds the params if any
     * 3. Calls the execute method
     * 4. Creates an object instantiation by matching the table column names with the object attributes
     *
     * @param  String $query [[SQL Query]]
     * @param  String $class [[The class name]]
     * @param  Array [$params = []] [[An array of params]]
     * @return Class
     */
    public function getObj($query, $class, $params = []) {
        if ($this->isConn) {
            try {
                $this->query($query);
                if (count($params)) {
                    $this->bind($params);
                }
                $this->execute();
                return $this->stmt->fetchAll(PDO::FETCH_CLASS, $class);
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                debuggerlog('Query_fail', $this->error);
                return false;
            }
        }
    }

    /**
     *  Get multiple entries in assoc array
     */
    public function getRows($query, $params = []) {
        if ($this->isConn) {
            try {
                $this->query($query);
                if (count($params)) {
                    $this->bind($params);
                }
                $this->execute();
                return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                debuggerlog('Query_fail', $this->error);
                return false;
            }
        }
    }

    /**
     * Get single entry as assoc array
     */
    public function getRow($query, $params = []) {
        if ($this->isConn) {
            try {
                $this->query($query);
                if (count($params)) {
                    $this->bind($params);
                }
                $this->execute();
                return $this->stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                debuggerlog('Query_fail', $this->error);
                return false;
            }
        }
    }

    /**
    *  Get error from a try catch inside transaction
    */
    public function ReturnError(){
        return $this->error;
    }
    /**
     * Execute query and check affected rows
     */
    public function affectRow($query, $params = []) {
        if ($this->isConn) {
            try {
                $this->query($query);
                if (count($params)) {
                    $this->bind($params);
                }
                $this->execute();
                return $this->numRows();
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                debuggerlog('Query_fail', $this->error);
                return false;
            }
        }
    }

        /**
     * Execute query and check affected rows
     */
    public function imAffectRow($query, $params = []) {
        $this->query($query);
        if (count($params)) {
            $this->bind($params);
        }
        $this->execute();
        return $this->numRows();
    }

        /**
     * Insert entry
     */
    public function imInsertRow($query, $params = []) {
        return $this->imAffectRow($query, $params);
    }

    /**
     * Insert entry
     */
    public function insertRow($query, $params = []) {
        return $this->affectRow($query, $params);
    }

    /**
     * Update entry
     */
    public function updateRow($query, $params = []) {
        return $this->affectRow($query, $params);
    }

    /**
     * Delete entry
     */
    public function deleteRow($query, $params = []) {
        return $this->affectRow($query, $params);
    }

    /**
     * Returns the auto generated id used in the latest query
     */
    public function getInsertedId() {
        return $this->dbh->lastInsertId();
    }

    /**
     * Gets the number of rows in a result
     */
    public function numRows() {
        return $this->stmt->rowCount();
    }

    /**
     * Begin transaction
     */
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    /**
     * Execute transaction
     */
    public function endTransaction() {
        return $this->dbh->commit();
    }

    /**
     * Rollback
     */
    public function cancelTransaction() {
        return $this->dbh->rollBack();
    }

    /**
     * Disconnect from db
     */
    public function Disconnect(){
        $this->datab = NULL;
        $this->isConn = FALSE;
    }

    /**
     * Show database tables if exist
     */
    public function showTables() {
        $sql = "SHOW TABLES";
        $result = $this->getRows($sql);
        return !empty($result)?$result: false;
    }

}

$database = new Database();

?>
