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
                        <h3 class="text-center">Change Password</h3>
                        <div class="panel-body">

                            <form class="form" action="/account/default/changePassword"  method="post"><!--start form--><!--add form action as needed-->
                                <fieldset>
                                    <label>Old Password</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <!--EMAIL ADDRESS-->
                                            <input  oninvalid="setCustomValidity('Minimum length is 8 characters')" oninput="setCustomValidity('')" type="password" class="form-control" placeholder="Old Password" name="old_password" required>
                                        </div>
                                    </div>
                                    <label>Password</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <!--EMAIL ADDRESS-->
                                            <input  oninvalid="setCustomValidity('Minimum length is 8 characters')" oninput="setCustomValidity('')" type="password" class="form-control" placeholder="Password" name="new_password" id="password" required>
                                        </div>
                                    </div>
                                    <label>Confirm Password</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <!--EMAIL ADDRESS-->
                                            <input  type="password" class="form-control" placeholder="Confirm Password" name="new_password_confirm" id="confirm_password" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-lg gb btn-block" value="Change" name="submit" type="submit">
                                    </div>
                                </fieldset>
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