<?php include('header.php'); ?>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<?php echo $this->status->display(); ?>
<div class="content">
	<div class="container-fluid ">
		<div class="container ">
		<div class="col-md-4"></div>
		<div class="col-md-4"></div>
                <a href="/account/default/searchDomain">Search Domain</a>
		<div class="col-md-4"></div>
			<div class="row">
				<div class=" col-md-12 "> 
                                    <?php if($domains){ ?>
                                        
					<table class="table table-striped ">
                                            <tr>
                                                <thead>
                                                    <th>Domain name</th>
                                                    <th>Voice Domain</th>
                                                    <th>Added date</th>
                                                    <th>Period</th>
                                                    <th>Edit</th>
                                                    <th>Status</th>
                                                    <th>Renew</th>
                                                    <th>Info</th>
                                                    <th>Delete</th>
                                                </thead>
                                            </tr>
						<tbody>
                                                    <?php foreach ($domains as $key => $value) { ?>
							<tr>
                                                            <td><?php echo $value['domain'];?></td>
                                                            <td><?php echo $value['voice_domain'];?></td>
                                                            <td><?php echo $value['addedon'];?></td>
                                                            <?php if ($value['registered']){ ?>
                                                                <td><?php echo $value['period']; ?> </td>
                                                                <td>
                                                                    <a href="/account/default/editDomain?id=<?php echo $value['id'];?>">Edit</a>
                                                                </td>
                                                                <td><?php if($value['domain']){
                                                                 if($value['purchased']){ ?>
                                                                    Purchased
                                                                <?php }  else { ?>
                                                                    <a href="/account/default/domainNamePrivacyPurchase?id=<?php echo $value['id'];?>">Purchase</a>
                                                                <?php }} ?>
                                                                </td>
                                                                <td>
                                                                    <?php if($value['domain']){ ?>
                                                                    <a href="/account/default/renewDomain?id=<?php echo $value['id'];?>">Renew</a>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <?php if($value['domain']){ ?>
                                                                    <a href="/account/default/domainInfo?id=<?php echo $value['id'];?>">Info</a>
                                                                    <?php } ?>
                                                                </td>
                                                            <?php }  else { ?>
                                                                <td><a href="/account/default/registerDomain?ids=<?php echo htmlspecialchars(json_encode(array($value['id'])));?>">Register Domain</a></td>
                                                                <td></td><td></td><td></td><td></td>
                                                            <?php } ?>
                                                            <td>
                                                                <a href="/account/default/deleteDomain?id=<?php echo $value['id'];?>">Delete</a>
                                                            </td>
							</tr>									
                                                    <?php } ?>
							
						</tbody>
					</table>
                                    <?php } ?>
                                   
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>

