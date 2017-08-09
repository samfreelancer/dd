<?php include $this->tpl->header(); ?>

<div class="page-content">

  <div class="container-fluid">

    <div class="row-fluid">
      <div class="span12">

        <?php include $this->tpl->block('other', 'style-customizer.php'); ?>

        <h3 class="page-title">
          Add User
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
            <a href="#">Add User</a>
          </li>
        </ul>

      </div>
    </div>

    <?php echo $this->status->display(); ?>

    <div class="row-fluid">
      <div class="span12">

        <div class="portlet box blue">
          <div class="portlet-title">
            <div class="caption"><i class="icon-globe"></i>Add User</div>
          </div>
          <div class="portlet-body form">

            <form class="form-horizontal" name="userAdd" id="userAdd" action="/manage-users/default/add" method="post" autocomplete="off">
              <?php include $this->tpl->block('form', 'manage-user.php'); ?>
                <div class="control-group">
                  <label class="control-label">Group</label>
                  <div class="controls">
                    <?php echo lib_select('group_id', $groupList, 'post', 'm-wrap medium'); ?>
                  </div>
                </div>

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