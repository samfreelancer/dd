<?php
class permission extends adfModelBase {
    public function add($data) {
        try {
            $this->validateNewPermission($data);

            //$this->events->add('PERMISSION_ADD', "Permission '{$data['name']}' added");

            return parent::add($data);
        } catch (Exception $e) {
            return $this->setError($e->getMessage());
        }
    }

    public function update($id, $data) {
        try {
            $this->validateUpdatePermission($data);

            //$this->events->add('PERMISSION_UPDATE', "Permission '{$data['name']}' updated");

            return parent::update($id, $data);
        } catch (Exception $e) {
            return $this->setError($e->getMessage());
        }
    }

    public function delete($id) {
        try {
            if (false == $data = $this->getById($id)) {
                throw new Exception("Permission not found.");
            }

            //$this->events->add('PERMISSION_DELETE', "Permission '{$data['name']}' deleted");

            $this->db->query("DELETE FROM `{$this->_table}` WHERE `id` = '" . $this->db->escape($id) . "'");

            return true;
        } catch (Exception $e) {
            return $this->setError($e->getMessage());
        }
    }

    public function validateUpdatePermission($data) {
        if (empty($data['name']) || strlen($data['name']) > 255) {
            throw new Exception("Name is required and must be shorter than 256 characters.");
        }
    }

    public function validateNewPermission($data) {
        if (empty($data['app']) || strlen($data['app']) > 255) {
            throw new Exception("Application is required and must be shorter than 256 characters.");
        }

        if ($this->getByApp($data['app'])) {
            throw new Exception("An application by that name already exists.");
        }

        if (empty($data['name']) || strlen($data['name']) > 255) {
            throw new Exception("Name is required and must be shorter than 256 characters.");
        }
    }

    public function getByApp($key) {
        if (false == $row = $this->db->getRow("SELECT * FROM `{$this->_table}` WHERE `app` = '" . $this->db->escape($key) . "'")) {
            return false;
        }

        return $row;
    }

    public function getList($orderBy = 'name', $order = 'ASC') {
        if (false == $results = $this->db->getRecords("SELECT * FROM `{$this->_table}` ORDER BY `" . $this->db->escape($orderBy) . "` $order")) {
            return false;
        }

        return $results;
    }
}