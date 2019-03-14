<?php require_once ("head-utils.php");?>

<main>
	<?php require_once("navbar.php");?>
	<!-- user profile image -->
	<div class="container">
		<h1 class="py-3">{{User Handle}}</h1>
		<div class="p-3">
			<div class="row">
				<div class="col-3">
					<img class="img-fluid" src="/images/pathToYourImage.png" alt="profile image">
				</div>
				<div class="col-3">
					<button type="button" class="btn btn-primary">edit</button>
				</div>
				<div class="col-3">
					<!-- heart icon -->
					<i class="far fa-heart"></i>
				</div>
				<div class="col-3">
					<!-- Dan score icon -->
					<img src="/images/pathToYourImage.png" class="img-fluid" alt="Responsive image">
				</div>
			</div>
		</div>

	<!-- user about me -->

		<div class="card card-body my-3">
			<div class="row">
				<div class="col">
					<div class=" my-auto py-3">
						<div class="aboutMe">
							<h2>About Me:</h2>Scratch at owner, destroy all furniture, especially couch my water bowl is clean and freshly replenished, so i'll drink from the toilet dont wait for the storm to pass, dance in the rain and bird bird bird bird bird bird human why take bird out i could have eaten that. Proudly present butt to human. When in doubt, wash scream at teh bath, for ask to be pet then attack owners hand stuff and things but cough furball lick butt and make a weird face. Plays league of legends please stop looking at your phone and pet me for ask to be pet then attack owners hand for pooping rainbow while flying in a toasted bread costume in space for lick sellotape yet claws in your leg bite off human's toes. in there anyway spot something, big eyes, big eyes, crouch, shake butt, prepare to pounce wake up human for food at 4am.</div>
						<button type="button" class="btn btn-primary">edit</button>
					</div>
				</div>
			</div>
		</div>

		<div class="card card-body my-3">
			<div class="row">
				<div class="col">
					<div class=" my-auto py-3">
						<div class="interests">
							<h2>Interests:</h2>Scratch at owner, destroy all furniture, especially couch my water bowl is clean and freshly replenished, so i'll drink from the toilet dont wait for the storm to pass, dance in the rain and bird bird bird bird bird bird human why take bird out i could have eaten that. Proudly present butt to human. When in doubt, wash scream at teh bath, for ask to be pet then attack owners hand stuff and things but cough furball lick butt and make a weird face. Plays league of legends please stop looking at your phone and pet me for ask to be pet then attack owners hand for pooping rainbow while flying in a toasted bread costume in space for lick sellotape yet claws in your leg bite off human's toes. in there anyway spot something, big eyes, big eyes, crouch, shake butt, prepare to pounce wake up human for food at 4am.</div>
						<button type="button" class="btn btn-primary">edit</button>
					</div>
				</div>
			</div>
		</div>

		<div class="card card-body my-3">
			<div class="row">
				<div class="col">
					<div class=" my-auto py-3">
						<div class="detailQuestionnaire">
							<h2>Detail Questionnaire:</h2>
							<h3></h3>
							<div> <strong>Age: </strong> {{userDetail.age}}</div>
							<button type="button" class="btn btn-primary">edit</button>

							<h3></h3>
							<div> <strong>Career: </strong> {{userDetail.career}}</div>
							<button type="button" class="btn btn-primary">edit</button>

							<h3></h3>
							<div> <strong>Display Email: </strong> {{userDetail.display-email}}</div>
							<button type="button" class="btn btn-primary">edit</button>

							<h3></h3>
							<div> <strong>Education: </strong> {{userDetail.education}}</div>
							<button type="button" class="btn btn-primary">edit</button>

							<h3></h3>
							<div> <strong>Gender: </strong> {{userDetail.gender}}</div>
							<button type="button" class="btn btn-primary">edit</button>

							<h3></h3>
							<div> <strong>Race: </strong> {{userDetail.Race}}</div>
							<button type="button" class="btn btn-primary">edit</button>

							<h3></h3>
							<div> <strong>Religion: </strong> {{userDetail.Religion}}</div>
							<button type="button" class="btn btn-primary">edit</button>
						</div>
					</div>
				</div>
			</div>
		</div>


		<!-- heart icon -->
		<div class="row mb-5">
			<div class="col"></div>
			<div class="col-1"><i class="far fa-heart"></i></div>
			<div class="col"></div>
		</div>
	</div>


</main>

<?php require_once("footer.php"); ?>