	<div class="col-xl-12 col-lg-12 col-sm-12 col-xs-12 container-main"style="padding:0">
		
		<?php $this->load->view('includes/socialmedia'); ?>
		<?php $this->load->view('includes/nav'); ?>
		
		<div class="col-lg-12 col-sm-12 col-xs-12 bgImage">
			<div class="d-flex flex-column overlay justify-content-center"align="center">
				<h1 class="text-light"style="font-family: Quicksand, sans-serif">Welcome to</h1>
				<p class="text-warning"style="font-family: Quicksand, sans-serif; margin-bottom: 2px;font-weight:bold; font-size: 3vw;">Virginia & Boy</p>
				<h4 class="text-light"style="font-family: Quicksand, sans-serif">Lodge and Resort</h4>
				<h5 class="text-light"style="font-family: Quicksand, sans-serif">Lorem ipsum dolor sit amet,adipiscing consectetur adipiscing </h5>
				<h5 class="text-light"style="font-family: Quicksand, sans-serif">consectetur adipiscing elit nullam </h5>



				<div class="col-lg-6 col-sm-3 col-xs-5" style="padding: 3% 0">
					<div class="btn-group" role="group" aria-label="Basic example">
						<a href="#containerAbout" style="color: white">
							<button type="button" class="btn btn-lg btn-primary">Know More</button>
						</a>
						&nbsp
						<a href="<?=base_url()?>Reservation" style="color: white"> 
							<button type="button" class="btn btn-lg color-yellow">Book Now</button> 
						</a>
					</div>
					<form id="viewReservationForm" onsubmit="appendCode()" action="" method="post" class="form-inline col-lg-7 col-sm-3 col-xs-5 pt-5">
						<div class="input-group mb-2 mr-sm-2">
							<div class="input-group-prepend">
								<div class="input-group-text">TRANS ID -</div>
							</div>
							<input type="text" class="form-control" id="transaction_id" placeholder="Transaction Code">
						</div>


						<button type="submit" class="btn btn-primary mb-2">View</button>
					</form>
				</div>
			</div>
		</div> 
	</div>
	<div class="container container-new"id="containerAbout"style="margin-bottom:15px;padding:50px">
		<div class="row d-flex justify-content-center">
			<div class="col-lg-12 col-sm-12 col-xs-12 margin-top">
				<h1 class="text-secondary text-center"style="font-family:Quicksand,sans-serif">About <span class="text-warning">Virginia & Boy</span></h1>
			</div>
			<div class="col-lg-12 col-sm-12 col-xs-12">
				<p class="text-muted text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet
				Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet</p>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-4 col-sm-4 col-xs-12 bg-light inner">
				<img src="<?=base_url()?>/assets/images/IMG_6309.jpg" class="img-fluid img-background">
				<div class="overlays overlaysBottom" style="background-color:#1aaa8fbd">
					<div class="text">
						<h2 class="text-center text-warning text-xs">Gallery</h2>	
						<p class="text-center text-light text-paragraph">
							<a href="#" class="text-light">Click here</a>
						</p>	
					</div>

				</div>
			</div>	
			<div class="col-lg-4 col-sm-4 col-xs-12 bg-light inner">
				<img src="<?=base_url()?>/assets/images/room3.jpg"class="img-fluid img-background">
				<div class="overlays overlaysBottom"style="background-color:#dc3545c9">
					<div class="text">
						<h3 class="text-center text-warning text-xs">Rooms</h3>	
						<p class="text-center text-light text-paragraph">
							<a href="#" class="text-light">Click here</a>
						</p>	
					</div>

				</div>
			</div>	
			<div class="col-lg-4 col-sm-4 col-xs-12 bg-light inner">
				<img src="<?=base_url()?>/assets/images/billiard.jpg"class="img-fluid img-background">
				<div class="overlays overlaysBottom" style="background-color: #f2ab2bd9 !important">
					<div class="text">
						<h3 class="text-center text-warning text-xs">Amenities</h3>	
						<p class="text-center text-light text-paragraph">
							<a href="#" class="text-light">Click here</a>
						</p>	
					</div>

				</div>
			</div>	
		</div>
		<div class="row">
			<div class="col-lg-4 col-sm-4 col-xs-12 bg-info bg-height"style="background-color: #1aaa8f !important">
				<h1 class="text-center text-light text-small"style="font-family:quicksand,sans-serif">RELAXING</h1>
				<p class="text-center text-light">Lorem ipsum dolor sit amet,consectetur adipiscing elit. Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet consectetur adipiscing elit. Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet</p>
			</div>	
			<div class="col-lg-4 col-sm-4 col-xs-12 bg-danger bg-height"style="background-color:#dc3545!important !important">
				<h1 class="text-center text-light text-small"style="font-family:quicksand,sans-serif">AFFORADBLE</h1>
				<p class="text-center text-light">Lorem ipsum dolor sit amet,consectetur adipiscing elit. Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet consectetur adipiscing elit. Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet</p>
			</div>	
			<div class="col-lg-4 col-sm-4 col-xs-12 bg-brown bg-height"style="background-color:	#f2ab2b !important">
				<h1 class="text-center text-light text-small"style="font-family:quicksand,sans-serif">EXCITEMENT</h1>
				<p class="text-center text-light">Lorem ipsum dolor sit amet,consectetur adipiscing elit. Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet consectetur adipiscing elit. Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet</p>
			</div>	
		</div>
	</div>
	<div class="bg-light container-fluid">
		<div class="row d-flex justify-content-center"style="padding:50px">
			<div class="col-lg-12 col-sm-12 col-xs-12">
				<h1 class="text-warning text-center"style="font-family:Quicksand,sans-serif">Fun and Excitement</h1>
			</div>
			<div class="col-lg-12 col-sm-12 col-xs-12">
				<p class="text-secondary text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet
				Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet</p>
			</div>
		</div>
	</div>
	<div class="container-fluid bg-reservations">
		<div class="row">
			<div class="col-lg-7 col-sm-7 col-xs-12 overlay-new d-flex flex-column justify-content-center">
				<h1 class="text-light"style="font-family:quicksand,sans-serif;font-size:3em;"><a class="text-light"href="selectDates.php"><b>CHECK AVAILABLE PRICES</b></a></h1>

				<p class="text-light">Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur</p>
			</div>
		</div>
	</div> 		
	<?php $this->load->view('includes/footerDiv'); ?>
	<script type="text/javascript">
		// Select all links with hashes
		$('a[href*="#"]')
		// Remove links that don't actually link to anything
		.not('[href="#"]')
		.not('[href="#0"]')
		.click(function(event) {
			 // On-page links
			 if (
			 	location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
			 	&& 
			 	location.hostname == this.hostname
			 	) {
			 		 // Figure out element to scroll to
			 		var target = $(this.hash);
			 		target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			 		 // Does a scroll target exist?
			 		 if (target.length) {
			 		 	// Only prevent default if animation is actually gonna happen
			 		 	event.preventDefault();
			 		 	$('html, body').animate({
			 		 		scrollTop: target.offset().top
			 		 	}, 1000, function() {
			 		 		 // Callback after animation
			 		 		  // Must change focus!
			 		 		  var $target = $(target);
			 		 		  $target.focus();
			 		 		  if ($target.is(":focus")) { 
			 		 		  	// Checking if the target was focused
			 		 		  	return false;
			 		 		  } else {
			 		 		  	$target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
			 		 		  	$target.focus(); // Set focus again
			 		 		  };
			 		 		});
			 		 }
			 		}
			 	});

			 </script>