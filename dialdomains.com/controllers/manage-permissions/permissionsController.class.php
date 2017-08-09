<?php
class permissionsController extends adfBaseController {
    public function indexAction() {
        $perm = new permission();
        $permList = $perm->getList();

        include $this->tpl->page('manage-permissions/permissions/list.php');
    }

    public function addAction() {
        if (lib_is_post()) {
            $perm = new permission();

            if ($perm->add($_POST)) {
                $this->status->message("The permission {$_POST['name']} has been added.");
                return $this->renderNext();
            } else {
                $this->status->error($perm->getError());
            }
        }

        include $this->tpl->page('manage-permissions/permissions/add.php');
    }

    public function editAction() {
        $perm = new permission();

        if (false == $data = $perm->getById(lib_request('id'))) {
            $this->status->error("The requested permission does not exist.");
            return $this->renderNext();
        }

        if (lib_is_post()) {
            if ($perm->update(lib_request('id'), $_POST)) {
                $this->status->message("The permission {$_POST['name']} has been updated.");
                return $this->renderNext();
            } else {
                $this->status->error($perm->getError());
            }
        } else {
            $_POST = $data;
        }

        include $this->tpl->page('manage-permissions/permissions/edit.php');
    }

    public function deleteAction() {
        $perm = new permission();

        if (false == $data = $perm->getById(lib_request('id'))) {
            $this->status->error("The requested permission does not exist.");
            return $this->renderNext();
        }

        if (lib_request('id')) {
            if ($perm->delete(lib_request('id'))) {
                $this->status->message("The permission {$data['name']} has been deleted.");
                return $this->renderNext();
            } else {
                $this->status->error($perm->getError());
                return $this->renderNext();
            }
        }

        lib_redirect("manage-permissions/permissions/index");
    }
}