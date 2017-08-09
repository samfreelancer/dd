<?php include('header.php'); ?>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<div class="content">
  <div class="container-fluid margin-bottom-20">
    <div class="container ">
      <?php echo $this->status->display(); ?>
      <div class="row">
        <form action="/searchDomain" method="post">
          <div class="col-md-12">
            <div class="input-group">
              <input type="text" name="domain_name" value="<?php if(isset($domain)) echo $domain; ?>" class="form-control" placeholder="Search for domain...">
              <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Search</button>
              </span>
            </div>
          </div>
        </form>
      </div>
            
      <?php if (isset($nameGenerates) && is_array($nameGenerates) && count($nameGenerates) > 0) { ?>
      <div class="row padding-top-10">
        <div class="col-md-12"> 
          <div class="panel panel-default">
            <div class="panel-heading">Alternative domains</div>

            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Domain Name</th>
                  <th>Voice Domain</th>
                  <th>Price</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($nameGenerates as $key => $value) {
                    $domainIndex = substr($value, strrpos($value, '.') + 1); ?>
                <tr class="product-item">
                  <td><?php echo $key + 1; ?></td>
                  <td><?php echo $value; ?></td>
                  <td><?php echo $value; ?></td>
                  <td><div class="pi-price">$<?php echo $domain_price[$domainIndex]['price']; ?></div></td>
                  <td>
                    <a href="#" domain="<?php echo $value; ?>" login="out" class="btn btn-default add2cart <?php echo (!in_array($value, $this->shoppingCart)) ? 'add_to_cart' : 'disabled' ?>">
                    <?php echo (!in_array($value, $this->shoppingCart)) ? 'Add to cart' : 'Added!' ?>
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
  </div>
</div>
<?php include('footer.php'); ?>
