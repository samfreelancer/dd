<?php include('header.php'); ?>

<div  class="container-fluid banner">
	<div>
            <?php
                    if(isset($isAvaliable)){
                            if($isAvaliable){  ?>
                                    <div class="alert " role="alert">
                                             <h1 id="banner"><b>Success ! THIS DOMAIN IS AVAILABLE:</b></h1>
                                    </div>
                                    				
                        <?php }else{   ?>
                                    <div class="alert " role="alert">
                                             <h1 id="banner"><b>SORRY, THIS DOMAIN ISN'T AVAILABLE</b></h1>
                                    </div>
                                    <?php if (!empty($nameGenerates)) { ?>
                                        <h3>Use one of these</h3>
                                        <?php foreach ($nameGenerates as $key => $value) { ?>
                                             <span><?php echo $value; ?>, </span>
                                        <?php } ?>
                                    <?php } ?>
                           <?php }
                    }else{ ?>
                        <div class="container-fluid fixed-row-spacer"></div>
                        <div class="container-fluid fixed-row-spacer"></div>
                        <div class="container-fluid fixed-row-spacer"></div>
                    <?php }
            ?>
		<!--<h1 id="banner"><b>Your Website Awaits...</b></h1>-->
		<form class="tb" action="/home/searchDomain" method="post">
			<span><input type="text" value="<?php echo (isset($domain)?$domain:''); ?>" name="domain_name"  placeholder=" Find your domain name" required><input class="gb" type="submit" value="Search"></span>
			<div style="text-align:right; margin-right:auto;"><br/>Dialdomains Patented Advanced Search Tool</div>
		</form>
	</div>
</div>
<!--content-->
<div class="container-fluid content">
	<div class="">
		<div class="row">
			<div class="col-md-4 bb">L</div>
			<div class="col-md-4 gb">M</div>
			<div class="col-md-4 rb">R</div>
		</div>
		  <div class="container-fluid fixed-row-spacer"></div>
		<div class="row">
			<div class="col-md-6 gb">
				<h1>Headline</h1>
				<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
				<button class="btn">Call to Action</button>				
			</div>
			<div class="col-md-6 bb">
				<h1>Headline</h1>
				<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
				<button class="btn">Call to Action</button>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 ">L</div>
			<div class="col-md-4 ">M</div>
			<div class="col-md-4 ">R</div>
		</div>		
	</div>
</div>
<!--end content-->

<?php include('footer.php'); ?>