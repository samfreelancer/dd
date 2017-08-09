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

    <div class="row-fluid">
      <?php include $this->tpl->header("/elements/domail_header.php"); ?>
    </div>

    <?php echo $this->status->display(); ?>

    <div class="row-fluid">
      <div class="span12">
        <div class="portlet box blue">
          <div class="portlet-title">
            <div class="caption"><i class="icon-globe"></i>Domain Name</div>
          </div>
          <div class="portlet-body">
            <form action="/manage-domains/default/edit" method="POST" class="form-horizontal form">
              <div class="row-fluid">
                <div class="span8 offset1">
                  <?php
                  /**
                   * this should be
                   * <?php echo lib_input($name, $method = 'post', $class = null, $readonly = false); ?>
                   *
                   * css for these should be the same and set in a css file for consitency
                   *
                   * The placeholder tag doesn't work on some versions of IE still in use.
                   */
                 
                  ?>
                  <?php echo lib_input("domain", $method = 'post', $class = "m-wrap huge"); ?>
                </div>
                <div class="span1">
                  <button type="submit" class="btn green big">
                      Change
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>


  </div>
</div>

<?php
include $this->tpl->footer();
