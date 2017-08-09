<?php adfView::addCSS('/assets/css/pages/error.css'); ?>

<?php include $this->tpl->header(); ?>

<div class="page-content" style="min-height:1112px !important">

  <div class="container-fluid">

    <div class="row-fluid">
      <div class="span12">

        <?php include $this->tpl->block('other', 'style-customizer.php'); ?>

        <h3 class="page-title">
          403 <small> Forbidden</small>
        </h3>
        <ul class="breadcrumb">
          <li>
            <i class="icon-home"></i>
            <a href="/index">Home</a>
            <i class="icon-angle-right"></i>
          </li>
          <li><a href="#">403 Forbidden</a></li>
        </ul>

      </div>
    </div>

    <div class="row-fluid">
      <div class="span12 page-404">
        <div class="number">
          403
        </div>
        <div class="details">
          <h3>Access Forbidden</h3>
          <p>
            You are not authorized to view the requested resource.  If you feel this is an error, please contact your system administrator.<br>
            <a href="/index">Return home</a>.
          </p>
        </div>
      </div>
    </div>

  </div>

</div>

<?php include $this->tpl->footer(); ?>