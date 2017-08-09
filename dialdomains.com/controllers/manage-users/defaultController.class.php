<?php
class defaultController extends adfBaseController {

    public function indexAction() {
        $user       = new user();
        $userList   = $user->getAllWhere("status = 'active'");

        $group        = new permissionGroup();
        $groupList    = $group->getAllByKeyValue();
        $groupListAdd = $group->getCanAddByKeyValue();

        include $this->tpl->page('manage-users/list.php');
    }

    public function addAction() {
        $group      = new permissionGroup();
        $groupList  = $group->getCanAddByKeyValue();

        if (lib_is_post()) {
            $user = new user();

            if ($user->add($_POST)) {
                $this->status->message("The user {$_POST['first_name']} {$_POST['last_name']} has been added.");
                return $this->renderNext();
            } else {
                $this->status->error($user->getError());
            }
        }

        include $this->tpl->page('manage-users/add.php');
    }

    public function editAction() {
        $user           = new user();
        $group          = new permissionGroup();
        $groupList      = $group->getCanEditByKeyValue();
        $allGroupList   = $group->getAllByKeyValue();

        if (false == $data = $user->getById(lib_request('id'))) {
            $this->status->error("The requested user was not found.");
        }

        if (lib_is_post()) {
            if ($user->update(lib_request('id'), $_POST)) {
                $this->status->message("Your changes have been saved.");
                return $this->renderNext();
            } else {
                $_POST['group_id'] = $data['group_id'];
                $this->status->error($user->getError());
            }
        } else {
            unset($data['password']);
            $_POST = $data;
        }

        include $this->tpl->page('manage-users/edit.php');
    }

    public function deleteAction() {
        $user       = new user();

        if (false == $data = $user->getById(lib_request('id'))) {
            $this->status->error("User could not be found.");
            return $this->renderNext();
        }

        if (lib_request('confirm') == 'true') {
            if ($user->delete(lib_request('id'))) {
                $this->status->message("User has been terminated!");
            } else {
                $this->status->error($user->getError());
            }

            return $this->renderNext();
        }

        lib_redirect("manage-users/default/index");
    }
}