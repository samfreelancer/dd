<?php include('header.php'); ?>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<?php echo $this->status->display(); ?>
<div class="content">
    <div class="row-fluid">
      <div class="span12">
        <div class="portlet box blue">
        
          <div class="portlet-body">
            <form action="/account/default/searchDomain" method="POST" class="form-horizontal form">
                <div class="span8 offset1">
                    <?php echo lib_input("domain", $method = 'post', $class = "m-wrap huge"); ?>
                </div>
                <button type="submit" class="btn ">
                    <?php echo (lib_post('domain'))?'Search again':'Search domain' ?>
                </button>
            </form>
            <div class="container-fluid fixed-row-spacer"></div>
          <?php if(lib_is_post()){ ?>
              <?php if($isAvaliable){ ?>

                    <form action="/account/default/addToCart" method="POST" class="form-horizontal form">
                        <div class="portlet-title">
                            <div class="caption"><i class="icon-globe"></i>"<?php echo lib_post('domain'); ?>" Domain Name Available</div>
                        </div>
                        <input name="finalize_domain" type="hidden" value="<?php echo lib_post('domain'); ?>">
                        
                        <button type="submit" class="btn green big">
                            Continue to Cart
                        </button>
                    </form>
                 <?php }else{ ?>
                    <form action="/account/default/addToCart" method="POST" class="form-horizontal form">
                        <div class="portlet-title">
                        <div class="caption"><i class="icon-globe"></i>Domain Name Not Available</div>
                        </div>
                        <?php if(!empty($nameGenerates)){ ?>
                            <div>Try one of these</div>
                        <?php foreach ($nameGenerates as $key => $value) { ?>
                                <input type="radio" name="finalize_domain" value="<?php echo $value; ?>"><?php echo $value; ?><br>
                            <?php  } ?>
                        <?php } ?>
                        <button type="submit" class="btn green big">
                            Continue to Cart
                        </button>
                    </form>
                 <?php } ?>

          <?php } ?>
          </div>
        </div>
      </div>
    </div>
</div>
<?php include('footer.php'); ?>