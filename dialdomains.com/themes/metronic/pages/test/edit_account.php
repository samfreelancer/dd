<?php include('header.php'); ?>

<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid content">
    <div class="container content">        
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="text-center">
                        <h3 class="text-center">Edit Profile</h3>
                        <div class="panel-body">
                           <div class="row">
                            <form class="form" action="/account/default/edit"  method="post"><!--start form--><!--add form action as needed-->
                                <fieldset>
                                  <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>EMAIL ADDRESS</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <input id="email" value="<?php echo $user['email']; ?>" name="email" placeholder="email address" class="form-control" type="email" >
                                        </div>
                                    </div>
                                    <label>Username</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <input   value="<?php echo $user['username']; ?>" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Username" name="username" required >
                                        </div>
                                    </div>
                                     <label>First Name</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <input   value="<?php echo $user['first_name']; ?>" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="First Name" name="first_name" required >
                                        </div>
                                    </div>
                                    <label>Last Name</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <input   value="<?php echo $user['last_name']; ?>" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Last Name" name="last_name" required >
                                        </div>
                                    </div>
                                    <label>Domain Password</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <input   value="<?php echo $user['domain_password']; ?>" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Domain Password" name="domain_password" required >
                                        </div>
                                    </div>
                                    <label>Phone</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <input   value="<?php echo $user['phone']; ?>" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Phone" name="phone" required >
                                        </div>
                                    </div>
                                  </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                    <label>Phone Region</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <input   value="<?php echo $user['phone_region']; ?>" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Phone Region" name="phone_region" required >
                                        </div>
                                    </div>
                                    <label>Address</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <input   value="<?php echo $user['address']; ?>" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Address" name="address" required >
                                        </div>
                                    </div>
                                    <label>City</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <input   value="<?php echo $user['city']; ?>" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="City" name="city" required >
                                        </div>
                                    </div>
                                    <label>State</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <input   value="<?php echo $user['state']; ?>" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="State" name="state" required >
                                        </div>
                                    </div>
                                    <label>Country</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <input   value="<?php echo $user['country']; ?>" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Country" name="country" required >
                                        </div>
                                    </div>
                                    <label>Zipcode</label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            <!--EMAIL ADDRESS-->
                                            <input   value="<?php echo $user['zipcode']; ?>" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Zipcode" name="zipcode" required >
                                        </div>
                                    </div>
                                    </div>
<!--                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                            EMAIL ADDRESS
                                            <input   value="<?php echo $user['email']; ?>" oninvalid="setCustomValidity('Minimum length is 6 characters')" oninput="setCustomValidity('')" type="text" class="form-control" placeholder="Email" name="email" required >
                                        </div>
                                    </div>-->

                                    <div class="form-group">
                                        <input class="btn btn-lg gb btn-block" value="Edit" name="submit" type="submit">
                                    </div>
                                </fieldset>
                            </form><!--/end form-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>       
    </div>


</div>
<?php include('footer.php'); ?>