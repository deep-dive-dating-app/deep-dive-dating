<?php require_once ("head-utils.php");?>

<?php require_once("navbar.php");?>

<main>

	<!-- Modal: used live demo example from bootstrap, can call this modal using id logoutModal -->
	<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModal" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="logoutModal">You have been logged out!</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Thanks for visiting. Come back soon!</p>
				</div>
			</div>
		</div>
	</div>
</main>