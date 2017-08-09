<?php include $this->tpl->header(); ?>

<div class="page-content">

  <div class="container-fluid">

    <div class="row-fluid">
      <div class="span12">

        <?php include $this->tpl->block('other', 'style-customizer.php'); ?>

        <h3 class="page-title">
          Edit Access Permission
        </h3>
        <ul class="breadcrumb">
          <li>
            <i class="icon-home"></i>
            <a href="/index">Home</a>
            <i class="icon-angle-right"></i>
          </li>
          <li>
            <a href="/manage-permissions/groups/index">User Groups</a>
            <i class="icon-angle-right"></i>
          </li>
          <li><a href="#">Edit Access Permission</a></li>
        </ul>

      </div>
    </div>

    <?php echo $this->status->display(); ?>

    <div class="row-fluid">
      <div class="span12">

        <div class="portlet box blue">
          <div class="portlet-title">
            <div class="caption"><i class="icon-group"></i>Edit Access Permission</div>
          </div>
          <div class="portlet-body form">

            <form class="form form-horizontal" name="userAdd" id="userAdd" action="/manage-permissions/groupPermissions/edit" method="post" autocomplete="off">
                <?php foreach ($permissions as $permData): ?>
                  <div class="control-group">
                    <label class="control-label"></label>
                      <div class="controls">
                        <?php echo lib_checkbox('perm_' . $permData['id'], ' ' . $permData['description']); ?>
                      </div>
                  </div>
                <?php endforeach; ?>
                <input type="hidden" name="group_id" value="<?php echo lib_request('group_id'); ?>" />

                <div class="form-actions">
                  <button type="submit" class="btn blue"><i class="icon-ok"></i> Save</button>
                </div>

              </form>


          </div>
        </div>

      </div>
    </div>

  </div>

</div>

<?php include $this->tpl->footer(); ?>