<?php adfView::addCss('/assets/css/style-domains.css'); ?>
<?php adfView::addJs('/assets/scripts/manage-domains/add.js'); ?>
<?php adfView::addJs('/assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js'); ?>
<?php include $this->tpl->header(); ?>
<div class="page-content">

  <div class="container-fluid">

    <div class="row-fluid">
      <?php include $this->tpl->header("/elements/domail_header.php"); ?>
    </div>
    <div class="row-fluid">
      <div class="span12">

        <div class="portlet box blue">
          <div class="portlet-title">
            <div class="caption"><i class="icon-globe"></i>Users Domains List</div>
          </div>
          <div class="portlet-body">
<!--            <div class="table-toolbar">
              <div class="btn-group">
                <button id="add_user" class="btn green" onclick="addUserModal()">
                  Add New <i class="icon-plus"></i>
                </button>
              </div>
            </div>-->

            <table class="table table-hover">
              <thead>
                <tr>
                  <th width="5%">#</th>
                  <th>Doamin</th>
                  <th>Phone Number</th>
                  <th>addedon</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $counter = 1;
                foreach ($domains as $data): ?>
                  <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo $data['domain'];  ?></td>
                    <td><?php echo $data['phone_number']; ?></td>
                    <td><?php echo $data['addedon']; ?></td>
                    <td>
                      <a href="/manage-domains/default/edit?id=<?php echo $data['id']; ?>" title="Edit" class="btn yellow"><i class="icon-pencil"></i></a>
                      <button onclick="showConfirmationModal('/manage-domains/default/delete?id=<?php echo $data['id']; ?>&confirm=true', 'Are you sure you want to delete this domain?')" class="btn red"><i class="icon-trash"></i></button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

          </div>
        </div>

      </div>
    </div>
    <?php echo $this->status->display(); ?>
  </div>
</div>
<?php include $this->tpl->block('other', 'confirmation-modal.php'); ?>

<?php adfView::addJS("/assets/plugins/bootstrap-modal/js/bootstrap-modal.js");?>
<?php adfView::addJS("/assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js");?>
<?php adfView::addJS("/themes/admin/js/confirmation-modal.js");?>
<?php
include $this->tpl->footer();
