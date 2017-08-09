<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->

<head>
  <meta charset="utf-8" />
  <title>System Management</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta content="" name="description" />
  <meta content="" name="author" />

  <?php adfView::addCSS("/assets/plugins/bootstrap/css/bootstrap.min.css", 10);?>
  <?php adfView::addCSS("/assets/plugins/bootstrap/css/bootstrap-responsive.min.css", 10);?>
  <?php adfView::addCSS("/assets/plugins/font-awesome/css/font-awesome.min.css", 10);?>
  <?php adfView::addCSS("/assets/css/style-metro.css", 10);?>
  <?php adfView::addCSS("/assets/css/style.css", 10);?>
  <?php adfView::addCSS("/assets/css/style-responsive.css", 10);?>
  <?php adfView::addCSS("/assets/css/themes/default.css", 10);?>
  <?php adfView::addCSS("/assets/plugins/uniform/css/uniform.default.css", 10);?>r
  <?php echo adfView::getCSS(); ?>

  <link rel="shortcut icon" href="favicon.ico" />
</head>

  <body class="page-header-fixed">

    <?php include $this->tpl->block('navigation', 'top-navigation-bar.php'); ?>

    <div class="page-container">

      <?php include $this->tpl->block('navigation', 'side-menu.php'); ?>





