<?php include $this->tpl->header('header_login.php'); ?>

<div class="content">

  <?php if (isset($this->expired) && $this->expired): ?>

  <?php elseif (isset($reset) && $reset == true): ?>

    <div class="form-vertical login-form">
      <h4>Your password has been reset successfully</h4>
      <p>
        Please <a href="<?php echo lib_link('public/default/login'); ?>">click here</a> to login.
      </p>
    </div>

  <?php else: ?>

    <form class="form-vertical login-form" action="<?php echo lib_link('public/default/reset?s=' . $s); ?>" method="post" name="recover">
      <h3 class="form-title">Reset Your Password</h3>

      <div class="control-group">
        <label class="control-label">Your Email</label>
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-envelope"></i>
            <?php echo lib_input('email', 'post', 'm-wrap'); ?>
          </div>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label">Password</label>
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-lock"></i>
            <?php echo lib_password('data[password]', 'post', 'm-wrap'); ?>
          </div>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label">Re-type Password</label>
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-lock"></i>
            <?php echo lib_password('data[password_confirm]', 'post', 'm-wrap'); ?>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn ">
          Submit <i class="m-icon-swapright m-icon-white"></i>
        </button>
      </div>

    </form>

  <?php endif; ?>

</div>

<?php include $this->tpl->footer('footer_login.php'); ?>
