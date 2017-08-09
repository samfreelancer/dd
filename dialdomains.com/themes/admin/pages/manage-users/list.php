<?php adfView::addCSS("/assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.css");?>
<?php adfView::addCSS("/assets/plugins/bootstrap-modal/css/bootstrap-modal.css");?>

<?php include $this->tpl->header(); ?>

<div class="page-content">

  <div class="container-fluid">

    <div class="row-fluid">
      <div class="span12">

        <?php include $this->tpl->block('other', 'style-customizer.php'); ?>

        <h3 class="page-title">
          Users List
        </h3>
        <ul class="breadcrumb">
          <li>
            <i class="icon-home"></i>
            <a href="/index">Home</a>
            <i class="icon-angle-right"></i>
          </li>
          <li><a href="#">Users List</a></li>
        </ul>

      </div>
    </div>

    <?php echo $this->status->display(); ?>

    <div class="row-fluid">
      <div class="span12">

        <div class="portlet box blue">
          <div class="portlet-title">
            <div class="caption"><i class="icon-globe"></i>Users List</div>
          </div>
          <div class="portlet-body">
            <div class="table-toolbar">
              <div class="btn-group">
                <button id="add_user" class="btn green" onclick="addUserModal()">
                  Add New <i class="icon-plus"></i>
                </button>
              </div>
            </div>

            <table class="table table-hover">
              <thead>
                <tr>
                  <th width="5%">#</th>
                  <th>Name</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Group</th>
                  <th>Status</th>
                  <th width="12%">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $counter = 1;
                foreach ($userList as $data): ?>
                  <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo $data['first_name'] . ' ' . $data['last_name']; ?></td>
                    <td><?php echo $data['username']; ?></td>
                    <td><?php echo $data['email']; ?></td>
                    <td><?php echo $groupList[$data['group_id']]; ?></td>
                    <td><?php echo ucwords($data['status']); ?></td>
                    <td>
                      <a href="/manage-users/default/edit?id=<?php echo $data['id']; ?>" title="Edit" class="btn yellow"><i class="icon-pencil"></i></a>
                      <button onclick="showConfirmationModal('/manage-users/default/delete?id=<?php echo $data['id']; ?>&confirm=true', 'Are you sure you want to delete this user?')" class="btn red"><i class="icon-trash"></i></button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

          </div>
        </div>

      </div>
    </div>

  </div>

</div>

<div id="addUserModal" class="modal hide fade" tabindex="-1">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h3>Add User</h3>
  </div>
  <form class="form form-horizontal" name="userAdd" id="userAdd" action="/manage-users/default/add" method="post" autocomplete="off">
    <div class="modal-body">
      <?php include $this->tpl->block('form', 'manage-user.php'); ?>
      <div class="control-group">
        <label class="control-label">Group</label>
        <div class="controls">
          <?php echo lib_select('group_id', $groupListAdd, 'post', 'm-wrap medium'); ?>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" data-dismiss="modal" class="btn">Cancel</button>
      <button type="submit" class="btn blue">Add</button>
    </div>
  </form>
</div>

<?php include $this->tpl->block('other', 'confirmation-modal.php'); ?>

<?php adfView::addJS("/assets/plugins/bootstrap-modal/js/bootstrap-modal.js");?>
<?php adfView::addJS("/assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js");?>
<?php adfView::addJS("/themes/admin/js/confirmation-modal.js");?>

<script type="text/javascript">
  function addUserModal() {
    $('#addUserModal').modal();
  }
</script>

<?php include $this->tpl->footer(); ?>