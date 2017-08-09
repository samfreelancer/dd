<?php include('header.php'); ?>
<div class="main">
    <div class="container">
      <ul class="breadcrumb">
          <li><a href="index.html">Home</a></li>
          <li><a href="">Store</a></li>
          <li class="active">Create new account</li>
      </ul>
      <!-- BEGIN SIDEBAR & CONTENT -->
      <div class="row margin-bottom-40">
        <!-- BEGIN SIDEBAR -->
        <div class="sidebar col-md-3 col-sm-3">
          <ul class="list-group margin-bottom-25 sidebar-menu">
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i> Login/Register</a></li>
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i> Restore Password</a></li>
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i> My account</a></li>
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i> Address book</a></li>
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i> Wish list</a></li>
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i> Returns</a></li>
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i> Newsletter</a></li>
          </ul>
        </div>
        <!-- END SIDEBAR -->

        <!-- BEGIN CONTENT -->
        <div class="col-md-9 col-sm-9">
          <h1>Create an account</h1>
          <div class="shopping-cart-data clearfix">
                <p><?php echo $this->status->display(); ?></p>
          </div>
          <div class="content-form-page">
            <div class="row">
              <div class="col-md-7 col-sm-7">
                <form class="form-horizontal" role="form" method="post">
                  <fieldset>
                    <legend>Your personal details</legend>
                    <div class="form-group">
                      <label for="firstname" class="col-lg-4 control-label">Username <span class="require">*</span></label>
                      <div class="col-lg-8">
                        <?php echo lib_input("user", $method = 'post', $class = "form-control"); ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="firstname" class="col-lg-4 control-label">First Name <span class="require">*</span></label>
                      <div class="col-lg-8">
                        <?php echo lib_input("first_name", $method = 'post', $class = "form-control"); ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="lastname" class="col-lg-4 control-label">Last Name <span class="require">*</span></label>
                      <div class="col-lg-8">
                        <?php echo lib_input("last_name", $method = 'post', $class = "form-control"); ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="email" class="col-lg-4 control-label">Email <span class="require">*</span></label>
                      <div class="col-lg-8">
                        <?php echo lib_input("email", $method = 'post', $class = "form-control"); ?>
                      </div>
                    </div>
                  </fieldset>
                  <fieldset>
                    <legend>Your password</legend>
                    <div class="form-group">
                      <label for="password" class="col-lg-4 control-label">Password <span class="require">*</span></label>
                      <div class="col-lg-8">
                           <?php echo lib_password("password", $method = 'post', $class = "form-control"); ?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="confirm-password" class="col-lg-4 control-label">Confirm password <span class="require">*</span></label>
                      <div class="col-lg-8">
                        <?php echo lib_password("password_confirm", $method = 'post', $class = "form-control"); ?>
                      </div>
                    </div>
                  </fieldset>
<!--                  <fieldset>
                    <legend>Newsletter</legend>
                    <div class="checkbox form-group">
                      <label>
                        <div class="col-lg-4 col-sm-4">Singup for Newsletter</div>
                        <div class="col-lg-8 col-sm-8">
                          <input type="checkbox">
                        </div>
                      </label>
                    </div>
                  </fieldset>-->
                  <div class="row">
                    <div class="col-lg-8 col-md-offset-4 padding-left-0 padding-top-20">                        
                      <button type="submit" class="btn btn-primary">Create an acoount</button>
                      <button type="button" class="btn btn-default">Cancel</button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="col-md-4 col-sm-4 pull-right">
                <div class="form-info">
                  <h2><em>Important</em> Information</h2>
                  <p>Lorem ipsum dolor ut sit ame dolore  adipiscing elit, sed sit nonumy nibh sed euismod ut laoreet dolore magna aliquarm erat sit volutpat. Nostrud exerci tation ullamcorper suscipit lobortis nisl aliquip  commodo quat.</p>

                  <p>Duis autem vel eum iriure at dolor vulputate velit esse vel molestie at dolore.</p>

                  <button type="button" class="btn btn-default">More details</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- END CONTENT -->
      </div>
      <!-- END SIDEBAR & CONTENT -->
    </div>
</div>
<?php include('footer.php'); ?>