<?php include('header.php'); ?>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<div class="content">
	<div class="container-fluid ">
		<div class="container ">
		<div class="col-md-4"></div>
		<div class="col-md-4" align="center"> <img alt="User Pic" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=100" class="img-circle"> <br/><br/></div>     
		
		<div class="col-md-4"></div>
			<div class="row">
				<div class=" col-md-12 "> 
					<table class="table table-striped ">
						<tbody>
							<tr>
								<td>Username:</td>
								<td><?php echo $user['username']; ?></td>
							</tr>									
							<tr>
								<td>First Name:</td>
								<td><?php echo $user['first_name']; ?></td>
							</tr>
							<tr>
								<td>Last Name:</td>
								<td><?php echo $user['last_name']; ?></td>
							</tr>
							<tr>
								<td>Email:</td>
								<td><?php echo $user['email']; ?></td>
							</tr>
                                                        <tr>
                                                                <td>Domain password</td>
                                                                <td><?php echo $user['domain_password']; ?></td>
                                                        </tr>
							<tr>
								<td>Phone Number</td>
								<td><?php echo $user['phone']; ?></td>
							</tr>			
							<tr>
								<td>phone region</td>
								<td><?php echo $user['phone_region']; ?></td>
							</tr>			
							<tr>
								<td>Address</td>
								<td><?php echo $user['address']; ?></td>
							</tr>									
							<tr>
								<td>City / Town:</td>
								<td><?php echo $user['city']; ?></td>
							</tr>
							<tr>
								<td>State / Province:</td>
								<td><?php echo $user['state']; ?></td>
							</tr>
							<tr>
								<td>Country:</td>
								<td><?php echo $user['country']; ?></td>
							</tr>
							<tr>
								<td>Zip / Postal Code:</td>
								<td><?php echo $user['zipcode']; ?></td>
							</tr>
							<!--<tr>
								<td>Address Line 2:</td>
								<td><?php //echo $user['city']; ?></td>
							</tr>									
							<tr>
								<td>Date of Birth</td>
								<td>VALUE GOES HERE</td>
							</tr>
							<tr>
								<td>Verification Status:</td>
								<td>Value GOES HERE</td>
							</tr>-->
						</tbody>
					</table>
					<a class="btn gb" href="/account/default/edit" ><i class="fa fa-gear"></i> Edit Profile</a>
					<a class="btn gb" href="/account/default/changePassword" ><i class="fa fa-gear"></i> Change Password</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>

<!-- Edit Profile Modal -->
<div class="modal fade" id="edit" tabindex="1" role="dialog" aria-labelledby="edit" area-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" area-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title g" id="myModalLabel" ><b>Edit Profile</b></h4>
			</div>
                        <form class="form-horizontal" action="/account/default/edit"  method="post">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<div class="account-wall">
							
								<!-- full-name input-->
								<div class="input-group">
									<label class="input-label">Username</label>
									<div class="inputs">
										<input id="full-name" value="<?php echo $user['username']; ?>" name="username" type="text" placeholder="full name"
										class="input-xlarge">
										<p class="help-block"></p>
									</div>
								</div>
								<div class="input-group">
									<label class="input-label">First Name</label>
									<div class="inputs">
										<input id="full-name" value="<?php echo $user['first_name']; ?>" name="first_name" type="text" placeholder="full name"
										class="input-xlarge">
										<p class="help-block"></p>
									</div>
								</div>
								<div class="input-group">
									<label class="input-label">Last Name</label>
									<div class="inputs">
										<input id="full-name" value="<?php echo $user['last_name']; ?>" name="last_name" type="text" placeholder="full name"
										class="input-xlarge">
										<p class="help-block"></p>
									</div>
								</div>
								<div class="input-group">
									<label class="input-label">Email</label>
									<div class="inputs">
										<input id="full-name" value="<?php echo $user['email']; ?>" name="email" type="text" placeholder="Email"
										class="input-xlarge">
										<p class="help-block"></p>
									</div>
								</div>
								<div class="input-group">
									<label class="input-label">Domain Password</label>
									<div class="inputs">
										<input id="full-name" value="<?php echo $user['domain_password']; ?>" name="domain_password" type="text" placeholder="Domain Password"
										class="input-xlarge">
										<p class="help-block"></p>
									</div>
								</div>
								<!-- address-line1 input-->
								<div class="input-group">
									<label class="input-label">Address</label>
									<div class="inputs">
										<input id="address-line1" value="<?php echo $user['address']; ?>" name="address" type="text" placeholder="Address"
										class="input-xlarge">
									</div>
								</div>
								<div class="input-group">
									<label class="input-label">Phone</label>
									<div class="inputs">
										<input id="phone" value="<?php echo $user['phone']; ?>" name="phone" type="text" placeholder="Phone">
									</div>
								</div>
								<div class="input-group">
									<label class="input-label">Phone Region</label>
									<div class="inputs">
										<input id="phone_region" value="<?php echo $user['phone_region']; ?>" name="phone_region" type="text" placeholder="Phone region">
									</div>
								</div>
								<!-- city input-->
								<div class="input-group">
									<label class="input-label">City / Town</label>
									<div class="inputs">
										<input id="city" name="city" value="<?php echo $user['city']; ?>" type="text" placeholder="city" class="input-xlarge">
										<p class="help-block"></p>
									</div>
								</div>
								<!-- region input-->
								<div class="input-group">
									<label class="input-label">State/Province/Region</label>
									<div class="inputs">
										<input id="region" name="state" value="<?php echo $user['state']; ?>" type="text" placeholder="state / province / region" class="input-xlarge">
										<p class="help-block"></p>
									</div>
								</div>
								<!-- postal-code input-->
								<div class="input-group">
									<label class="input-label">Zip / Postal Code</label>
									<div class="inputs">
										<input id="postal-code" name="zipcode" value="<?php echo $user['zipcode']; ?>" type="text" placeholder="zip or postal code"
										class="input-xlarge">
										<p class="help-block"></p>
									</div>
								</div>
								<!-- country select -->
								<div class="input-group">
									<label class="input-label">Country</label>
									<div class="inputs">
										<select id="country" name="country" class="input-xlarge">
											<option value=""  <?php echo ('' == $user['country'])?'selected="selected"':''?>>(please select a country)</option>
											<?php foreach ($countries as $key => $value) { ?>
                                                                                            <option  value="<?php echo $value; ?>" <?php echo ($value== $user['country'])?'selected="selected"':''?>><?php echo $value; ?></option>
                                                                                        <?php } ?>
										</select>
									</div>
								</div>
							
						</div>
					</div>
					<div class="col-md-3"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn gb" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn gb" >Apply</button>
			</div>
                    </form>
		</div>
	</div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="change_password" tabindex="1" role="dialog" aria-labelledby="change_password" area-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
                    <form class="form-horizontal" action="/account/default/changePassword"  method="post">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" area-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title g" id="myModalLabel" ><b>Change Password</b></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<div class="account-wall">
							<form class="form-horizontal">
							 <div class="form-group">
							<input  type="password" name="old_password" placeholder=" old password" />
							</div>
							 <div class="form-group">
							<input type="password" name="old_password_confirm" placeholder=" old password confirm" />
							</div>
							 <div class="form-group">
							<input  type="password" name="new_password" placeholder="new password" />
							</div>
							 <div class="form-group">
							<input  type="password" name="new_password_confirm" placeholder=" new password confirm" />
							</div>
							
						</div>
					</div>
					<div class="col-md-3"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn gb" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn gb">Apply</button>
			</div>
                    </form>
		</div>
	</div>
</div>

