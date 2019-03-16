<?php require_once("head-utils.php"); ?>

<?php require_once("navbar.php"); ?>


<main class="gray">
	<div class="container">
		<div class="p-2">
			<h1>Browse</h1>
			<div>
				<span>Search for a certain someone...&nbsp&nbsp&nbsp&nbsp
					<input type="text" rel="">
					<button class="btn"><i class="fas fa-search"></i></button>
				</span>
			</div>

			<div class="row border rounded bg-light mt-4">
				<div class="col-sm-3">
					<div class="my-auto pt-5 d-block d-flex justify-content-center">
						<img class="img-fluid" src="catgirl.jpg" alt="Catarina">
					</div>
				</div>
				<div class="col-sm-5">
					<div class="my-auto py-5">
						<p class="userHandle">Name</p>
						<p class="userDetailAboutMe"> About Me: bird bird bird human why take bird out i could have eaten that.</p>
					</div>
				</div>
				<div class="col-sm-2">
					<!--todo add letter grade-->
					<div class="my-auto py-5">
						<p class="letterGrade">letter grade</p>
					</div>
				</div>
				<div class="col-sm-2">
					<!--todo add matching ability to heart-->
					<div class="my-auto py-5">
						<i class="far fa-heart"></i>
					</div>
				</div>
			</div>
			
	<?php require_once("footer.php"); ?>
</main>
