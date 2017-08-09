<?php
class adfTableDefinitions {
    private static
        $_instances = array();

    private
        $_tDefs;

    private function __construct($db) {
        $this->initialize($db);
    }

    public static function getInstance(adfDb $db) {
        if (!isset(self::$_instances[$db->link_id->databaseName])){
            self::$_instances[$db->link_id->databaseName] = new adfTableDefinitions($db);
        }
        
        return self::$_instances[$db->link_id->databaseName];
    }
    
    public static function destroy(adfDb $db) {
        unset(self::$_instances[$db->link_id->databaseName]);
    }

    protected function initialize($db) {
        $databaseName = $db->link_id->databaseName;
        $this->cache = new adfCache($databaseName . '_db_field_definitions.cache', 604800, adfRegistry::get('SITE_BASE_PATH') . '/cache/tables/');

        if (false == $this->_tDefs = $this->cache->get()) {
            $this->db = $db;

            if (false == $tableList = $this->db->getRecords("SHOW tables")) {
                return false;
            }

            foreach ($tableList as $table) {
                $table = $table["Tables_in_" . $databaseName];

                if (false == $fieldList = $this->db->getRecords("DESCRIBE $table")) {
                    return false;
                }

                $this->_tDefs[$table]['fields']             = array();
                $this->_tDefs[$table]['primary_key']        = null;
                $this->_tDefs[$table]['required']           = array();
                $this->_tDefs[$table]['defaults']           = array();
                $this->_tDefs[$table]['types']              = array();
                $this->_tDefs[$table]['attributes']         = array();
                $this->_tDefs[$table]['size']               = array();

                foreach ($fieldList as $fieldData) {
                    $this->_tDefs[$table]['fields'][] = $fieldData['Field'];

                    if ($fieldData['Key'] == 'PRI') {
                        $this->_tDefs[$table]['primary_key'] = $fieldData['Field'];
                    }

                    if ($fieldData['Null'] == 'NO') {
                        $this->_tDefs[$table]['required'][] = $fieldData['Field'];
                    }

                    $default = $fieldData['Default'] == 'Null' ? '' : (string) $fieldData['Default'];
                    $this->_tDefs[$table]['defaults'][$fieldData['Field']] = $default;

                    # parse the type and length, values..
                    preg_match('#([^\( ]+)(?:\(([^\)]+)\))?(?:\s{0,}(.*))?#i', $fieldData['Type'], $matches);

                    $this->_tDefs[$table]['types'][$fieldData['Field']] = $matches[1];

                    if ($matches[1] == 'enum' || $matches[1] == 'set') {
                        if (preg_match_all('#\'(.*?)\'[,)]#i', $fieldData['Type'], $options)) {
                            foreach ($options[1] as $option) {
                                $this->_tDefs[$table]['attributes'][$fieldData['Field']][] = str_replace("''", "'", $option);
                            }
                        }

                        $this->_tDefs[$table]['size'][$fieldData['Field']] = '';
                    } else {
                        $this->_tDefs[$table]['size'][$fieldData['Field']] = $matches[2];
                        $this->_tDefs[$table]['attributes'][$fieldData['Field']] = $matches[3];
                    }


                }
            }

            $this->cache->save($this->_tDefs);
        }
    }

    public function getDefaultValues($table) {
         $tableLowercase = strtolower($table);
        if (isset($this->_tDefs[$table]['defaults'])) {
            return $this->_tDefs[$table]['defaults'];
        }
         if (isset($this->_tDefs[$tableLowercase]['defaults'])) {
            return $this->_tDefs[$tableLowercase]['defaults'];
        }

        return array();
    }

    public function getRequiredFields($table) {
        $tableLowercase = strtolower($table);
        if (isset($this->_tDefs[$table]['required'])) {
           isset($this->_tDefs[$table]['required']);
        }
        else if(isset($this->_tDefs[$tableLowercase]['required'])){
           isset($this->_tDefs[$tableLowercase]['required']);
        }

        return array();
    }

    public function getFields($table) {
         $tableLowercase = strtolower($table);
        if (isset($this->_tDefs[$table]['fields'])) {
            return $this->_tDefs[$table]['fields'];
        }
        else if (isset($this->_tDefs[$tableLowercase]['fields'])) {
            return $this->_tDefs[$tableLowercase]['fields'];
        }

        return array();
    }

    public function getFieldTypes($table) {
         $tableLowercase = strtolower($table);
        if (isset($this->_tDefs[$table]['types'])) {
            return $this->_tDefs[$table]['types'];
        }
        else if (isset($this->_tDefs[$tableLowercase]['types'])) {
            return $this->_tDefs[$tableLowercase]['types'];
        }

        return '';
    }

    public function getFieldAttributes($table) {
          $tableLowercase = strtolower($table);
        if (isset($this->_tDefs[$table]['attributes'])) {
            return $this->_tDefs[$table]['attributes'];
        }
        else if (isset($this->_tDefs[$tableLowercase]['attributes'])) {
            return $this->_tDefs[$tableLowercase]['attributes'];
        }

        return '';
    }

    public function getPrimaryKey($table) {
         $tableLowercase = strtolower($table);
        if (isset($this->_tDefs[$table]['primary_key'])) {
            return $this->_tDefs[$table]['primary_key'];
        }
        else if (isset($this->_tDefs[$tableLowercase]['primary_key'])) {
            return $this->_tDefs[$tableLowercase]['primary_key'];
        }
        return '';
    }
}