<!DOCTYPE html>
<html lang="en">

<?php require_once ("head-utils.php");?>

	<body>
		<?php require_once("navbar.php");?>

		<main>

			<!-- Modal -->
			<div class="container">
				<div class="" role="document">
					<div class="p-5"></div>
					<div class="card">
						<div class="card-body">
							<form action="#" novalidate>
								<div class="form-group">
									<div class="input-group">
										<input id="signInEmail" name="signInEmail" type="email" class="form-control" placeholder="Email">
									</div>
									<div class="form-group">
										<div class="input-group">
											<input id="signInPassword" name="signInPassword" type="password" class="form-control" placeholder="Password">
										</div>
									</div>
								</div>
								<div>
									<button class="btn btn-primary"><i class="fa fa-sign-in"></i>Login!</button>
								</div>
							</form>
							<div>
								<span>Don't Have an Account?</span><button class="btn-link" data-toggle="modal" data-target="#signUpModal">Sign Up!</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>


	</body>
</html>