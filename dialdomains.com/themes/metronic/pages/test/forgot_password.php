<?php include('header.php'); ?>
<div class="main">
    <div class="container">
      <ul class="breadcrumb">
          <li><a href="index.html">Home</a></li>
          <li><a href="">Store</a></li>
          <li class="active">Forgot Your Password?</li>
      </ul>
      <!-- BEGIN SIDEBAR & CONTENT -->
      <div class="row margin-bottom-40">
        <!-- BEGIN SIDEBAR -->
        <div class="sidebar col-md-3 col-sm-3">
          <ul class="list-group margin-bottom-25 sidebar-menu">
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i>Login/Register</a></li>
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i>Restore Password</a></li>
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i>My account</a></li>
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i>Address book</a></li>
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i>Wish list</a></li>
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i>Returns</a></li>
            <li class="list-group-item clearfix"><a href="#"><i class="fa fa-angle-right"></i>Newsletter</a></li>
          </ul>
        </div>
        <!-- END SIDEBAR -->

        <!-- BEGIN CONTENT -->
        <div class="col-md-9 col-sm-9">
          <h1>Forgot Your Password?</h1>
          <div class="content-form-page">
            <div class="row">
              <div class="col-md-7 col-sm-7">
                <form action="/forgotpassword" class="form-horizontal form-without-legend" method="post" role="form">
                  <p>Enter the e-mail address associated with your account. Click submit to have your password e-mailed to you.</p>
                  <div class="form-group">
                    <label for="email" class="col-lg-4 control-label">Email</label>
                    <div class="col-lg-8">
                      <?php echo lib_input("email", $method = 'post', $class = "form-control"); ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-8 col-md-offset-4 padding-left-0 padding-top-5">
                      <button type="submit" class="btn btn-primary">Send</button>
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