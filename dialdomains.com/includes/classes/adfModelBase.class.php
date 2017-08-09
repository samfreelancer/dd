<?php
class adfModelBase {
    protected
        $_error,
        $_options = array(),
        $_table = '',
        $_fields,
        $_pkey;

    public function __construct($dbCon = null) {
        if (!is_null($dbCon)) {
            $this->db = $dbCon;
        } else {
            $this->db = new adfDb(adfRegistry::get('DB_LINK'));
        }
        
        if (empty($this->_table)){
            $this->_table = get_class($this);
        }
        
        $this->setTableDefinitions();

        if ($this->_table != 'eventLog') {
            $this->events = new eventLog();
        }
        
        $this->validator = get_class($this) . 'Validator';
    }
    
    public function __destruct() {
        adfTableDefinitions::destroy($this->db);
    }

    protected function setTableDefinitions() {
        $this->table_def    = adfTableDefinitions::getInstance($this->db);
        $this->_fields      = $this->table_def->getFields($this->_table);
        $this->_pkey        = $this->table_def->getPrimaryKey($this->_table);
        $this->_defaults    = $this->table_def->getDefaultValues($this->_table);
        $this->_required    = $this->table_def->getRequiredFields($this->_table);
        $this->_attributes  = $this->table_def->getFieldAttributes($this->_table);
    }

    /**
     *
     * Creates an indexed array of enum values
     *
     * @param string $field The name of the field
     * @param array $exclude An array of values to exclude
     * @return mixed An array of key/value pairs or false on failure
     */
    public function getEnumOptions($field, $exclude = array()) {
        if (!empty($this->_attributes[$field]) && is_array($this->_attributes[$field])) {
            $n = array();

            foreach ($this->_attributes[$field] as $option) {
                if (in_array($option, $exclude)) {
                    continue;
                }

                $n[$option] = ucwords($option);
            }

            asort($n, SORT_STRING);

            return $n;
        }

        return false;
    }

    /**
     * Retrieves a record by the Primary Key
     *
     * @param int $id The primary key
     * @return mixed An array or false on failure
     */
    public function getById($id) {
        if (false == $row = $this->db->getRow("SELECT * FROM `{$this->_table}` WHERE `{$this->_pkey}` = '" . $this->db->escape($id) . "'")) {
            return false;
        }

        return $row;
    }

    /**
     * Delets a record by Primary Key
     *
     * @param int $id The primary key
     * @return bool True or False if the delete query failed
     */
    public function deleteById($id) {
        return $this->db->query("DELETE FROM `{$this->_table}` WHERE `{$this->_pkey}` = '" . $this->db->escape($id) . "'");
    }

    /**
     *
     * Deletes records matching a where clause.  Do not include the WHERE construct.
     * @param string $where The where clause
     * @return bool True/False
     */
    public function deleteWhere($where = '1 = 2') {
        return $this->db->query("DELETE FROM `{$this->_table}` WHERE $where");
    }

    /**
     * Retrieves a record by matching a value with the specified field name
     *
     * @param string $field_name The database table field name
     * @param string $field_value The value to search for
     * @param string $order_by The field to order by
     * @param string $sort_order The order to sort by
     * @return mixed Array or false on failure
     */
    public function getOneByField($field_name, $field_value, $order_by = null, $sort_order = 'ASC') {
        $order_by       = (is_null($order_by)) ? $this->_pkey : $order_by;
        $sort_order     = ($sort_order == 'ASC') ? 'ASC' : 'DESC';

        $query = "
            SELECT
              *
            FROM
              `{$this->_table}`
            WHERE
              `{$field_name}` = '" . $this->db->escape($field_value) . "'
            ORDER BY
              `$order_by` $sort_order
        ";

        if (false == $row = $this->db->getRow($query)) {
            return false;
        }

        return $row;
    }

    /**
     * Retrieves all records by matching a value with the specified field name
     *
     * @param string $field_name The database table field name
     * @param string $field_value The value to search for
     * @param string $order_by The field to order by
     * @param string $sort_order The order to sort by
     * @return mixed Array or false on failure
     */
    public function getManyByField($field_name, $field_value, $order_by = null, $sort_order = 'ASC') {
        $order_by       = (is_null($order_by)) ? $this->_pkey : $order_by;
        $sort_order     = ($sort_order == 'ASC') ? 'ASC' : 'DESC';

        $query = "
            SELECT
              *
            FROM
              `{$this->_table}`
            WHERE
              `{$field_name}` = '" . $this->db->escape($field_value) . "'
            ORDER BY
              `$order_by` $sort_order
        ";

        if (false == $results = $this->db->getRecords($query)) {
            return false;
        }

        return $results;
    }

