<?php include('header.php'); ?>
<div class="main">
    <div class="container">
        <?php echo $this->status->display(); ?>
        <ul class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li><a href="">Store</a></li>
            <li class="active">Checkout</li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
            <!-- BEGIN CONTENT -->
            <div class="col-md-12 col-sm-12">
                <h1>Checkout</h1>
                <!-- BEGIN CHECKOUT PAGE -->
                <form action="/account/default/payDomain" method="POST" >
                    <div class="panel-group checkout-page accordion scrollable" id="checkout-page">

                        <!-- BEGIN PAYMENT ADDRESS -->
                        <div id="payment-address" class="panel panel-default">
                            <div class="panel-heading">
                                <h2 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#checkout-page" href="#payment-address-content" class="accordion-toggle">
                                        Step 1: Account &amp; Billing Details
                                    </a>
                                </h2>
                            </div>
                            <div id="payment-address-content" class="panel-collapse collapse in">
                                <div class="panel-body row">
                                    <div class="col-md-6 col-sm-6">
                                        <h3>Your Personal Details</h3>
                                        <div class="form-group">
                                            <label for="firstname">First Name <span class="require">*</span></label>
                                            <?php echo lib_input("business_firstname", $method = 'post', $class = "form-control"); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="lastname">Last Name <span class="require">*</span></label>
                                            <?php echo lib_input("business_lastname", $method = 'post', $class = "form-control"); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">E-Mail <span class="require">*</span></label>
                                            <?php echo lib_input("email", $method = 'post', $class = "form-control", false, 'email'); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="telephone">Telephone <span class="require">*</span></label>
                                            <?php echo lib_input("business_telephone", $method = 'post', $class = "form-control", false, 'tel'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <h3>Your Address</h3>
                                        <div class="form-group">
                                            <label for="address1">Address 1</label>
                                            <?php echo lib_input("business_address", $method = 'post', $class = "form-control"); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="city">City <span class="require">*</span></label>
                                            <?php echo lib_input("business_city", $method = 'post', $class = "form-control"); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="post-code">Post Code <span class="require">*</span></label>
                                            <?php echo lib_input("business_zipcode", $method = 'post', $class = "form-control"); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="post-code">State <span class="require">*</span></label>
                                            <?php echo lib_input("business_state", $method = 'post', $class = "form-control"); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">                      
                                        <button class="btn btn-primary  pull-right" type="button" data-toggle="collapse" data-parent="#checkout-page" data-target="#payment-method-content" id="button-payment-address">Continue</button>
                                        <div class="checkbox pull-right">
                                            <label>
                                                <input type="checkbox" name="privacy_policy" id="privacy_policy" value="1"> I have read and agree to the <a title="Privacy Policy" href="#">Privacy Policy</a> &nbsp;&nbsp;&nbsp; 
                                            </label>
                                        </div>                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END PAYMENT ADDRESS -->

                        <!-- BEGIN PAYMENT METHOD -->
                        <div id="payment-method" class="panel panel-default">
                            <div class="panel-heading">
                                <h2 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#checkout-page" href="#payment-method-content" class="accordion-toggle">
                                        Step 2: Payment Method
                                    </a>
                                </h2>
                            </div>
                            <div id="payment-method-content" class="panel-collapse collapse">
                                <div class="panel-body row">
                                    <div class="col-md-12">
                                        <p>Please select the preferred payment method to use on this order.</p>
                                        <div class="form-group">
                                            <label for="creditcard">Credit Card <span class="require">*</span></label>
                                            <?php echo lib_input("creditcard", $method = 'post', $class = "form-control"); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="expiration">Expiration Date <span class="require">*</span></label>
                                            <?php echo lib_input("expiration", $method = 'post', $class = "form-control"); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="cvv">CVV <span class="require">*</span></label>
                                            <?php echo lib_input("cvv", $method = 'post', $class = "form-control"); ?>
                                        </div>
                                        <button class="btn btn-primary pull-right" type="button" id="button-payment-method" data-toggle="collapse" data-parent="#checkout-page" data-target="#confirm-content">Continue</button>
                                        <div class="checkbox pull-right">
                                            <label>
                                                <input type="checkbox"  name="terms_condition" id="terms_condition" value="1"> I have read and agree to the <a title="Terms & Conditions" href="#">Terms & Conditions </a> &nbsp;&nbsp;&nbsp; 
                                            </label>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END PAYMENT METHOD -->

                        <!-- BEGIN CONFIRM -->
                        <div id="confirm" class="panel panel-default">
                            <div class="panel-heading">
                                <h2 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#checkout-page" href="#confirm-content" class="accordion-toggle">
                                        Step 3: Confirm Order
                                    </a>
                                </h2>
                            </div>
                            <div id="confirm-content" class="panel-collapse collapse">
                                <div class="panel-body row">
                                    <?php  if (!empty($shoppingCart)) { ?>
                                        <div class="col-md-12 clearfix">
                                            <div class="table-wrapper-responsive">
                                                <table>
                                                    <tr>
                                                        <th class="checkout-model">Domain Name</th>
                                                        <th class="checkout-quantity">YEARS</th>
                                                        <th class="checkout-price">Price</th>
                                                        <th class="checkout-total">Total</th>
                                                    </tr>
                                                    <?php
                                                    $domain_priceM = new domainprice();
                                                    $domain_price = $domain_priceM->getDomainsPrice();
                                                    $total = 0;
                                                    /*if (isset($_COOKIE['checkout'])) {
                                                        $checkout = array();
                                                        foreach (json_decode($_COOKIE['checkout']) as $key => $value) {
                                                            $checkout[$key] = $value;
                                                        }
                                                    }*/
                                                    $ss = 'out'; 
                                                	if (adfAuthentication::isLoggedIn()) {
                                                        $ss = 'in';
                                                    }
                                                    foreach ($this->shoppingCart as $key => $value) {
                                                        //$domainIndex = (isset($value['name']))?substr($value['name'], strrpos($value['name'], '.') + 1):substr($value, strrpos($value, '.') + 1);
                                                        $total+= $value['revised_price'];
                                                        ?>
                                                        <tr>
                                                            <td class="checkout-model"><?php echo $value['domain']; ?></td>
                                                            <td class="checkout-quantity">
                                                                <select name="years" login="<?php echo $ss; ?>" class="select selectpicker" data-id-dropdown="<?php echo $key; ?>">
                                                                    <option  value="1" <?php if ($value['quantity'] == '1') echo 'selected="selected"'; ?>>1 Year</option>
                                                                    <option  value="2" <?php if ($value['quantity'] == '2') echo 'selected="selected"'; ?>>2 Years</option>
                                                                    <option  value="3" <?php if ($value['quantity'] == '3') echo 'selected="selected"'; ?> >3 Years</option>
                                                                    <option  value="5" <?php if ($value['quantity'] == '5') echo 'selected="selected"'; ?>>5 Years</option>
                                                                    <option  value="10" <?php if ($value['quantity'] == '10') echo 'selected="selected"'; ?>>10 Years</option>
                                                                </select>
                                                            </td>
                                                            <td class="checkout-price"><strong><span>$</span><span data-unit-id="<?php echo $key; ?>"><?php echo $value['revised_price']; ?></span></strong></td>
                                                            <td class="checkout-total shopping-cart-total"><strong><span>$</span><span class="domanTotalPrice" data-total-id="<?php echo $key; ?>"><?php echo $value['total_price'];  ?></span></strong></td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>

                                            </div>
                                            <div class="checkout-total-block">
                                                <ul>
                                                    <li class="checkout-total-price">
                                                        <em>Grand Total</em>
                                                        <strong class="price"><span>$</span><span class="total_price_sum"><?php echo $total; ?></span></strong>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="clearfix"></div>
                                            <input type="hidden" name="domains" value="<?php echo htmlspecialchars(json_encode($this->shoppingCart)); ?>">
                                            <button class="btn btn-primary pull-right" type="submit" id="button-confirm">Confirm Order</button>
                                            <button type="button" class="btn btn-default pull-right margin-right-20">Cancel</button>
                                        </div>
                                    <?php } else { ?>
                                        <div class="shopping-cart-data clearfix">
                                            <p>Your shopping cart is empty!</p>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <!-- END CONFIRM -->
                    </div>
                </form>
                <!-- END CHECKOUT PAGE -->
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
    </div>
</div>

<?php include('footer.php'); ?>