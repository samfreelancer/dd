<?php
class adfDb {

    public static $verbose = false;
    private $result = false;

    public function __construct($link_id) {
        $this->link_id = $link_id;
		$this->query("SET NAMES 'utf8'");
        if (defined('DB_TIMEZONE') && DB_TIMEZONE != ''){
            $this->query("SET time_zone = '" . $this->escape(DB_TIMEZONE) . "'");
        }
    }

    public function getRow($query, $freeResult = true) {
        if ($this->query($query) && $this->result && $this->numRows() > 0) {
            $row = $this->fetchAssoc();
            if ($freeResult) {
                $this->freeResult();
            }
            return $row;
        } else {
            return false;
        }
    }

    public function getRecords($query, $freeResult = true) {
        if ($this->query($query) && $this->result && $this->numRows() > 0) {
            $records = $this->fetchAll();
            if ($freeResult) {
                $this->freeResult();
            }
            return $records;
        }
        else {
            return false;
        }
    }

    public function query($query) {
        $start = microtime(true);
        $result = (!mysqli_multi_query($this->link_id, $query)) ? false : true;
        $exec_time = round((microtime(true) - $start), 3);

        if (!$result) {
            $this->result = false;
            trigger_error("$query failed with error: #" . mysqli_errno($this->link_id) . ' ' . mysqli_error($this->link_id), E_USER_WARNING);
        } else {
            $this->storeResult();
        }

        if (self::$verbose) {
            echo "<div>Query Details: \"$query\" took $exec_time seconds to execute.</div>";
            echo "<div><pre>" . print_r(debug_backtrace(), true) . "</pre></div>";
        }

        return $result;
    }

    private function storeResult() {
        return (false === ($this->result = mysqli_store_result($this->link_id))) ? false : true;
    }

    public function escape($string) {
        return mysqli_real_escape_string($this->link_id, $string);
    }

    public function insert($data, $table, $priority = '') {
        $insert_data = array();

        foreach ($data as $value) {
            if (is_null($value)) {
                $insert_data[] = 'NULL';
            }
            else {
                $insert_data[] = "'{$this->escape($value)}'";
            }
        }

        return $this->query("INSERT $priority INTO $table (`" . implode("`, `", array_map(array($this, 'escape'), array_keys($data))) . "`) VALUES (" . implode(", ", $insert_data) . ")");
    }

    public function update($data, $table, $parameters, $limit = null) {
        $query = "UPDATE $table SET ";

        foreach ($data as $field => $value) {
            if (is_null($value)) {
                $query .= "`{$this->escape($field)}` = NULL, ";
            }
            else {
                $query .= "`{$this->escape($field)}` = '{$this->escape($value)}', ";
            }
        }

        $query = substr($query, 0, -2);

        if (empty($parameters) || (!is_array($parameters))) {
            // TODO Trigger error
            $query .= " ";
        }
        else {
            $query .= " WHERE ";

            foreach ($parameters as $field => $value) {
                $value = explode(" ", $value);
                $query .= "`" . $this->escape($field) . "` " . $this->escape($value[0]) . " '" . $this->escape($value[1]) . "' AND ";
            }

            $query = substr($query, 0, -4);
        }

        if ($limit !== null) {
            if (preg_match('#^\d{1,12}$|^\d{1,12},\s{1,3}\d{1,12}$#', $limit)) {
                $query .= " LIMIT $limit";
            }
            else {
                // TODO trigger error
            }
        }
        return $this->query($query);
    }

    public function fetchAssoc($result = null) {
        if (is_null($result)) {
            return ($this->result) ? mysqli_fetch_assoc($this->result) : null;
        }

        return mysqli_fetch_assoc($result);
    }

    public function fetchAll() {
        $records = array();

        if (!$this->result) {
            return false;
        }

        while ($row = $this->fetchAssoc()) {
            $records[] = $row;
        }

        return $records;
    }

    public function insert_id() {
        return mysqli_insert_id($this->link_id);
    }

    public function numRows($result = null) {
        if (is_null($result)) {
            return ($this->result) ? mysqli_num_rows($this->result) : 0;
        }

        return mysqli_num_rows($result);
    }

    public function affectedRows() {
        return (-1 == ($rows = mysqli_affected_rows($this->link_id))) ? null : $rows;
    }

    public function getResult() {
        return $this->result;
    }

    public function freeResult() {
        mysqli_free_result($this->result);
    }

    public function dataSeek($offset) {
        mysqli_data_seek($this->result, $offset);
    }

    public function nextResult() {
        if (mysqli_next_result($this->link_id) && $this->storeResult()) {
            return true;
        }
        else {
            $this->result = false;
            return false;
        }
    }

    public function cycleResults() {
        while ($this->next_result()) {
            $this->freeResult();
        }
    }

    public function startTransaction() {
        return $this->query("START TRANSACTION");
    }

    public function setAutocommit($mode) {
        return mysqli_autocommit($this->link_id, $mode);
    }

    public function commit() {
        return mysqli_commit($this->link_id);
    }

    public function rollback() {
        return mysqli_rollback($this->link_id);
    }

    public function __destruct() {
        $this->setAutocommit(true);
    }

}