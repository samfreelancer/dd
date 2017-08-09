<?php include('header.php'); ?>
<div class="main">
<form action="" method="POST">
  <div class="container">
    <ul class="breadcrumb">
      <li><a href="/">Home</a></li>
      <li><a href="">Store</a></li>
      <li class="active">Search result</li>
    </ul>
    <!-- BEGIN SIDEBAR & CONTENT -->
    <div class="row margin-bottom-40">
      <!-- BEGIN SIDEBAR -->
      <div class="sidebar col-md-3 col-sm-5">
        <div class="sidebar-filter margin-bottom-25">
          <h2>Search categories</h2>
          <h3>Availability domains</h3>
          <div class="checkbox-list">
            <?php foreach ($tlds as $key => $value) { ?>
            <label><input type="checkbox" name="tlds[]" value="<?php echo $value; ?>" <?php if(isset($postedTlds) && is_array($postedTlds) && in_array($value, $postedTlds)) echo "checked"; ?>><?php echo $value; ?></label>
            <?php } ?>
          </div>
        </div>
      </div>
      <!-- END SIDEBAR -->
      <!-- BEGIN CONTENT -->
      <div class="col-md-9 col-sm-7">
        <div class="content-search margin-bottom-20">
          <div class="row">
            <div class="col-md-6 col-sm-6">
              <h1>Search for <em>Domain</em></h1>
            </div>
            <div class="col-md-6 col-sm-6">
              <div class="input-group">
                <?php echo lib_input("domain", $method = 'post', $class = "form-control"); ?>
                <span class="input-group-btn">
                <button class="btn btn-primary" type="submit"><?php echo (lib_post('domain')) ? 'Search again' : 'Search domain' ?></button>
                </span>
              </div>
            </div>
          </div>
          <?php if (lib_is_post()) {  ?>
          <?php if ($isAvaliable) { ?>
          <div class="row product-list clearfix">
            <div class="panel panel-success clearfix">
              <div class="panel-heading">
                <h3 class="panel-title">YOUR DOMAIN IS AVAILABLE.</h3>
              </div>
              <div class="panel-body">
                <div class="form-body">
                  <div class="form-group">
                    <div class="row product-item">
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <h3><a href="#"><?php echo lib_post('domain'); ?></a></h3>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <h3><a href="#"><?php echo lib_post('domain'); ?></a></h3>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="pi-price">$<?php echo $result['domains'][lib_post('domain')]['new_price']; ?></div>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="input-icon right">
                          <a href="#" data-num="<?php echo $result['domains'][lib_post('domain')]['price']; ?>" data-voice-domain="0" price="<?php echo $result['domains'][lib_post('domain')]['new_price']; ?>" domain="<?php echo lib_post('domain'); ?>" login="<?php echo (adfAuthentication::isLoggedIn()) ? 'in' : 'out' ?>" class="btn green <?php echo (!array_key_exists(lib_post('domain'), $this->shoppingCart)) ? 'add_to_cart' : 'disabled' ?>">
                          <?php echo (!array_key_exists(lib_post('domain'), $this->shoppingCart)) ? 'Add to cart' : 'Added!' ?>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } else if(count($availableVoiceDomains) > 0) { ?>
          <div class="margin-bottom-20">
            <div class="row">
              <div class="col-md-6 col-sm-6">
                <h1>Sorry, <?php echo lib_post('domain'); ?> is taken </h1>
              </div>
            </div>
          </div>
          <div class="row product-list clearfix">
            <div class="row padding-top-10">
              <div class="col-md-12">
                <div class="panel panel-default">
                  <div class="panel-heading">Voice domains</div>
                  <table class="table" style="border-collapse: unset">
                    <thead>
                      <tr>
                        <th>&nbsp;</th>
                        <th>Voice Domain</th>
                        <th>Price</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($availableVoiceDomains as $key => $value) {
                        $domainIndex = substr($value, strrpos($value, '.') + 1); ?>
                      <tr class="product-item">
                        <td><?php echo $value; ?></td>
                        <td><?php echo "Available"; ?></td>
                        <td>
                        	<?php $er = []; ?>
                          <div class="pi-price">$<?php echo lib_improve_price($er,$domain_price[$domainIndex]['price']); ?></div>
                        </td>
                        <td>
                          <a href="#" data-voice-domain="1" data-num="<?php echo $domain_price[$domainIndex]['price']; ?>" price="<?php echo $domain_price[$domainIndex]['price']; ?>" domain="<?php echo $value; ?>" login="<?php echo (adfAuthentication::isLoggedIn()) ? 'in' : 'out' ?>" class="btn btn-default add2cart <?php echo (!in_array($value, $this->shoppingCart)) ? 'add_to_cart' : 'disabled' ?>">
                          <?php echo (!array_key_exists($value, $this->shoppingCart)) ? 'Add to cart' : 'Added!' ?>
                          </a>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
          <?php } else { ?>
          <div class="margin-bottom-20">
            <div class="row">
              <div class="col-md-6 col-sm-6">
                <h1>Sorry, <?php echo lib_post('domain'); ?> is taken as well as voice domain <?php echo lib_post('domain'); ?> is taken </h1>
              </div>
            </div>
          </div>
          <?php } ?>
          <div class="margin-bottom-20">
            <div class="row">
              <div class="col-sm-offset-9 col-md-2">
                <a href="/account/default/shoppingCart" id="cart" class="btn btn-lg green m-icon-big <?php echo (empty($this->shoppingCart)) ? 'disabled' : '' ?>">Continue to Cart <i class="m-icon-big-swapright m-icon-white"></i></a>
              </div>
            </div>
          </div>
          <div class="row product-list clearfix">
            <?php if (!empty($result['suggested'])) { ?>
            <div class="row padding-top-10">
              <div class="col-md-12">
                <div class="panel panel-default">
                  <div class="panel-heading">Alternative domains</div>
                  <table class="table" style="border-collapse: unset">
                    <thead>
                      <tr>
                        <th>&nbsp;</th>
                        <th>Domain</th>
                        <th>Voice Domain</th>
                        <th>Price</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($result['suggested'] as $domain => $meta) { 
                      if (true === $meta['avail']){
                      ?>
                      <tr class="product-item">
                        <td><?php echo $domain; ?></td>
                        <td><?php echo "Available"; ?></td>
                        <td><?php echo (!empty($meta['voice']) && $meta['voice'] === true) ? "Available": "Not Available"; ?></td>
                        <td>
                          <div class="pi-price">$<?php echo $meta['new_price']; ?></div>
                        </td>
                        <td>
                          <a href="#" data-voice-domain="0" price="<?php echo $meta['new_price']; ?>" domain="<?php echo $domain; ?>" login="<?php echo (adfAuthentication::isLoggedIn()) ? 'in' : 'out' ?>" class="btn btn-default add2cart <?php echo (!in_array($domain, $this->shoppingCart)) ? 'add_to_cart' : 'disabled' ?>">
                          <?php echo (!in_array($domain, $this->shoppingCart)) ? 'Add to cart' : 'Added!' ?>
                          </a>
                        </td>
                      </tr>
                      <?php }} ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
        <!-- BEGIN PRODUCT LIST -->
      </div>
      <!-- END CONTENT -->
    </div>
    <!-- END SIDEBAR & CONTENT -->
  </div>
</form>
</div>
<?php include('footer.php'); ?>