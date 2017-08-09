<?php
$search_term = 'domain_name';
?>


<div class="row">
	<div class="col-md-4" >
		Filter

<div style="margin-bottom:50px;">
			<input type="range"  min="0" value="0" max="10" step="1">
			<label>Filter Range Value Slider</label>
</div>

<div style="margin-bottom:50px;">
		<input type="text" placeholder="Filter by"><button>Apply</button>
</div>
<div style="margin-bottom:50px;">
	<style type="text/css">
	</style>
		<ul >
			<li style="color:black;"><input type="checkbox"><label>.com</label></li><br/>
			<li style="color:black;"><input type="checkbox"><label>.us</label></li><br/>
			<li style="color:black;"><input type="checkbox"><label>.net</label></li><br/>
			<li style="color:black;"><input type="checkbox"><label>.org</label></li><br/>
		</ul>
</div>

	</div>
	<div class="col-md-8">
		Results
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Domain</th>
					<th>Price</th>
					<th>Buying Options</th>
				</tr>
			</thead>
			<tbody>

				<?php
				for($int =0; $int <10; $int++){
					?>
					<tr>
						<td><?=$search_term?></td>
						<td>$10.99</td>
						<td><button type="button" class="btn btn-success">Add to Cart</button></td>
					</tr>
					<?php
				}
				?>

			</tbody>
		</table>
	</div>

</div>