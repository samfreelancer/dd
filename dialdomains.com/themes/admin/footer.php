    </div>

    <div class="footer">
      <div class="footer-inner">
        System Management &copy; Copyright 2012-<?php echo date("Y"); ?>
      </div>
      <div class="footer-tools">
        <span class="go-top">
          <i class="icon-angle-up"></i>
        </span>
      </div>
    </div>

    <?php adfView::addJS("/assets/plugins/jquery-1.10.1.min.js", 10);?>
    <?php adfView::addJS("/assets/plugins/jquery-migrate-1.2.1.min.js", 10);?>
    <!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <?php adfView::addJS("/assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js", 10);?>
    <?php adfView::addJS("/assets/plugins/bootstrap/js/bootstrap.min.js", 10);?>
    <?php adfView::addJS("/assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js", 10);?>
    <!--[if lt IE 9]>
    <script src="/assets/plugins/excanvas.min.js"></script>
    <script src="/assets/plugins/respond.min.js"></script>
    <![endif]-->
    <?php adfView::addJS("/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js", 10);?>
    <?php adfView::addJS("/assets/plugins/jquery.blockui.min.js", 10);?>
    <?php adfView::addJS("/assets/plugins/jquery.cookie.min.js", 10);?>
    <?php adfView::addJS("/assets/plugins/uniform/jquery.uniform.min.js", 10);?>

    <?php adfView::addJS("/assets/scripts/app.js", 10);?>

    <?php adfView::getJS(); ?>

    <script>
      jQuery(document).ready(function() {
        App.init(); // initlayout and core plugins
      });
    </script>

  </body>
</html>