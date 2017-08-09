<div class="modal fade" id="commonModal" tabindex="-1" role="dialog"
	aria-labelledby="commonModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="addDnsModal" tabindex="-1" role="dialog"
	aria-labelledby="addDnsModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Add DNS</h4>
			</div>
			<form name="add-dns-frm" id="add-dns-frm" method="post" action="">
				<div class="modal-body">
					<div class="form-group row">
						<label for="hostname" class="col-2 col-form-label"><strong>Hostname</strong> *</label>
						<div class="col-10">
							<input class="form-control required" type="text" autocomplete="off" value="" name="hostname" id="hostname">
						</div>
					</div>
					<div class="form-group row">
						<label for="type" class="col-2 col-form-label"><strong>Type</strong> *</label>
						<div class="col-10">
							<input class="form-control required" type="text" autocomplete="off" value="" name="type" id="type">
						</div>
					</div>
					<div class="form-group row">
						<label for="content" class="col-2 col-form-label"><strong>Content</strong> *</label>
						<div class="col-10">
							<input class="form-control required" type="text" autocomplete="off" value="" name="content" id="content">
						</div>
					</div>
					<div class="form-group row">
						<label for="ttl" class="col-2 col-form-label"><strong>TTL</strong> *</label>
						<div class="col-10">
							<input class="form-control required" type="number" min="0" max="1000" step="1" value="" name="ttl" id="ttl">
						</div>
					</div>
					<div class="form-group row">
						<label for="priority" class="col-2 col-form-label"><strong>Priority</strong> *</label>
						<div class="col-10">
							<input class="form-control required" type="number" min="0" max="100" step="1" value="" name="priority" id="priority">
						</div>
					</div>
    			</div>
    			<div class="modal-footer">
    				<button type="submit" class="btn btn-primary">Submit</button>
    				<button type="button" class="btn btn-primary add-dns-go-back">Go Back</button>
    			</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="updatePhoneNumberModal" tabindex="-1" role="dialog"
	aria-labelledby="updatePhoneNumberModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Modify Phone Number</h4>
			</div>
			<form name="update-phone-frm" id="update-phone-frm" method="post" action="">
				<input type='hidden' name="domain" id="domain" value='' />
				<div class="modal-body">
					<div class="form-group row">
						<label for="hostname" class="col-2 col-form-label"><strong>Phone Number</strong> *</label>
						<div class="col-10">
							<input class="form-control required pattern" type="tel" pattern="/^\d{3}[\-]\d{3}[\-]\d{4}$/" placeholder="Phone Number (Format: 999-999-9999)" title="Phone Number (Format: 999-999-9999)" autocomplete="off" value="" name="phone_number" id="phone_number">
						</div>
					</div>
    			</div>
    			<div class="modal-footer">
    				<button type="submit" class="btn btn-primary">Update</button>
    			</div>
			</form>
		</div>
	</div>
</div>