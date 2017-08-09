    <?php include('header.php'); ?>

<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid fixed-row-spacer"></div>
<div class="container-fluid content">
	<div class="container content">

		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<?php
				$registration_attempt=false;
				
				$attempt_count=0;
				//if registration attempt was sucessful
				if($registration_attempt&&$attempt_count!=0){
					?>
					<div class="alert alert-success" role="alert">
						<h3><strong>Success !</strong> your account was succesfully created !</h3>
					</div>
					<?php					
				}

				//else if registration attempt failed
				if(!$registration_attempt&&$attempt_count!=0){
					?>
					<div class="alert alert-danger" role="alert">
						<h1>Your account could not be created.</h1>
					</div>
					<?php
				}

				//else if no registration attempt was made
				if ($attempt_count==0) {

				}
			
				?>

			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="row">

		<div class="col-md-2"></div>
			<div class="col-md-8">
				<img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
				alt="">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					<form class="form-signin" action="/login" method="post" name="login">
						<input type="text" class="form-control" placeholder="Username" name="username" required autofocus>
						<input type="password" class="form-control" placeholder="Password" name="password" required>
						<button class="btn btn-lg  btn-block gb" type="submit">
							Sign in</button>
							<label class="checkbox pull-left">
								<input type="checkbox" value="remember-me">
								Remember me
							</label>
							<a href="/home/forgotpassword" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
						</form>
						<a href="/home/register"   class="text-center new-account">Create an account </a>
					</div>
					<div class="col-md-4"></div>

				</div>
				<div class="col-md-2"></div>

			</div>

		</div>
	</div>
	<?php include('footer.php'); ?>