<?php adfView::addCSS("/assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.css"); ?>
<?php adfView::addCSS("/assets/plugins/bootstrap-modal/css/bootstrap-modal.css"); ?>

<?php include $this->tpl->header(); ?>

<div class="page-content">

  <div class="container-fluid">

    <div class="row-fluid">
      <div class="span12">

        <?php include $this->tpl->block('other', 'style-customizer.php'); ?>

        <h3 class="page-title">
          Access Permissions
        </h3>
        <ul class="breadcrumb">
          <li>
            <i class="icon-home"></i>
            <a href="/index">Home</a>
            <i class="icon-angle-right"></i>
          </li>
          <li><a href="#">Access Permissions</a></li>
        </ul>

      </div>
    </div>

    <?php echo $this->status->display(); ?>

    <div class="row-fluid">
      <div class="span12">

        <div class="portlet box blue">
          <div class="portlet-title">
            <div class="caption"><i class="icon-bookmark-empty"></i>Permission List</div>
          </div>
          <div class="portlet-body">
            <div class="table-toolbar">
              <div class="btn-group">
                <button id="add_user" class="btn green" onclick="addPermissionModal()">
                  Add New <i class="icon-plus"></i>
                </button>
              </div>
            </div>
            <?php if (is_array($permList) && count($permList) > 0) { ?>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th width="5%">#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th width="12%">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $counter = 1;
                  foreach ($permList as $data) {
                    ?>
                    <tr>
                      <td><?php echo $counter++; ?></td>
                      <td><?php echo $data['name']; ?></td>
                      <td><?php echo $data['description']; ?></td>
                      <td>
                        <a class="btn yellow" href="<?php echo lib_link('/manage-permissions/permissions/edit'); ?>?id=<?php echo $data['id']; ?>" title="Edit"><i class="icon-pencil"></i></a>
                        <button class="btn red" onclick="showConfirmationModal('/manage-permissions/permissions/delete?id=<?php echo $data['id']; ?>', 'Are you sure you want to delete this permission?')" title="Delete"><i class="icon-trash"></i></button>
                      </td>
                    </tr>
                   <?php } ?>
                </tbody>
              </table>
            <?php } else { ?>
              <div class="alert alert-info">No permissions found</div>
            <?php } ?>
          </div>
        </div>

      </div>
    </div>

  </div>

</div>

<div id="addPermissionModal" class="modal hide fade" tabindex="-1">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h3>Add Permission</h3>
  </div>
  <form class="form-horizontal form" name="userAdd" id="userAdd" action="/manage-permissions/permissions/add" method="post" autocomplete="off">
    <div class="modal-body">
      <?php include $this->tpl->block('form', 'manage-permissions.php'); ?>
    </div>
    <div class="modal-footer">
      <button type="button" data-dismiss="modal" class="btn">Cancel</button>
      <button type="submit" class="btn blue">Add</button>
    </div>
  </form>
</div>

<?php include $this->tpl->block('other', 'confirmation-modal.php'); ?>

<?php adfView::addJS("/assets/plugins/bootstrap-modal/js/bootstrap-modal.js"); ?>
<?php adfView::addJS("/assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js"); ?>
<?php adfView::addJS("/themes/admin/js/confirmation-modal.js"); ?>

<script type="text/javascript">
  function addPermissionModal() {
    $('#addPermissionModal').modal();
  }
</script>

<?php include $this->tpl->footer(); ?>
