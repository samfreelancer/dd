<?php include('header.php'); ?>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<?php echo $this->status->display(); ?>
<div class="content">
    <div class="row-fluid">
      <div class="span12">
        <div class="portlet box blue">
        
          <div class="portlet-body">
          <?php if(isset($isAvaliable) && $isAvaliable && isset($domainName)){ ?>
            <form action="/account/default/registerDomain" method="POST" class="form-horizontal form">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-globe"></i>Domain Name Available</div>
                </div>
              <div class="row-fluid">
                <div class="span8 offset1">
                  <input type="text" placeholder="Domain name" name="finalize_domain" class="m-wrap huge"  value="<?php echo isset($domainName)?$domainName:''; ?>"/>
                </div>
                <div class="span1">
                  <button type="submit" class="btn green big">
                    Continue
                  </button>
                </div>
              </div>
            </form>
          <?php }else{ ?>
            <form action="/account/default/addDomain" method="POST" class="form-horizontal form">
                <div class="portlet-title">
                    <div class="caption">
                        <?php if(isset($_POST['domain'])){ ?>
                        <?php if(isset($notAvailable)){ ?>
                            <p>Sorry, that name is not available for registration. Please try again.</p>
                        <?php }else{ ?>
                            <p>Sorry <?php echo $domainName; ?> is taken</p>
                        <?php }}else{ ?>
                            <p>Enter domain name</p>
                        <?php } ?>
                    </div>
                </div>
              <div class="row-fluid">
                <div class="span8 offset1">
                    <input type="text"  placeholder="Domain name" name="domain" class="m-wrap huge"  value="<?php echo (isset($domainName) && $domainName)?$domainName:''; ?>"/>
                </div>

                <div class="span1">
                  <button type="submit" class="btn green big">
                    <?php if(isset($_POST['domain'])){ ?>
                        Search again
                    <?php }else{ ?>
                        Search Domain
                    <?php } ?>
                      
                  </button>
                </div>
              </div>
            </form>
      
          <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <?php if ($domainProcessed){ ?>
     <form action="/account/default/registerDomain" method="POST" class="form-horizontal form">
      <div class="row-fluid">
        <div class="span12">
          <div class="portlet box blue">

            <?php if (!empty($readings)){ ?>
            <div class="portlet-title">
                <h3>Use one of these</h3>
              <!--<div class="caption"><i class="icon-comment"></i>Readings</div>-->
            </div>

            <div class="portlet-body form">
                  <div class="row-fluid">
                    <?php $numberOfReadings = count($readings) + 1; ?>
                    <?php $customReadingRowAdded = false; ?>
                    <?php $readingsInColumn = ceil($numberOfReadings / 2); ?>
                    <?php for($column = 0; $column < 2; ++$column): ?>
                      <div class="span6 readingsColumn">
                        <?php for($row = 0; $row < $readingsInColumn; ++$row){ ?>
                          <?php if (!empty($readings[$column * $readingsInColumn + $row])): ?>
                            <label class="checkbox line">
                              <input type="radio" value="<?php echo $readings[$column * $readingsInColumn + $row]['value']; ?>" name="finalize_domain" >
                              <?php echo $readings[$column * $readingsInColumn + $row]['display']; ?>
                            </label>
                            <?php endif; ?>
                          </label>
                        <?php }; ?>
                      </div>
                    <?php endfor; ?>
                  </div>
                  <button type="submit" class="btn green big">
                    Continue
                  </button>
                
            <?php }else{ ?>
                No readings 
            <?php }; ?>
            </div>
          </div>
        </div>
      </div>
    </form>
    <?php } ?>
</div>
<?php include('footer.php'); ?>