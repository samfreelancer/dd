<?php
class permissionGroup extends adfModelBase {

    public function getCanNotEditByKeyValue() {
        if (false == $results = $this->getManyByField('can_edit', 'false')) {
            return false;
        }

        $rows = array();

        foreach ($results as $record) {
            $rows[$record['id']] = $record['name'];
        }

        return $rows;
    }

    public function getCanEditByKeyValue() {
        if (false == $results = $this->getManyByField('can_edit', 'true')) {
            return false;
        }

        $rows = array();

        foreach ($results as $record) {
            $rows[$record['id']] = $record['name'];
        }

        return $rows;
    }

    public function getCanAddByKeyValue() {
        if (false == $results = $this->getManyByField('can_add', 'true')) {
            return false;
        }

        $rows = array();

        foreach ($results as $record) {
            $rows[$record['id']] = $record['name'];
        }

        return $rows;
    }

    public function getAllByKeyValue() {
        if (false == $results = $this->getList()) {
            return false;
        }

        $rows = array();

        foreach ($results as $record) {
            $rows[$record['id']] = $record['name'];
        }

        return $rows;
    }

    public function getList() {
        if (false == $results = $this->db->getRecords("SELECT * FROM `{$this->_table}` ORDER BY `name` ASC")) {
            return false;
        }

        return $results;
    }
}