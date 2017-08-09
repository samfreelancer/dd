<?php include('header.php'); ?>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<?php echo $this->status->display(); ?>
<div class="content">

    <div  class="register-domain container">
        <div class="row">
            <form action="/account/default/registerDomain" method="POST" class="form-horizontal form">				

                <input value="<?php echo $shopperUserData['user']; ?>" name="user" type="hidden" />    
                <input  value="<?php echo $finalizeDomain; ?>" name="finalize_domain" type="hidden">
                <input  value="<?php echo $id; ?>" name="id" type="hidden">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="input-group">
                        <label class="input-label">Domain</label>
                        <div class="inputs">
                            <?php ?>
                            <input  disabled="disabled" value="<?php echo trim($finalizeDomain);?>" name="domain_name" type="text">
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label">First Name</label>
                        <div class="inputs">
                            <?php echo lib_input("firstname", $method = 'post', $class = "m-wrap huge"); ?>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label">Last Name</label>
                        <div class="inputs">
                            <?php echo lib_input("lastname", $method = 'post', $class = "m-wrap huge"); ?>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label">Email</label>
                        <div class="inputs">
                            <?php echo lib_input("email", $method = 'post', $class = "m-wrap huge"); ?>
                            <p class="help-block"></p>
                        </div>
                    </div>


                    <div class="input-group">
                        <label class="input-label">Domain Password</label>
                        <div class="inputs">
                            <?php echo lib_input("password", $method = 'post', $class = "m-wrap huge"); ?>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label">Address</label>
                        <div class="inputs">
                            <?php echo lib_input("address", $method = 'post', $class = "m-wrap huge"); ?>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label">Phone</label>
                        <div class="inputs">
                            <input id="phone" value="<?php echo $shopperUserData['phone']; ?>" name="phone" type="text" placeholder="Phone">
                            <p class="help-block"></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="input-group">
                        <label class="input-label">Phone Region</label>
                        <div class="inputs">
                            <?php echo lib_input("phone_region", $method = 'post', $class = "m-wrap huge"); ?>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label">City / Town</label>
                        <div class="inputs">
                            <input id="city" name="city" value="<?php echo $shopperUserData['city']; ?>" type="text" placeholder="city" class="input-xlarge">
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label">State/Province/Region</label>
                        <div class="inputs">
                            <?php echo lib_input("state", $method = 'post', $class = "m-wrap huge"); ?>
                            <p class="help-block"></p>
                        </div>
                    </div>    
                    <div class="input-group">
                        <label class="input-label">Zip / Postal Code</label>
                        <div class="inputs">
                            <?php echo lib_input("zipcode", $method = 'post', $class = "m-wrap huge"); ?>
                            <p class="help-block"></p>
                        </div>
                    </div>    
                    <div class="input-group">
                        <label class="input-label">Country</label>
                        <div class="inputs">
                            <select id="country" name="country" class="input-xlarge">
                                <option value=""  <?php echo ('' == $shopperUserData['country']) ? 'selected="selected"' : '' ?>>(please select a country)</option>
                                <?php foreach ($countries as $key => $value) { ?>
                                    <option  value="<?php echo $value; ?>" <?php echo ($value == $shopperUserData['country']) ? 'selected="selected"' : '' ?>><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label">Years</label>
                        <div class="inputs">
                            <select id="years" name="years" class="input-xlarge">
                                <option selected value="1">1 Year</option>
                                <option value="2">2 Years</option>
                                <option value="3">3 Years</option>
                                <option value="5">5 Years</option>
                                <option value="10">10 Years</option>

                            </select>
                            <p class="help-block"></p>
                        </div>
                    </div>

                    <button type="submit" class="btn green big input-xlarge">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>