<?php include('header.php'); ?>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<?php echo $this->status->display(); ?>
<div class="content">
    <div class="row-fluid">
      <div class="span12">
        <div class="portlet box blue">
        
          <div class="portlet-body">
          <?php if($isAvaliable){ ?>
            <form action="/account/default/registerDomain" method="POST" class="form-horizontal form">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-globe"></i>Domain Name Available</div>
                </div>
              <div class="row-fluid">
                <div class="span8 offset1">
                    <?php echo lib_input("finalize_domain", $method = 'post', $class = "m-wrap huge"); ?>
                    <input type="hidden" name ="id" value="<?php echo $id; ?>">
                </div>
                <div class="span1">
                  <button type="submit" class="btn green big">
                    Register Domain
                  </button>
                </div>
              </div>
            </form>
          <?php }else{ ?>
            <form action="/account/default/addDomain" method="POST" class="form-horizontal form">
                <div class="portlet-title">
                    <div class="caption">

                    </div>
                </div>
              <div class="row-fluid">
                <div class="span8 offset1">
                    <?php echo lib_input("domain", $method = 'post', $class = "m-wrap huge"); ?>
                </div>
                  
                <div class="span1">
                  <button type="submit" class="btn green big">
                        Add Domain
                  </button>
                </div>
              </div>
            </form>
      
          <?php } ?>
          </div>
        </div>
      </div>
    </div>
</div>
<?php include('footer.php'); ?>