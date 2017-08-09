<?php
class groupPermissionsController extends adfBaseController {
    public function editAction() {
        $router     = new adfRouter();
        $mGroup     = new permissionGroup();
        $group_id   = adfAuthentication::getCurrentPermissionGroupId();

        if (false == $gData = $mGroup->getById(lib_request('group_id'))) {
            $this->status->error("The requested group was not found.");
            lib_redirect('groupManagement');
        }

        if ($group_id != 3 && $gData['can_edit'] != 'true') {
            $this->status->error("The requested group cannot be modified.");
            lib_redirect('manage-permissions/groups/index');
        }

        $groupName          = $gData['name'];
        $mPermissions       = new permission();
        $permissions        = $mPermissions->getList('app', 'ASC');
        $mGroupPermissions  = new groupPermissions();
        
        if (lib_is_post()) {
            if ($mGroupPermissions->updatePermissions(lib_request('group_id'), $permissions, $_POST)) {
                $this->status->message('The permissions have been updated');
                lib_redirect('manage-permissions/groups/index');
            } else {
                $this->status->error($mGroupPermissions->getError());
            }
        } else {
            if (false != $activePermissions = $mGroupPermissions->getPermissionsByGroupId(lib_request('group_id'))) {
                foreach ($activePermissions as $perm) {
                    $_POST['perm_' . $perm['permission_id']] = '1';
                }
            }
        }
        
        include $this->tpl->page('manage-permissions/groupPermissions/edit.php');
    }
}

