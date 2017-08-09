<?php include('header.php'); ?>
<div
	class="container-fluid fixed-row-spacer"></div>
<div
	class="container-fluid fixed-row-spacer"></div>
<div class="content">
	<div class="container-fluid margin-bottom-20">
		<div class="container ">
		<?php echo $this->status->display(); ?>
		<?php if($domains !== FALSE && is_array($domains) && count($domains) > 0) { ?>
			<div class="row">
				<div class="col-md-12">
					<div class="jumbotron">
						<p>List of domains you own!</p>
					</div>
				</div>
			</div>

			<div class="row padding-top-10">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">Domains List</div>

						<table class="table">
							<thead>
								<tr>
									<th>Domain Name</th>
									<th>Is Voice Domain</th>
									<th>Order Id</th>
									<th>Validity Period</th>
									<th>Actions</th>
								</tr>
							</thead>

							<tbody>
							<?php foreach ($domains as $domain) { ?>
								<tr>
									<td><?php echo $domain['domain']; ?></td>
									<td>
                                        <span class="glyphicon <?php echo ($domain['is_voice_domain'] == 1) ? 'glyphicon-ok' : 'glyphicon-remove' ?>" 
                                        	aria-hidden="true"></span>
                                    </td>
									<td><?php echo $domain['orderid']; ?></td>
									<td><?php echo lib_convert_date_show($domain['period']); ?></td>
									<td>
										<div class="btn-group">
											<button type="button" class="btn btn-primary dropdown-toggle"
												data-toggle="dropdown" aria-haspopup="true"
												aria-expanded="false">Click Here</button>
											<div class="dropdown-menu">
												<?php 
            									if (!empty($domain['domain']) && $domain['is_voice_domain'] == 0) {
            									    ?>
												<a class="dropdown-item domain_name" href="javascript:void(0)">Manage Nameserver</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item dns_name" href="javascript:void(0)">Manage DNS</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item domain_renew <?php echo ($domain['auto_renew'] == 1) ? "text-success" : 'text-danger' ; ?>" href="javascript:void(0)"
												<?php 
												    if($domain['auto_renew'] == 1) {
												        echo "title = 'Click to switch off'";   
												    } else {
												        echo "title = 'Click to switch on'";
												    }
												?>
												>
												<?php 
												    if($domain['auto_renew'] == 1) {
												        echo "Auto Renew On";   
												    } else {
												        echo "Auto Renew Off";
												    }
            									} else {
            									?>
            									   <a class="dropdown-item phone_number" data-phone="<?php echo $domain['phone_number']; ?>" href="javascript:void(0)">Update Phone Number</a>
            									<?php  
            									}
												?>
												</a>
											</div>
										</div> 
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php }else { ?>
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="quote-v1-inner margin-top-10">
						<form method="post" action="/searchDomain">
							<div class="input-group">
								<input type="text" name="domain" class="form-control"
									placeholder="Search for domain..."> <span
									class="input-group-btn"> <input class="btn btn-default"
									type="submit" value="Search">
								</span>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
			<?php include('footer.php');
			include('modal.php');
			?>
