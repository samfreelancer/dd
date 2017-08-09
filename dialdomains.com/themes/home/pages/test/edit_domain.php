<?php include('header.php'); ?>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<?php echo $this->status->display(); ?>
<div class="content">
    <div class="row-fluid">
        <div class="span12">
            <div class="portlet box blue">

                <div class="portlet-body">
                    <form action="/account/default/checkDomain" method="POST" class="form-horizontal form">
                        <div class="portlet-title">
                            <div class="caption"><i class="icon-globe"></i>Domain Name</div>
                        </div>
                        <div class="row-fluid">
                            <div class="span8 offset1">
                                <?php echo lib_input("domain", $method = 'post', $class = "m-wrap huge"); ?>
                            </div>
                            <input type="hidden" name="id" value="<?php echo lib_request('id') ?>" >
                            <div class="span1">
                                <button type="submit" class="btn green big">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <a href="/account/default/domains">Back</a>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>