<?php include $this->tpl->header(); ?>

<div class="page-content">

  <div class="container-fluid">

    <div class="row-fluid">
      <div class="span12">

        <?php include $this->tpl->block('other', 'style-customizer.php'); ?>

        <h3 class="page-title">
          Add Group
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
          <li><a href="#">Add Group</a></li>
        </ul>

      </div>
    </div>

    <?php echo $this->status->display(); ?>

    <div class="row-fluid">
      <div class="span12">

        <div class="portlet box blue">
          <div class="portlet-title">
            <div class="caption"><i class="icon-group"></i>Add Group</div>
          </div>
          <div class="portlet-body form">

            <form class="form-horizontal form" name="userAdd" id="userAdd" action="/manage-permissions/groups/add" method="post" autocomplete="off">

              <?php include $this->tpl->block('form', 'manage-permission-group.php'); ?>

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
