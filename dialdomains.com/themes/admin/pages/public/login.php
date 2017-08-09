<?php include $this->tpl->header('header_login.php'); ?>

<div class="content">

  <form class="form-vertical login-form" action="" method="post" name="login">
    <h3 class="form-title">Login to your account</h3>
    <div class="alert alert-error hide">
      <button class="close" data-dismiss="alert"></button>
      <span><?php echo $this->status->display(); ?></span>
    </div>
    <div class="control-group">
      <label class="control-label">Username</label>
      <div class="controls">
        <div class="input-icon left">
          <i class="icon-user"></i>
          <?php echo lib_input('username', 'post', 'm-wrap'); ?>
        </div>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label">Password</label>
      <div class="controls">
        <div class="input-icon left">
          <i class="icon-lock"></i>
          <?php echo lib_password('password', 'post', 'm-wrap'); ?>
        </div>
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn blue pull-right">
        Sign In <i class="m-icon-swapright m-icon-white"></i>
      </button>
    </div>
    <div class="forget-password">
      <h4>Forgot your password ?</h4>
      <p>
        no worries, click <a href="recover" id="forget-password">here</a>
        to reset your password.
      </p>
    </div>
  </form>

</div>

<?php include $this->tpl->header('footer_login.php'); ?>
