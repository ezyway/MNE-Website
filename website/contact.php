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
				<h2 class="contact__title">Connect With Us</h2>
				<p class="contact__description">
					Our team proudly offers on-time delivery and world class quality products with 100% customer satisfaction guarantee.
				</p>

				<!-- Phone -->
				<div class="contact__card contact__card--phone">
					<div class="contact__icon">
						<img src="assets/images/contact/phone.png" alt="Phone Icon">
					</div>
					<div class="contact__details">
						<a href="tel:+917435924700">+91 74359 24700</a><br>
						<a href="tel:+919624515033">+91 96245 15033</a>
					</div>
				</div>

				<!-- Email -->
				<div class="contact__card contact__card--email">
					<div class="contact__icon">
						<img src="assets/images/contact/message.png" alt="Email Icon">
					</div>
					<div class="contact__details">
						<div class="contact__email-scroll">
							<a href="mailto:mahekshial@gmail.com">mahekshial@gmail.com</a><br>
							<a href="mailto:yourbackup@example.com">mail@gmail.com</a>
						</div>
					</div>
				</div>


				<!-- Address -->
				<div class="contact__card contact__card--address">
					<div class="contact__icon">
						<img src="assets/images/contact/location.png" alt="Location Icon">
					</div>
					<div class="contact__details">
						<a href="https://www.google.com/maps?q=Office+No.+410/411,+Unicorn+Prime,+Ranjit+Sagar+Road,+Jamnagar+361005,+Gujarat"
							target="_blank"
							rel="noopener noreferrer">
							Office No. 410/411, Unicorn Prime,
							Ranjit Sagar Road, Jamnagar - 361005 Gujarat (INDIA)
						</a>
					</div>
				</div>

			</div>

			<div class="contact__form-wrapper">
				<p class="contact__form-instruction">
					We're happy to listen to you.
					<br>
					Send us your Enquiries Or Feedback...
				</p>
				<form class="contact__form">

					<!-- Row 1: First Name and Last Name -->
					<div class="form-row">
						<div class="form-group">
							<label for="firstName">First Name</label>
							<input type="text" id="firstName" name="firstName" required>
						</div>
						<div class="form-group">
							<label for="lastName">Last Name</label>
							<input type="text" id="lastName" name="lastName" required>
						</div>
					</div>

					<!-- Row 2: Company Name and Designation -->
					<div class="form-row">
						<div class="form-group">
							<label for="company">Company Name</label>
							<input type="text" id="company" name="company" required>
						</div>
						<div class="form-group">
							<label for="designation">Designation (optional)</label>
							<input type="text" id="designation" name="designation">
						</div>
					</div>

					<!-- Row 3: Phone Number and Email -->
					<div class="form-row">
						<div class="form-group">
							<label for="phone">Phone Number</label>
							<input type="tel" id="phone" name="phone" required>
						</div>
						<div class="form-group">
							<label for="email">Email Address</label>
							<input type="email" id="email" name="email" required>
						</div>
					</div>

					<!-- Subject (Full Width) -->
					<div class="form-group">
						<label for="subject">Subject</label>
						<input type="text" id="subject" name="subject" required>
					</div>

					<!-- Message (Full Width) -->
					<div class="form-group">
						<label for="message">Your Message</label>
						<textarea id="message" name="message" rows="4" required></textarea>
					</div>
					
					<button type="submit" class="contact__submit">Send</button>
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