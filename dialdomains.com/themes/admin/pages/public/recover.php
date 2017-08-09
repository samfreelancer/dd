<?php include $this->tpl->header('header_login.php'); ?>

<div class="content">

  <?php if (isset($recovered) && $recovered == true): ?>

  <div class="form-vertical login-form">
    <h3 class="form-title">Recover Password</h3>
    <p>
      Please check your email.
      <br />NOTE: url given below is for testing:<br />
      <?php echo SITE_HTTP_PATH; ?>/reset?s=<?php echo $link; ?>
    </p>
  </div>

  <?php else: ?>

  <form class="form-vertical login-form" action="<?php echo lib_link('public/default/recover'); ?>" method="post" name="recover">
    <h3 class="form-title">Recover Password</h3>
    <div class="alert alert-error hide">
      <button class="close" data-dismiss="alert"></button>
      <span><?php echo $this->status->display(); ?></span>
    </div>

    <div class="control-group">
      <label class="control-label">Your Email</label>
      <div class="controls">
        <div class="input-icon left">
          <i class="icon-envelope"></i>
          <?php echo lib_input('email', 'post', 'm-wrap'); ?>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn blue pull-right">
        Submit <i class="m-icon-swapright m-icon-white"></i>
      </button>
    </div>
  </form>

  <?php endif; ?>

</div>

<?php include $this->tpl->footer('footer_login.php'); ?>
