<?php
class groupPermissions extends adfModelBase {
    public function groupCan($group_id, $app) {
        $query = "
          SELECT
            gp.id
          FROM
            groupPermissions gp,
            permission p
          WHERE
            p.id = gp.permission_id
            AND gp.group_id = '" . $this->db->escape($group_id) . "'
            AND p.app = '" . $this->db->escape($app) . "'
        ";
        
        if ($this->db->getRow($query)) {
            return true;
        }

        return false;
    }

    public function getPermissionsByGroupId($group_id) {
        if (false == $results = $this->db->getRecords("SELECT * FROM `{$this->_table}` WHERE group_id = '" . $this->db->escape($group_id) . "'")) {
            return false;
        }

        return $results;
    }

    public function getPermissionPathsAsArrayByGroupId($group_id) {
        $query = "
          SELECT
            p.app
          FROM
            groupPermissions gp,
            permission p
          WHERE
            p.id = gp.permission_id
            AND gp.group_id = '" . $this->db->escape($group_id) . "'
        ";

        if (false == $results = $this->db->getRecords($query)) {
            return false;
        }

        $paths = array();

        foreach ($results as $row) {
            $paths[] = $row['app'];
        }

        return $paths;
    }

    public function getPermissionsIdsForGroupAsArray($group_id) {
        if (false == $results = $this->db->getRecords("SELECT permission_id FROM `{$this->_table}` WHERE group_id = '" . $this->db->escape($group_id) . "'")) {
            return false;
        }

        $ids = array();

        foreach ($results as $row) {
            $ids[] = $row['permission_id'];
        }

        return $ids;
    }

    public function updatePermissions($group_id, $permissions, $data) {
        foreach ($permissions as $permission) {
            if (!empty($data['perm_' . $permission['id']]) && $data['perm_' . $permission['id']] == '1') {
                $this->addPermissionToGroup($group_id, $permission['id']);
            } else {
                $this->removePermissionFromGroup($group_id, $permission['id']);
            }
        }

        return true;
    }

    public function removePermissionFromGroup($group_id, $permission_id) {
        return $this->db->query("DELETE FROM `{$this->_table}` WHERE group_id = '" . $this->db->escape($group_id) . "' AND permission_id = '" . $this->db->escape($permission_id) . "'");
    }

    public function permissionExists($group_id, $permission_id) {
        if (false == $row = $this->db->getRow("SELECT * FROM `{$this->_table}` WHERE group_id = '" . $this->db->escape($group_id) . "' AND permission_id = '" . $this->db->escape($permission_id) . "'")) {
            return false;
        }

        return $row;
    }

    public function addPermissionToGroup($group_id, $permission_id) {
        if (!$this->permissionExists($group_id, $permission_id)) {
            $data = array(
                'group_id'      => $group_id,
                'permission_id' => $permission_id,
            );

            return $this->add($data);
        }

        return false;
    }
}
