<!DOCTYPE html>
<html lang="en">

<head>

	<!-- ===================================================
         Metadata & Document Setup
         =================================================== -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!-- Meta description for SEO -->
	<meta name="description" content="XXXXX">

	<!-- Document Title -->
	<title>Contact Us</title>

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="assets/logo.ico">

	<!-- ===================================================
         Fonts & Stylesheets
         =================================================== -->

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

	<!-- Custom Styles -->
	<link href="styles/global.css" rel="stylesheet">
	<link href="styles/contact.css" rel="stylesheet">

	<!-- ===================================================
         Scripts
         =================================================== -->
	<!-- <script src="scripts/XXXXXXXX.js" defer></script> -->
</head>

<body>
	<!-- ===================================================
         Navigation Section
         - Included via PHP to allow reusability across pages.
         =================================================== -->
	<?php include("nav.php"); ?>

		<!-- HEADER BANNER WITH BREADCRUMB -->
		<div class="headerBannerWrapper">
		<div class="about-banner">
			<div class="about-banner__left">
				<div class="about-banner__content">
					<h1 class="about-banner__title">Contact Us</h1>
					<div class="breadcrumb">
						<a href="index.php" class="breadcrumb__link">Home</a>
						<span class="breadcrumb__separator">&gt;</span>
						<a href="contact.php" class="breadcrumb__link">Contact Us</a>
					</div>
				</div>
			</div>

			<div class="about-banner__right">
				<img src="assets/images/header_img.png" alt="">
			</div>
		</div>
	</div>

	<section class="contact">
		<div class="contact__container">
		<div class="contact__info">
			<h4 class="contact__subtitle">GET IN TOUCH</h4>
			<h2 class="contact__title">Connect With Us<br>When Need Help!</h2>
			<p class="contact__description">
			Our team proudly offers an on-time guarantee and a 100% customer satisfaction guarantee.
			</p>

			<!-- Phone -->
			<div class="contact__card contact__card--phone">
				<div class="contact__icon">
					<img src="assets/images/contact/phone.png" alt="Phone Icon">
				</div>
				<div class="contact__details">
					<p>
						<a href="tel:+917435924700">+91 74359 24700</a><br>
						<a href="tel:+919624515033">+91 96245 15033</a>
					</p>
				</div>
			</div>

			<!-- Email -->
			<div class="contact__card contact__card--email">
				<div class="contact__icon">
					<img src="assets/images/contact/message.png" alt="Email Icon">
				</div>
				<div class="contact__details">
					<p>
						<a href="mailto:mahekshial@gmail.com">mahekshial@gmail.com</a><br>
						<a href="mailto:yourbackup@example.com">yourbackup@example.com</a>
					</p>
				</div>
			</div>

			<!-- Address -->
			<div class="contact__card contact__card--address">
				<div class="contact__icon">
					<img src="assets/images/contact/location.png" alt="Location Icon">
				</div>
				<div class="contact__details">
					<p>
						<a 
							href="https://www.google.com/maps?q=Office+No.+410/411,+Unicorn+Prime,+Ranjit+Sagar+Road,+Jamnagar+361005,+Gujarat"
							target="_blank"
							rel="noopener noreferrer"
						>
							Office No. 410/411, Unicorn Prime,<br>
							Ranjit Sagar Road, Jamnagar - 361005 Gujarat (INDIA)
						</a>
					</p>
				</div>
			</div>

		</div>

		<div class="contact__form-wrapper">
			<p class="contact__form-instruction">
			For Any Enquiries Or Feedback, Please Fill Out The Form Below.
			</p>
			<form class="contact__form">
			<div class="contact__form-row">
				<div class="contact__form-group contact__form-group--half">
					<label>Name *</label>
					<input type="text" placeholder="First">
				</div>
				<div class="contact__form-group contact__form-group--half">
					<label>&nbsp;</label>
					<input type="text" placeholder="Last">
				</div>
			</div>

			<div class="contact__form-group">
				<label>Company Name *</label>
				<input type="text">
			</div>

			<div class="contact__form-group">
				<label>Designation (optional)</label>
				<input type="text">
			</div>

			<div class="contact__form-group">
				<label>Phone Numbers *</label>
				<input type="text">
			</div>

			<div class="contact__form-group">
				<label>Your Email Address *</label>
				<input type="email">
			</div>

			<div class="contact__form-group">
				<label>Your Message</label>
				<textarea rows="4"></textarea>
			</div>

			<button type="submit" class="contact__submit">Submit</button>
			</form>
		</div>
		</div>
	</section>

	<!-- ===================================================
         Footer Section
         - Included via PHP for consistency across pages.
         =================================================== -->
	<?php include("footer.html"); ?>

</body>

</html>