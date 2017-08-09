<!--HEADER-->
<!DOCTYPE html>
<html lang="en" class="">
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>	
  <?php adfView::addCSS("/assets/plugins/font-awesome-4.3.0/css/font-awesome.min.css", 10);?>
  <?php adfView::addCSS("/themes/home/pages/test/assets/css/style.css", 10);?>
  <?php adfView::addCSS("/assets/plugins/bootstrap-3.3.2-dist/css/bootstrap.min.css", 10);?>
  <?php adfView::addCSS("/assets/plugins/bootstrap-3.3.2-dist/css/bootstrap-theme.min.css", 10);?>
  <?php echo adfView::getCSS(); ?>
  
</head>
<!--HEADER-->

<!--BODY-->
<body>

  <!--FIXED BAR-->
  <div class="container-fluid fixed-row wb">
    <div class="container">
      <div class="col-md-4">
        <ul>
          <li><i class="fa fa-phone"></i>&nbsp;&nbsp;1 - 800- dial-domains</li>
          <li>&nbsp;&nbsp;&nbsp;</li>
          <li><i class="fa fa-comment"></i>&nbsp;&nbsp;Live Chat 24/7</li>
        </ul>
      </div>
      <div class="col-md-2"></div>
      <div class="col-md-8" style="text-align:right;">
        <ul>
          <li><a class="btn gb" href="/account/default/shoppingCart"><i class="fa fa-shopping-cart"></i> View Cart</a></li>

          <?php
          if(adfAuthentication::isLoggedIn()){
            ?>
            <li><button class="btn gb" data-toggle="modal" data-target="#logout"><i class="fa fa-power-off"></i> Logout</button></li>
            <?php
          }else{
            ?>
            <!--<li><button class="btn gb" data-toggle="modal" data-target="#login"><i class="fa fa-user"></i> Login</button></li>-->
            <!--<li><button class="btn gb" data-toggle="modal" data-target="#register"><i class="fa fa-user"></i> Register</button></li>-->
            <li><a href="/home/login" class="btn gb"><i class="fa fa-user"></i> Login</a></li>
            <li><a href="/home/register" class="btn gb"><i class="fa fa-user"></i> Register</a></li>
            <?php
          }
          ?>   

        </ul>
      </div>
    </div>
  </div>
  <!--FIXED BAR-->

  <div class="container-fluid fixed-row-spacer"></div>

  <!--NAV BAR-->
  <div class="container-fluid top-row tb">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <a href="/home/test"><span class="logo"><span class="g">D</span><span class="b">i</span><span class="y">a</span><span class="r">l</span class="logo">Domains</span>	</a>		
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-8">
          <ul style="margin:0px;">
            <li><i class="fa fa-globe"></i><br/><a href="/account/default/domains">Domains</a> <i class="fa fa-caret-down"></i></li>
            <li><i class="fa fa-desktop"></i><br/><a href="#">Website</a> <i class="fa fa-caret-down"></i></li>
            <li><i class="fa fa-upload"></i><br/><a href="#">Hosting</a> <i class="fa fa-caret-down"></i></li>
            <li><i class="fa fa-info"></i><br/><a href="#">About</a> <i class="fa fa-caret-down"></i></li>
            <li style="border-right: 1px solid #595959;"><i class="fa fa-user"></i><br/><a href="/account/default/home#">Account</a> <i class="fa fa-caret-down"></i></li>
          </ul>
        </div></div>
      </div>
    </div>
    <!--NAV BAR-->


    <!--Register Modal -->
    <div class="modal fade" id="register" tabindex="1" role="dialog" aria-labelledby="myModal2" area-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" area-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title g" id="myModalLabel" ><b>Register</b></h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                  <div class="account-wall">
                    <img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                    alt="">
                    <form class="form-signin" action="/home/register" method="post" name="register">
                      <input  pattern=".{6,}" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Username" name="user" required >
                      <input  pattern=".{6,}" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="First Name" name="first_name" required >
                      <input  pattern=".{6,}" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Last Name" name="last_name" required >
                      <input  pattern=".{6,}" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Email" name="email" required >
                      <input  pattern=".{8,}" oninvalid="setCustomValidity('Minimum length is 8 characters')" oninput="setCustomValidity('')" type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                      <input  pattern=".{8,}" type="password" class="form-control" placeholder="Confirm Password" name="password_confirm" id="confirm_password" required>
                      <button class="btn btn-lg  btn-block gb" type="submit">
                        Sign up</button>
                        <label class="checkbox pull-left">
                          <input type="checkbox" value="remember-me">
                          Remember me
                        </label>
                        <a href="/home/forgotpassword" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
                      </form>
                    </div>
                    <a href="/home/login" class="text-center new-account">Already have an account? </a>
                  </div>
                  <div class="col-md-3"></div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>


      <!--Login Modal -->
      <div class="modal fade" id="login" tabindex="1" role="dialog" aria-labelledby="myModal" area-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" area-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title g" id="myModalLabel" ><b>Login</b></h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-3"></div>
                  <div class="col-md-6">
                    <div class="account-wall">
                      <img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                      alt="">
                      <form class="form-signin" action="/login" method="post" name="login">
                        <input type="text" class="form-control" placeholder="Username" name="username" required autofocus>
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                        <button class="btn btn-lg  btn-block gb" type="submit">
                          Sign in</button>
                          <label class="checkbox pull-left">
                            <input type="checkbox" value="remember-me">
                            Remember me
                          </label>
                          <a href="/home/forgotpassword" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
                        </form>
                      </div>
                      <a href="/home/login" class="text-center new-account">Create an account </a>
                    </div>
                    <div class="col-md-3"></div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

         <!-- Logout Modal -->
      <div class="modal fade" id="logout" tabindex="1" role="dialog" aria-labelledby="logout" area-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" area-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title g" id="myModalLabel" ><b>Log out</b></h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-3"></div>
                  <div class="col-md-6">
                    <div class="account-wall">
                        <h6 class="g" style="text-align:center;"><b>Are you sure you want to logout?</b></h6>
                        <br/>
                      <form class="form-signin" action="/logoff" method="post" name="login">
                        <button class="btn btn-lg  btn-block gb" type="submit">
                          Yes, Logout</button>
                          <button class="btn btn-lg  btn-block gb" data-dismiss="modal" type="submit">
                          No, stay Logged in</button>     
                        </form>
                      </div>
                    </div>
                    <div class="col-md-3"></div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

