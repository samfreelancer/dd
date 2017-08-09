<?php
class adfMySQLSessionAdaptor extends adfSessionAdaptor {
	private $_lifetime = 0;
    private $_db;

    public function __construct(adfDb $db, $lifetime) {
        $this->_db = $db;
        $this->_lifetime = $lifetime;
    }

    public function read($key) {
        $query = "SELECT
                    session_data
                  FROM
                    sessions
                  WHERE
                    session_key = '" . $this->_db->escape($key) . "'
                    and session_timeout >= UNIX_TIMESTAMP()
                  LIMIT 1";

        if (false === ($data = $this->_db->getRow($query))) {
            return false;
        } else {
            return unserialize($data['session_data']);
        }
    }

    public function write($key, $data) {
		
		$this->setLifetime($key);
	
       $query = "INSERT INTO
                    sessions (session_key, session_data, session_timeout)
                  VALUES
                    ('" . $this->_db->escape($key) . "', '" . $this->_db->escape(serialize($data)) . "', UNIX_TIMESTAMP() + '" . $this->_db->escape($this->_lifetime) . "')
                  ON DUPLICATE KEY UPDATE
                    session_data = VALUES(session_data),
                    session_timeout = VALUES(session_timeout)";
					

        return $this->_db->query($query);
    }
	
	function setLifetime($key) {
		
		preg_match("/[a-z]+\/(\d*)\b/", $key,$matches);
		if ($matches[1] != "") {
			
		    $query = "SELECT group_id FROM user WHERE id={$matches[1]}";

            if (false === ($data = $this->_db->getRow($query))) {
			    return false;
			} else {
			    if ($data['group_id'] == 6) {
				    $this->_lifetime = (86400/2);
				}
				else {
				    $this->_lifetime = 86400;
				}
			}
			
		}
		
	}

    public function destroy($key) {
        return $this->_db->query("DELETE FROM sessions WHERE session_key = '" . $this->_db->escape($key) . "'");
    }

    public function gc() {
        return $this->_db->query("DELETE FROM sessions WHERE session_timeout < UNIX_TIMESTAMP()");
    }
}