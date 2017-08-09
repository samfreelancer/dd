<?php include $this->tpl->header(); ?>

<div class="page-content">

  <div class="container-fluid">

    <div class="row-fluid">
      <div class="span12">

        <?php include $this->tpl->block('other', 'style-customizer.php'); ?>

        <h3 class="page-title">
          Edit User
        </h3>
        <ul class="breadcrumb">
          <li>
            <i class="icon-home"></i>
            <a href="/index">Home</a>
            <i class="icon-angle-right"></i>
          </li>
          <li>
            <a href="/manage-users/default/index">Users List</a>
            <i class="icon-angle-right"></i>
          </li>
          <li>
            <a href="#">Edit User</a>
          </li>
        </ul>

      </div>
    </div>

    <?php echo $this->status->display(); ?>

    <div class="row-fluid">
      <div class="span12">

        <div class="portlet box blue">
          <div class="portlet-title">
            <div class="caption"><i class="icon-globe"></i>Edit User</div>
          </div>
          <div class="portlet-body form">

            <form class="form-horizontal" name="userEdit" id="userEdit" action="/manage-users/default/edit" method="post" autocomplete="off">
              <?php include $this->tpl->block('form', 'manage-user.php'); ?>
                <div class="control-group">
                  <label class="control-label">Group</label>
                  <div class="controls">
                  <?php if (array_key_exists($_POST['group_id'], $groupList)): ?>
                    <?php echo lib_select('group_id', $groupList, 'post', 'm-wrap medium'); ?>
                  <?php else: ?>
                    <input class="m-wrap medium" readonly="" type="text" disabled="" value="<?php echo $allGroupList[lib_post('group_id')]; ?>">
                  <?php endif; ?>
                  </div>
                </div>

                <input type="hidden" name="id" value="<?php echo $data['id']; ?>" />

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

