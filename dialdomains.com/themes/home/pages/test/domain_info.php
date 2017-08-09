<?php include('header.php'); ?>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<?php echo $this->status->display(); ?>
<div class="content">
    <div class="row-fluid">
        <div class="span12">
            <div class="portlet box blue">
                <div class="portlet-body">
                    <?php foreach ($info as $key => $value) { ?>
                        <div><?php echo $key.' : '.$value; ?></div>
                   <?php } ?>
                </div>
            </div>
            <a href="/account/default/domains">Back</a>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>