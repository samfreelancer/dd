<?php
class groupsController extends adfBaseController {
    public function indexAction() {
        $group = new permissionGroup();
        $groupList = $group->getList();

        if (adfAuthentication::getCurrentPermissionGroupId() != 3) {
            foreach ($groupList as $key => $data) {
                if (in_array($data['id'], array(3))) {
                    unset($groupList[$key]);
                }
            }
        }

        include $this->tpl->page('manage-permissions/groups/list.php');
    }

    public function addAction() {
        if (lib_is_post()) {
            $group = new permissionGroup();

            if ($group->add($_POST)) {
                $this->status->message("The group {$_POST['name']} has been added.");
                return $this->renderNext();
            } else {
                $this->status->error($group->getError());
            }
        }

        include $this->tpl->page('manage-permissions/groups/add.php');
    }

    public function editAction() {
    	$groups    = new permissionGroup();

    	if (false == $data = $groups->getById(lib_request('id'))) {
    		$this->status->error("The selected group has been deleted or does not exist.");
    		$this->renderNext();
    	}

    	if (lib_is_post()) {
    		if ($groups->update(lib_request('id'), $_POST)) {
    			$this->status->message("The group has been updated.");
    			$this->renderNext();
    		} else {
    			$this->status->error($groups->getError());
    			$this->renderNext();
    		}
    	} else {
    		$_POST = $data;
    	}

    	include $this->tpl->page('manage-permissions/groups/edit.php');
    }

    public function deleteAction() {
    	$groups    = new permissionGroup();

        if (false == $data = $groups->getById(lib_request('id'))) {
            $this->status->error("The selected group has been deleted or does not exist.");
            $this->renderNext();
        }

        if (lib_request('id')) {
            if ($groups->deleteById(lib_request('id'))) {
                $this->status->message("The group has been deleted.");
                $this->renderNext();
            } else {
                $this->status->error($groups->getError());
                $this->renderNext();
            }
        } else {
            $_POST = $data;
        }

        lib_redirect("manage-permissions/groups/index");
    }
}