<!DOCTYPE html>
<html lang="en">

		<?php require_once ("head-utils.php");?>

	<body>
		<div class="container p-5">
			<div class="row justify-content-center">
				<h2>Volunteer Sign-Up</h2>
			</div>
			<div class="col-lg-8 offset-lg-2">
				<form id="vol-sign-up" action="../public_html/api/volunteer-sign-up/index.php" method="post">
					<small id="formRequired" class="form-text text-danger">*Required fields</small>
					<div class="form-group">
						<label for="volunteerName">Name <span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="volunteerName" placeholder="Full name">
					</div>
					<div class="form-group">
						<label for="volunteerEmail">Email address <span class="text-danger">*</span></label>
						<input type="email" class="form-control" id="volunteerEmail" aria-describedby="emailHelp" placeholder="Enter email">
						<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
					</div>
					<div class="form-group">
						<label for="volunteerPassword">Password <span class="text-danger">*</span></label>
						<input type="password" class="form-control" id="volunteerPassword" placeholder="Password">
					</div>
					<div class="form-group">
						<label for="volunteerPasswordConfirm">Confirm password <span class="text-danger">*</span></label>
						<input type="password" class="form-control" id="volunteerPasswordConfirm" placeholder="Confirm password">
					</div>
					<div class="form-group">
						<label for="volunteerPhone">Phone number <span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="volunteerPhone" placeholder="xxx-xxx-xxxx">
					</div>
					<div class="form-group">
						<label for="volunteerAvailability">Availability</label>
						<textarea class="form-control" rows="3" id="volunteerAvailability" placeholder="Days/times available"></textarea>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</body>
</html>