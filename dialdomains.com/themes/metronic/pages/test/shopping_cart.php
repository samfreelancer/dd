<?php include('header.php'); ?>
<div class="main">
    <div class="container">
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
            <!-- BEGIN CONTENT -->
            <div class="col-md-12 col-sm-12">
                <h1>Shopping cart</h1>
                <div class="shopping-cart-page">
                    <?php if (!empty($shoppingCart)) { ?>
                        <div class="shopping-cart-data clearfix">
                            <div class="table-wrapper-responsive">
                                <table summary="Shopping cart">
                                    <tr>
                                        <th class="shopping-cart-ref-no">Domain Name</th>
                                        <th class="shopping-cart-ref-no-voice">Is Voice Domain</th>
                                        <th class="shopping-cart-quantity">YEARS</th>
                                        <th class="shopping-cart-price">Unit price</th>
                                        <th class="shopping-cart-total" colspan="2">Total</th>
                                    </tr>
                                    <?php                                  
                                    $domain_priceM = new domainprice();
                                    $domain_price = $domain_priceM->getDomainsPrice();
                                    $total = 0; 
                                    foreach ($this->shoppingCart as $key => $value) {
                                        if (!empty($value['domain'])) {
                                        $domainIndex = (isset($value['domain']))?substr($value['domain'], strrpos($value['domain'], '.') + 1) :substr($value, strrpos($value, '.') + 1);
                                        $total+= $value['total_price'];
                                        ?>
                                        <tr>
                                            <td class="shopping-cart-ref-no">
                                                <?php echo (isset($value['domain']))?$value['domain']:''; ?>
                                            </td>
                                            <td>
                                                <span class="glyphicon <?php echo ($value['is_voice_domain'] == 1) ? 'glyphicon-ok' : 'glyphicon-remove' ?>" 
                                                	aria-hidden="true"></span>
                                            </td>
                                            <td class="shopping-cart-quantity">
                                                <div class="product-quantity">
                                                	<?php $ss = 'out'; 
                                                	if (adfAuthentication::isLoggedIn()) {
                                                        $ss = 'in';
                                                    }?>
                                                    <select name="years" login="<?php echo $ss; ?>" class="select selectpicker" data-id-dropdown="<?php echo $key; ?>">
                                                        <option  value="1" <?php if ($value['quantity'] == '1' ) echo 'selected="selected"';?>>1 Year</option>
                                                        <option  value="2" <?php if ($value['quantity'] == '2' ) echo 'selected="selected"';?>>2 Years</option>
                                                        <option  value="3" <?php if ($value['quantity'] == '3' ) echo 'selected="selected"';?> >3 Years</option>
                                                        <option  value="5" <?php if ($value['quantity'] == '5' ) echo 'selected="selected"';?>>5 Years</option>
                                                        <option  value="10" <?php if ($value['quantity'] == '10' ) echo 'selected="selected"';?>>10 Years</option>
                                                    </select>
                                                     <!--<input id="product-quantity" type="text" value="1" readonly class="form-control input-sm">-->
                                                </div>
                                            </td>
                                            <td class="shopping-cart-price">
                                                <strong><span>$</span><span data-unit-id="<?php echo $key; ?>"> <?php echo $value['revised_price']; ?></span></strong>
                                            </td>
                                            <td class="shopping-cart-total">
                                                <strong>
                                                    <span>$</span>
                                                    <span class="domanTotalPrice" data-total-id="<?php echo $key; ?>">
                                                    <?php echo $value['total_price']; ?>
                                                    </span></strong>
                                            </td>
                                            <td class="del-goods-col">
                                                <?php if (adfAuthentication::isLoggedIn()) { ?>
                                                    <a class="del-goods" login="in" domain="<?php echo (isset($value['domain']))?$value['domain']:''; ?>"><i class="fa fa-times"></i></a>
                                                <?php } else { ?>
                                                    <a class="del-goods" login="out" domain="<?php echo (isset($value['domain']))?$value['domain']:''; ?>"><i class="fa fa-times"></i></a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php }} ?>
                                </table>

                            </div>

                            <div class="shopping-total">
                                <ul>
                                    <li class="shopping-total-price">
                                        <em>Total</em>
                                        <strong class="price"><span>$</span><span class="total_price_sum"><?php echo $total; ?></span></strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php } else { 
                    	echo '<meta http-equiv="refresh" content="0; URL=/public/default/searchDomain/">';
                    	?>
                        <div class="shopping-cart-data clearfix">
                            <p>Your shopping cart is empty!</p>
                        </div>
                    <?php } ?>
                    <?php if (adfAuthentication::isLoggedIn()) { ?>
                        <a href="/account/default/searchDomain" class="btn btn-default">FIND MORE DOMAINS<i class="fa fa-shopping-cart"></i></a>
                    <?php } else { ?>
                        <a href="/searchDomain" class="btn btn-default">FIND MORE DOMAINS<i class="fa fa-shopping-cart"></i></a>
                    <?php } ?>
                    <?php if (!empty($shoppingCart)) { ?>
                        <?php if (adfAuthentication::isLoggedIn()) { ?>
                            <a href="/account/default/payDomain" class="btn btn-primary" type="submit">Checkout <i class="fa fa-check"></i></a>
                        <?php } else { ?>
                            <a href="/loginORregister" class="btn btn-primary checkout" type="submit">Checkout <i class="fa fa-check"></i></a>
                            <?php
                            }
                        }
                        ?>
                </div>
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
    </div>
</div>
<?php include('footer.php'); ?>
