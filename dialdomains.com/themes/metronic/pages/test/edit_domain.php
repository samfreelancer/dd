<?php include('header.php'); ?>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<?php echo $this->status->display(); ?>
<div class="content">
    <div class="row-fluid">
        <div class="span12">
            <div class="portlet box blue">

                <div class="portlet-body container">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <form action="/account/default/editDomain" method="POST" class="form-horizontal form">
                                <div class="portlet-title">
                                    <div class="caption"><i class="icon-globe"></i>Domain Name</div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span8 offset1">
                                        <?php echo lib_input("domain", $method = 'post', $class = "m-wrap huge"); ?>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $data['id'] ?>" >
                                    <div class="span1">
                                        <button type="submit" class="btn green big">
                                            Change
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <form action="/account/default/editDomain" method="POST" class="form-horizontal form">
                                <input type="hidden" name="id" value="<?php echo $data['id'] ?>" >
                                <div class="portlet-title">
                                    <div class="caption"><i class="icon-globe"></i>Voice Domain Name</div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span8 offset1">
                                        <?php echo lib_input("voice_domain", $method = 'post', $class = "m-wrap huge"); ?>
                                    </div>
                                </div>
                                <!--<div class="portlet-title">
                                    <div class="caption"><i class="icon-globe"></i>Voice Domain Phone</div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span8 offset1">
                                <?php //echo lib_input("phone_number", $method = 'post', $class = "m-wrap huge"); ?>
                                    </div>
                                </div>-->
                                <div class="row-fluid">
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
</div>
<?php include('footer.php'); ?>