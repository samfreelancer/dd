<?php include('header.php'); ?>
<div class="main">
    <div class="container">
      <ul class="breadcrumb">
          <li><a href="/">Home</a></li>
          <li><a href="">Store</a></li>
          <li class="active">Login</li>
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
          <h1>Login </h1>
          <div class="shopping-cart-data clearfix">
                <p><?php echo $this->status->display(); ?></p>
          </div>
          <div class="content-form-page">
            <div class="row">
              <div class="col-md-7 col-sm-7">
                <form action="/login" class="form-horizontal form-without-legend" method="post"  role="form">
                  <div class="form-group">
                    <label for="email" class="col-lg-4 control-label">Email <span class="require">*</span></label>
                    <div class="col-lg-8">
                        <?php echo lib_input("username", $method = 'post', $class = "form-control"); ?>
                      <!--<input type="text" class="form-control" id="email">-->
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="password" class="col-lg-4 control-label">Password <span class="require">*</span></label>
                    <div class="col-lg-8">
                        <?php echo lib_password("password", $method = 'post', $class = "form-control"); ?>
                      <!--<input type="text" class="form-control" id="password">-->
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-8 col-md-offset-4 padding-left-0">
                      <a href="/forgotpassword">Forget Password?</a>
                    </div>
                  </div>
                  <div class="row padding-top-10" style="margin-left: 135px;">
                    <div class="col-lg-4">
                      <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                      <div class="col-lg-8">Or you can <a href="/register"><span style="color: #e94d1c;">Register</span></a> from <a href="/register">here</a></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-8 col-md-offset-4 padding-left-0 padding-top-10 padding-right-30">
                      <hr>
                      <div class="login-socio">
                          <p class="text-muted">or login using:</p>
                          <ul class="social-icons">
                              <li><a href="https://www.facebook.com/" data-original-title="facebook" class="facebook" title="facebook"></a></li>
                              <li><a href="https://twitter.com/" data-original-title="Twitter" class="twitter" title="Twitter"></a></li>
                              <li><a href="https://plus.google.com/" data-original-title="Google Plus" class="googleplus" title="Google Plus"></a></li>
                              <li><a href="https://www.linkedin.com/" data-original-title="Linkedin" class="linkedin" title="LinkedIn"></a></li>
                          </ul>
                      </div>                      
                    </div>
                  </div>
                </form>
              </div>
              <div class="col-md-4 col-sm-4 pull-right">
                <div class="form-info">
                  <h2><em>Important</em> Information</h2>
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

