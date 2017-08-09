<?php include('header.php'); ?>

<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid content">
    <div class="container content">
        <div class="col-md-4">

        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="text-center">
                        <h3 class="text-center">Register</h3>
                        <p>Registration here.</p>
                        <div class="panel-body">

                            <form class="form" action="/home/register"  method="post"><!--start form--><!--add form action as needed-->
                                <fieldset>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <!--EMAIL ADDRESS-->
                                            <input id="email" name="email" placeholder="email address" class="form-control" type="email" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <!--EMAIL ADDRESS-->
                                            <input  pattern=".{6,}" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Username" name="user" required >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <!--EMAIL ADDRESS-->
                                            <input  pattern=".{6,}" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="First Name" name="first_name" required >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <!--EMAIL ADDRESS-->
                                            <input  pattern=".{6,}" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Last Name" name="last_name" required >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <!--EMAIL ADDRESS-->
                                            <input  pattern=".{6,}" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Email" name="email" required >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <!--EMAIL ADDRESS-->
                                            <input  pattern=".{8,}" oninvalid="setCustomValidity('Minimum length is 8 characters')" oninput="setCustomValidity('')" type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <!--EMAIL ADDRESS-->
                                            <input  pattern=".{8,}" type="password" class="form-control" placeholder="Confirm Password" name="password_confirm" id="confirm_password" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-lg gb btn-block" value="Sign up" name="submit" type="submit">
                                    </div>
                                </fieldset>
                                 <a href="/home/login" class="text-center new-account">Already have an account? </a>
                            </form><!--/end form-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            

        </div>
    </div>


</div>
<?php include('footer.php'); ?>