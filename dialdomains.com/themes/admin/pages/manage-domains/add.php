<?php adfView::addCss('/assets/css/style-domains.css'); ?>
<?php adfView::addJs('/assets/scripts/manage-domains/add.js'); ?>
<?php adfView::addJs('/assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js'); ?>
<?php include $this->tpl->header(); ?>
<?php if (!isset($domainProcessed)){
  $domainProcessed = false;
} ?>
<script type="text/template" id="customReadingTextTemplate">
  <label class="checkbox line customReadingsContainer">
    <i class="icon-ok-circle icon-green"></i>
    <input type="checkbox" class="customReadingCheckbox"> <input type="text" name="selectedReadings[]" placeholder="Other" class="m-wrap medium customReadingText" disabled="disabled"/>
  </label>
</script>
<div class="page-content">

  <div class="container-fluid">
    <?php include $this->tpl->header("/elements/domail_header.php"); ?>
    

    <?php echo $this->status->display(); ?>

    <div class="row-fluid">
      <div class="span12">
        <div class="portlet box blue">
          <div class="portlet-title">
            <div class="caption"><i class="icon-globe"></i>Domain Name</div>
          </div>
          <div class="portlet-body">
            <form action="/manage-domains/default/add" method="POST" class="form-horizontal form">
              <div class="row-fluid">
                <div class="span8 offset1">
                  <input type="text" placeholder="Domain name" name="domain" class="m-wrap huge" style="width:100% !important; height:40px !important;" value="<?php echo !empty($_POST['domain']) ? $_POST['domain'] : ''?>"/>
                </div>
                <div class="span1">
                  <button type="submit" class="btn green big">
                    <?php if (!$domainProcessed): ?>
                      Go
                    <?php else: ?>
                      Change
                    <?php endif; ?>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <?php if ($domainProcessed): ?>
      <div class="row-fluid">
        <div class="span12">
          <div class="portlet box blue">
            <div class="portlet-title">
              <div class="caption"><i class="icon-comment"></i>Readings</div>
            </div>
            <div class="portlet-body form">
              <?php if (!empty($readings)): ?>
                <form method="POST" action="/manage-domains/default/add" class="form-horizontal selectReadingsForm">
                  <input type="hidden" name="finalize_domain" value="<?php echo $_POST['domain']; ?>" />
                  <div class="row-fluid">
                    <?php $numberOfReadings = count($readings) + 1; ?>
                    <?php $customReadingRowAdded = false; ?>
                    <?php $readingsInColumn = ceil($numberOfReadings / 2); ?>
                    <?php for($column = 0; $column < 2; ++$column): ?>
                      <div class="span6 readingsColumn">
                        <?php for($row = 0; $row < $readingsInColumn; ++$row): ?>
                          <?php if (!empty($readings[$column * $readingsInColumn + $row])): ?>
                            <label class="checkbox line">
                              <?php if ($readings[$column * $readingsInColumn + $row]['availability'] == 'used'): ?>
                                <i class="icon-minus-sign icon-red"></i> <?php // TODO, ADD SOME popovers like mechanism to explain ?>
                              <?php elseif ($readings[$column * $readingsInColumn + $row]['availability'] == 'similarUsed'): ?>
                                <i class="icon-warning-sign icon-yellow"></i>
                              <?php else: ?>
                                <i class="icon-ok-circle icon-green"></i>
                              <?php endif; ?>
                              <input 
                                type="checkbox" 
                                value="<?php echo $readings[$column * $readingsInColumn + $row]['value']; ?>" name="selectedReadings[]" 
                                <?php if ($readings[$column * $readingsInColumn + $row]['availability'] === 'used'): ?>
                                  disabled="disabled"
                                <?php elseif (!empty($_POST['selectedReadings']) && in_array($readings[$column * $readingsInColumn + $row]['value'], $_POST['selectedReadings'])): ?>
                                  checked="checked"
                                <?php endif; ?>
                              > 
                              <?php echo $readings[$column * $readingsInColumn + $row]['display']; ?>
                            </label>
                            <?php elseif (!$customReadingRowAdded): ?>
                              <label class="checkbox line customReadingsContainer">
                                <i class="icon-ok-circle icon-green"></i>
                                <input type="checkbox" class="customReadingCheckbox"> <input type="text" name="selectedReadings[]" placeholder="Other" class="m-wrap medium customReadingText" disabled="disabled"/>
                                <?php $customReadingRowAdded = true; ?>
                              </label>
                            <?php endif; ?>
                          </label>
                        <?php endfor; ?>
                      </div>
                    <?php endfor; ?>
                  </div>
                  <div class="row-fluid">
                    <div class="span12"></div>
                  </div>
                  <div class="row-fluid">
                    <div class="span8 offset1">
                      <input type="text" placeholder="___-___-____ Phone Number" name="phoneNumber" id="phone_number" class="m-wrap huge" style="width:100% !important; height:40px !important;" value="<?php echo !empty($_POST['phone_number']) ? $_POST['phone_number'] : ''?>"/>
                    </div>
                  </div>
                  <div class="row-fluid">
                    <div class="span12"></div>
                  </div>
                  <div class="form-actions">
                    <button type="submit" class="btn blue"><i class="icon-ok"></i> Save</button>
                  </div>
                </form>
              <?php else: ?>
                No readings ;_;
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php
include $this->tpl->footer();
