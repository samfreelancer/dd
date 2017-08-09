<?php include('header.php'); ?>

<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid content">
    <div class="container content">
        <div class="col-md-4">
            <h4>Forgot or need to reset your password?</h4>
            <p>
                Enter your username or your	email address that is associated with the account that you are trying to recover.
            </p>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="text-center">
                        <img src="/themes/home/pages/test/assets/img/avatar.png" class="profile-img" height="70">
                        <h3 class="text-center">Forgot Password?</h3>
                        <p>If you have forgotten your password - reset it here.</p>
                        <div class="panel-body">

                            <form class="form" action="/home/forgotpassword" method="post"><!--start form--><!--add form action as needed-->
                                <fieldset>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <!--EMAIL ADDRESS-->
                                            <input id="email" name="email" placeholder="email address" class="form-control" type="email" >
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-lg gb btn-block" value="Send My Password" name="submit" type="submit">
                                    </div>
                                </fieldset>
                            </form><!--/end form-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">

            <?php
            if (isset($recover_attempt)) {
                echo"Your password has been reset, check your email to confirm";
            }
            ?>
        </div>
    </div>


</div>
<?php include('footer.php'); ?>