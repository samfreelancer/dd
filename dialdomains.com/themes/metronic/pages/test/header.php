<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

    <!-- Head BEGIN -->
    <head>
        <meta charset="utf-8">
        <title><?php echo $this->getPageTitle(); ?></title>

        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <meta content="Metronic Shop UI description" name="description">
        <meta content="Metronic Shop UI keywords" name="keywords">
        <meta content="keenthemes" name="author">

        <meta property="og:site_name" content="-CUSTOMER VALUE-">
        <meta property="og:title" content="-CUSTOMER VALUE-">
        <meta property="og:description" content="-CUSTOMER VALUE-">
        <meta property="og:type" content="website">
        <meta property="og:image" content="-CUSTOMER VALUE-"><!-- link to image for socio -->
        <meta property="og:url" content="-CUSTOMER VALUE-">

        <link rel="shortcut icon" href="favicon.ico">
        <link href="/favicon.ico" rel="SHORTCUT ICON" type="image/ico">

        <!-- Fonts START -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow&subset=all" rel="stylesheet" type="text/css">
        <!-- Fonts END -->

        <!-- Global styles START -->    
        <?php adfView::addCSS("/themes/metronic/pages/test/assets/plugins/font-awesome/css/font-awesome.min.css", 10); ?>
        <?php adfView::addCSS("/themes/metronic/pages/test/assets/plugins/bootstrap/css/bootstrap.min.css", 10); ?>
        <!-- Global styles END --> 

        <!-- Page level plugin styles START -->
        <?php adfView::addCSS("/themes/metronic/pages/test/assets/plugins/fancybox/source/jquery.fancybox.css", 10); ?>
        <?php adfView::addCSS("/themes/metronic/pages/test/assets/plugins/bxslider/jquery.bxslider.css", 10); ?>
        <?php adfView::addCSS("/themes/metronic/pages/test/assets/plugins/uniform/css/uniform.default.css", 10); ?>
        <!-- Page level plugin styles END -->

        <!-- Theme styles START -->
        <?php adfView::addCSS("/themes/metronic/pages/test/assets/css/style-metronic.css", 10); ?>
        <?php adfView::addCSS("/themes/metronic/pages/test/assets/css/style.css", 10); ?>
        <?php adfView::addCSS("/themes/metronic/pages/test/assets/css/style-responsive.css", 10); ?>
        <?php adfView::addCSS("/themes/metronic/pages/test/assets/css/custom.css", 10); ?>
        <?php echo adfView::getCSS(); ?>
        <!-- Theme styles END -->
    </head>

    <!-- Head END -->

    <!-- Body BEGIN -->
    <body>
        <!-- BEGIN TOP BAR -->
        <div class="pre-header">
            <div class="container">
                <div class="row">
                    <!-- BEGIN TOP BAR LEFT PART -->
                    <div class="col-md-6 col-sm-6 additional-shop-info">
                        <ul class="list-unstyled list-inline">
                            <li><i class="fa fa-phone"></i>&nbsp;&nbsp;1 - 800- dial-domains</li>
                            <!-- <li><i class="fa fa-comment"></i>&nbsp;&nbsp;Live Chat 24/7</li> -->
                            <!-- END CURRENCIES -->
                            <!-- BEGIN LANGS -->
                            <li class="langs-block">
                                <!-- <a href="javascript:void(0);" class="current">English </a> -->
                                <!--<div class="langs-block-others-wrapper"><div class="langs-block-others">
                                        <a href="javascript:void(0);">French</a>
                                        <a href="javascript:void(0);">Germany</a>
                                        <a href="javascript:void(0);">Turkish</a>
                                    </div></div>-->
                            </li>
                            <!-- END LANGS -->
                        </ul>
                    </div>
                    <!-- END TOP BAR LEFT PART -->
                    <!-- BEGIN TOP BAR MENU -->
                    <div class="col-md-6 col-sm-6 additional-nav">
                        <ul class="list-unstyled list-inline pull-right">                            
                            <?php if (adfAuthentication::isLoggedIn()) { ?>
                                <!--                                <li><a href="/account/default/home">My Account</a></li>
                                                                <li><a href="#">My Wishlist</a></li>
                                                                <li><a href="/account/default/payDomain">Checkout</a></li>-->
                                <li><a href="/account/default/shoppingCart"> View Cart</a></li>
                                <li><a data-toggle="modal" data-target="#logout"> Logout</a></li>
                            <?php } else { ?>
                                <li><a href="/shoppingCart"> View Cart</a></li>
                                <li><a href="/login" > Log In</a></li>
                                <li><a href="/register" > Register</a></li>
                            <?php } ?>                           
                        </ul>
                    </div>
                    <!-- END TOP BAR MENU -->
                </div>
            </div>        
        </div>
        <!-- END TOP BAR -->
        <!-- BEGIN HEADER -->
        <div role="navigation" class="navbar header no-margin">
            <div class="container">
                <div class="row">
                    <div class="navbar-header col-md-2">
                        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                        <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <!-- END RESPONSIVE MENU TOGGLER -->

                        <a href="/" style="display: block;" class="logo navbar-brand"><span style="color: #E6400C;" >Dial</span><span style="color: #3E4D5C">Domains</span></a>		
                        <!-- LOGO -->
                    </div>
                    <!-- BEGIN CART -->
                    <!-- BEGIN NAVIGATION -->
                    <div class="collapse navbar-collapse mega-menu col-md-8">
                        <ul class="nav navbar-nav" style="margin:0px;">
                            <li><a href="/account/default/domains">Domains</a> </li>
                            <li><a href="#">Website</a></li>
                            <li><a href="#">Hosting</a></li>
                            <li><a href="/about">About</a></li>
                            <li><a href="/account/default/home#">Account</a></li>
                        </ul>
                    </div>
                    <!-- END NAVIGATION -->
                    <div class="cart-block col-md-2">
                        <div class="cart-info">
                            <a href="javascript:void(0);" class="cart-info-count"><?php
                                echo count($this->shoppingCart);
                                echo (count($this->shoppingCart) > 1) ? ' items' : ' item'
                                ?> </a>
                            <?php
                            /*if(isset($_COOKIE['checkout'])){
                                $checkout = array();
                                foreach (json_decode($_COOKIE['checkout']) as $key => $value) {
                                    $checkout[$key] = $value;
                                }
                            }*/
                            $domain_priceM = new domainprice();
                            $domain_price = $domain_priceM->getDomainsPrice();
                            $domain_price_sum = 0;
                            if (!empty($this->shoppingCart)) {
                                foreach ($this->shoppingCart as $key => $value) {
                                    if (!empty($value['domain'])) {
                                        //$domainIndex = substr($value['domain'], strrpos($value['domain'], '.') + 1);
                                        $domain_price_sum += $value['revised_price'];
                                    }
                                }
                            }
                            ?>
                            <a href="javascript:void(0);" class="cart-info-value ">$<span class="total_price_sum"><?php echo $domain_price_sum; ?></span></a>
						</div>
                            <i class="fa fa-shopping-cart"></i>
                            <!-- BEGIN CART CONTENT -->
                            <div class="cart-content-wrapper">
                                <div class="cart-content">
                                    <?php if (!empty($this->shoppingCart)) { ?>
                                        <ul class="scroller" style="height: 250px;">
                                            <?php
                                            foreach ($this->shoppingCart as $key => $value) {
                                                if (!empty($value['domain'])) {
                                                //$domainIndex = (isset($value['domain']))? substr($value['domain'], strrpos($value['domain'], '.') + 1): substr($value, strrpos($value, '.') + 1);
                                                ?>
                                                <li>
                                                    <a href="javascript:void(0);"></a>
                                                    <span class="cart-content-count"><?php echo $key+1; ?></span>
                                                    <strong><?php echo (isset($value['domain']))?$value['domain']: $value; ?></strong>
                                                    <em>$<?php echo $value['total_price']; ?></em>
                                                    <?php if (adfAuthentication::isLoggedIn()) { ?>
                                                        <a href="javascript:void(0)" domain="<?php echo (isset($value['domain']))?$value['domain']: ''; ?>" login="in" class="del-goods"><i class="fa fa-times"></i></a>
                                                <?php } else { ?>
                                                        <a href="javascript:void(0)" domain="<?php echo (isset($value['domain']))?$value['domain']: ''; ?>" login="out" class="del-goods"><i class="fa fa-times"></i></a>

                                                <?php } ?>
                                                </li>
                                        <?php }} ?>
                                        </ul>
                                        <?php } ?>
                                    <div class="text-right">
                                        <?php if (adfAuthentication::isLoggedIn()) { ?>
                                            <a href="/account/default/shoppingCart" class="btn btn-default">View Cart</a>
                                            <?php if (!empty($shoppingCart)) { ?>
                                                <a href="/account/default/payDomain" class="btn btn-primary">Checkout</a>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <a href="/shoppingCart" class="btn btn-primary">View Cart</a>
                                            <?php if (!empty($shoppingCart)) { ?>
                                                <a href="/login" class="btn btn-primary">Checkout</a>
                                            <?php } ?>
    <?php } ?>

                                    </div>
                                </div>
                            </div>
                            <!-- END CART CONTENT -->
                        </div>
                        <!-- END CART -->

                    </div>
                </div>
            </div>
            <!-- END HEADER -->



            <!--
            
            
            BODY
            <body>
            
              FIXED BAR
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
                      <li ><button class="btn gb" data-toggle="modal" data-target="#cart"><i class="fa fa-shopping-cart"></i> View Cart</button></li>
            
            <?php
            if (adfAuthentication::isLoggedIn()) {
                ?>
                                                            <li><button class="btn gb" data-toggle="modal" data-target="#logout"><i class="fa fa-power-off"></i>Logout</button></li>
                <?php
            } else {
                ?>
                                                            <li><button class="btn gb" data-toggle="modal" data-target="#login"><i class="fa fa-user"></i> Login</button></li>
                                                            <li><button class="btn gb" data-toggle="modal" data-target="#register"><i class="fa fa-user"></i> Register</button></li>
                                                            <li><a href="/home/login" class="btn gb"><i class="fa fa-user"></i> Login</a></li>
                                                            <li><a href="/home/register" class="btn gb"><i class="fa fa-user"></i> Register</a></li>
                <?php
            }
            ?>   
    
            </ul>
          </div>
        </div>
      </div>
      FIXED BAR
    
      <div class="container-fluid fixed-row-spacer"></div>
    
      NAV BAR
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
        NAV BAR-->
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
