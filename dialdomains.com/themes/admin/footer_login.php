    <div class="copyright">
      2013 &copy; Metronic - Admin Dashboard Template.
    </div>

    <?php adfView::addJS("assets/plugins/jquery-1.10.1.min.js", 10);?>
    <?php adfView::addJS("assets/plugins/jquery-migrate-1.2.1.min.js", 10);?>
    <!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <?php adfView::addJS("assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js", 10);?>
    <?php adfView::addJS("assets/plugins/bootstrap/js/bootstrap.min.js", 10);?>
    <?php adfView::addJS("assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js", 10);?>
    <!--[if lt IE 9]>
    <script src="assets/plugins/excanvas.min.js"></script>
    <script src="assets/plugins/respond.min.js"></script>
    <![endif]-->
    <?php adfView::addJS("assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js", 10);?>
    <?php adfView::addJS("assets/plugins/jquery.blockui.min.js", 10);?>
    <?php adfView::addJS("assets/plugins/jquery.cookie.min.js", 10);?>
    <?php adfView::addJS("assets/plugins/uniform/jquery.uniform.min.js", 10);?>

    <?php adfView::addJS("assets/plugins/jquery-validation/dist/jquery.validate.min.js", 10);?>
    <?php adfView::addJS("assets/plugins/backstretch/jquery.backstretch.min.js", 10);?>
    <?php adfView::addJS("assets/plugins/select2/select2.min.js", 10);?>

    <?php adfView::addJS("assets/scripts/app.js", 10);?>
    <?php adfView::addJS("assets/scripts/login-soft.js", 10);?>

    <?php adfView::getJS(); ?>

    <script>
      jQuery(document).ready(function() {
        App.init();
        Login.init();
      });
    </script>

  </body>

</html>