    /**
     * Add a record to the database
     *
     * @param array $data An array of key/value pairs
     * @return mixed An insert id, true, or false on failure
     */
    public function add($data) {
        try {
            $insert = array();

            # capture any and all array keys that belong in the table
            foreach ($this->_fields as $field) {
                if (array_key_exists($field, $data)) {
                    $insert[$field] = $data[$field];
                }
            }
            # Automatically set common fields
            if (in_array('addedon', $this->_fields)) {
                $insert['addedon'] = adfRegistry::get('MYSQL_NOW');
            }

            if (in_array('remoteip', $this->_fields)) {
                $insert['remoteip'] = $_SERVER['REMOTE_ADDR'];
            }

            # Set any required fields which aren't yet set
            foreach ($this->_required as $field) {
                if (!array_key_exists($field, $insert) || is_null($insert[$field])) {
                    $insert[$field] = $this->_defaults[$field];
                }
            }

            # unset the primary key
            unset($insert[$this->_pkey]);

            # validate record
            if (class_exists($this->validator)) {
                $validator = new $this->validator($this);
                if (!$validator->finalChecks($data)){
                    throw new Exception($validator->getFirstError());
                }
                if ($validator->hasFailed($insert)) {
                    throw new Exception($validator->getFirstError());
                }
            }
            
            # insert the record
            if (!$this->db->insert($insert, $this->_table)) {
                throw new Exception('An internal error has occurred.');
            }

            if ($this->db->affectedRows() > 0) {
                $id = $this->db->insert_id();
                adfRegistry::set('lastPkey', $id);
                return $id;
            }

            adfRegistry::set('lastPkey', null);

            return true;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return $e->getMessage();
        }
    }

    public function updateFieldById($id, $field, $value) {
        $up = array(
            $field  => $value,
        );

        return $this->db->update($up, $this->_table, array($this->_pkey => "= $id"));
    }

    /**
     * Updates a database record
     *
     * @param integer $id The primary key of the record to update
     * @param array $data A key/value array of fields to update
     * @return bool
     */
    public function update($id, $data) {
        try {
            # check that id is numeric
            if (!preg_match("#^\d+$#", $id)) {
                throw new Exception("Invalid primary key");
            }
            
            $update = array();

            # capture any and all array keys that belong in the table
            foreach ($this->_fields as $field) {
                if (array_key_exists($field, $data)) {
                    $update[$field] = $data[$field];
                }
            }
            if (in_array('updatedon', $this->_fields)) {
                $update['updatedon'] = adfRegistry::get('MYSQL_NOW');
            }

            # set id for update and remove from updated values
            adfRegistry::set('lastPkey', $id);
            unset($update[$this->_pkey]);

            #validate record
            if (class_exists($this->validator)) {
                $validator = new $this->validator($this, 'update', $id);
                if (!$validator->finalChecks($data)){
                    throw new Exception($validator->getFirstError());
                }
                if ($validator->hasFailed($update)) {
                    throw new Exception($validator->getFirstError());
                }
            }
            
            #update
            $this->db->update($update, $this->_table, array($this->_pkey => "= $id"), 1);

            return true;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves all records
     *
     * @return mixed An array of rows or false
     */
    public function getAll($order_by = NULL, $sort_order = 'ASC') {
        if (!is_null($order_by)) {
            $clause = "ORDER BY `" . $this->db->escape($order_by) . "` $sort_order";
        } else {
            $clause = '';
        }
        
        return $this->db->getRecords("SELECT * FROM `{$this->_table}` $clause");
    }

    /**
     *
     * Retrieves all records matching a where clause
     *
     * @param string $where MySQL WHERE clause.  Do not include WHERE.
     * @param string $order_by The field to order by.
     * @param string $sort_order The sort order
     * @return mixed An array of rows or false
     */
    public function getAllWhere($where, $order_by = 'id', $sort_order = 'ASC') {
        return $this->db->getRecords($this->getAllWhereQuery($where, $order_by, $sort_order));
    }

    /**
     *
     * Retrieves a single record matching a where clause
     *
     * @param string $where MySQL WHERE clause.  Do not include WHERE.
     * @return mixed A row or false
     */
    public function getOneWhere($where) {
        return $this->db->getRow($this->getOneWhereQuery($where));
    }

    /**
     *
     * Returns a database query returning all records matchng a where clause
     *
     * @param string $where MySQL WHERE clause.  Do not include WHERE.
     * @param string $order_by The field to order by.
     * @param string $sort_order The sort order
     * @return string The database query
     */
    public function getAllWhereQuery($where, $order_by = 'id', $sort_order = 'ASC') {
        return "SELECT * FROM `{$this->_table}` WHERE $where ORDER BY `" . $this->db->escape($order_by) . "` $sort_order";
    }

    /**
     *
     * Returns a database query returning a single record matchng a where clause
     *
     * @param string $where MySQL WHERE clause.  Do not include WHERE.
     * @return string The database query
     */
    public function getOneWhereQuery($where) {
        return "SELECT * FROM `{$this->_table}` WHERE $where LIMIT 1";
    }

    /**
     * Sets class options
     *
     * @param array $options An array of options
     * @return object $this For method chaining
     */
    public function setOptions($options) {
        if ($options == null || !is_array($options)) {
            $options = array();
        }

        foreach ($options as $key => $value) {
            $this->_options[$key] = $value;
        }

        return $this;
    }

    /**
     * Sets an option
     *
     * @param string $key The option key
     * @param mixed $value The option value
     * @return object $this For method chaining
     */
    public function setOption($key, $value) {
        $this->_options[$key] = $value;
        return $this;
    }

    /**
     * Sets an error
     *
     * @param string $error The error message
     * @return false Always returns false
     */
    protected function setError($e) {
        $this->_error = $e;
        return false;
    }

    /**
     * Retrieve an error
     *
     * @return string The error
     */
    public function getError() {
        return $this->_error;
    }
    
	/**
     *
     * Retrieves results based on query 
     *
     * @param string $query
     * @return mixed A row or false
     */
    public function getByQuery($query) {
        return $this->db->getRecords($query);
    }
    
	/**
     * Execute a Query
     *
     * @param string $query
     * @return bool True or False if the delete query failed
     */
    public function executeQuery($query) {
        return $this->db->query($query);
    }
}