<div class="control-group">
  <label class="control-label">User Name</label>
  <div class="controls">
    <?php echo lib_input('username', 'post', 'm-wrap medium'); ?>
  </div>
</div>
<div class="control-group">
  <label class="control-label">First Name</label>
  <div class="controls">
    <?php echo lib_input('first_name', 'post', 'm-wrap medium'); ?>
  </div>
</div>
<div class="control-group">
  <label class="control-label">Last Name</label>
  <div class="controls">
    <?php echo lib_input('last_name', 'post', 'm-wrap medium'); ?>
  </div>
</div>
<div class="control-group">
  <label class="control-label">Email</label>
  <div class="controls">
    <?php echo lib_input('email', 'post', 'm-wrap medium'); ?>
  </div>
</div>
<div class="control-group">
  <label class="control-label">Password</label>
  <div class="controls">
    <?php echo lib_password('password', 'post', 'm-wrap medium'); ?>
  </div>
</div>
<div class="control-group">
  <label class="control-label">Repeat Password</label>
  <div class="controls">
    <?php echo lib_password('password_confirm', 'post', 'm-wrap medium'); ?>
  </div>
</div>