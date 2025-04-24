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
	<title>About Us</title>

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
	<link href="styles/about.css" rel="stylesheet">

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
					<h1 class="about-banner__title">About Us</h1>
					<div class="breadcrumb">
						<a href="index.php" class="breadcrumb__link">Home</a>
						<span class="breadcrumb__separator">&gt;</span>
						<a href="about.php" class="breadcrumb__link">About Us</a>
					</div>
				</div>
			</div>

			<div class="about-banner__right">
				<img src="assets/images/about/dry-tree_8414098.png" alt="">
			</div>
		</div>
	 </div>
	
	<!-- ABOUT TEXT AND IMAGES WITH DESCRIPTION -->
	<section class="export-info">
		<div class="export-info__content">
		<div class="export-info__circle-images">
			<div class="export-info__image export-info__image--top-left"></div>
			<div class="export-info__image export-info__image--top-right"></div>
			<div class="export-info__image export-info__image--bottom-left"></div>
			<div class="export-info__image export-info__image--bottom-right"></div>
			<div class="export-info__center-image"></div>
		</div>

		<div class="export-info__text-content">
			<h2 class="export-info__heading">We Are Leaders In Our Field Since 2012</h2>

			<div class="export-info__highlights">
			<div class="export-info__highlight">
				<div class="export-info__checkmarkAndText">
					<img src="./assets/images/about/checkmark.png" alt=""> 
					<strong>We Ensure Quality & Packaging</strong>
				</div>
			</div>

			<hr class="custom_divider">

			<div class="export-info__highlight">
				<div class="export-info__checkmarkAndText">
					<img src="./assets/images/about/checkmark.png" alt=""> 
					<strong>Trusted & Legally Approved Certified Services</strong>
				</div>
			</div>
			</div>

			<p class="export-info__paragraph">
			We Karmani Exports engaged in Exporting of Raw Bold & Java Peanut, Blanched Peanut, Sesame Seeds,
			Desiccated Coconut, Raisins, Cashew Kernels, Almonds & Spices from India...
			</p>

			<p class="export-info__paragraph">
			We are regularly exporting to more than 25 countries like China, Vietnam, Indonesia, Thailand, Malaysia,
			Philippines, United Arab Emirates, United States...
			</p>

			<div class="export-info__brands">
			<span class="export-info__brands-title">Our Brands</span>
			<img src="peanuts-logo.png" alt="Logo 1" class="export-info__brand-logo">
			<img src="pure-spices-logo.png" alt="Logo 2" class="export-info__brand-logo">
			</div>
		</div>
		</div>
	</section>

	 <!-- COUNTER -->
	<section class="stats">
		<div class="stats__overlay">
			<div class="stats__container">
			
			<div class="stats__item">
				<div class="stats__number" data-target="15">0</div>
				<div class="stats__label">Years of Experience</div>
			</div>
			
			<div class="stats__item">
				<div class="stats__number" data-target="21">0</div>
				<div class="stats__label">Countries Served</div>
			</div>
			
			<div class="stats__item">
				<div class="stats__number" data-target="100">0</div>
				<div class="stats__label">Happy clients</div>
			</div>
			
			<div class="stats__item">
				<div class="stats__number" data-target="100" data-suffix="%">0%</div>
				<div class="stats__label">Quality Assurance</div>
			</div>

			</div>
		</div>
	</section>

	<!-- WHY CHOOSE US -->
	<section class="why-choose">
		<h2 class="why-choose__heading">Why Choose Us?</h2>
		<div class="why-choose__grid">
			
			<div class="why-choose__card">
			<div class="why-choose__icon-wrapper">
				<img src="./assets/images/about/ProductQuality.png" alt="" class="why-choose__icon">
				<span class="why-choose__number">01</span>
			</div>
			<h3 class="why-choose__title">Product Quality</h3>
			<p class="why-choose__desc">
				Quality in all aspects, such as dimensional accuracy, design, printing, labeling and packaging.
			</p>
			</div>

			<div class="why-choose__card">
			<div class="why-choose__icon-wrapper">
				<img src="./assets/images/about/PersonalizedServices.png" alt="" class="why-choose__icon">
				<span class="why-choose__number">02</span>
			</div>
			<h3 class="why-choose__title">Personalized Services</h3>
			<p class="why-choose__desc">
				We follow a client focused approach and thus provide our customers with highly customized services.
			</p>
			</div>

			<div class="why-choose__card">
			<div class="why-choose__icon-wrapper">
				<img src="./assets/images/about/CompetitivePrice.png" alt="" class="why-choose__icon">
				<span class="why-choose__number">03</span>
			</div>
			<h3 class="why-choose__title">Competitive Price</h3>
			<p class="why-choose__desc">
				We strive to offer our customers the best quality products at competitive industry prices.
			</p>
			</div>

			<div class="why-choose__card">
			<div class="why-choose__icon-wrapper">
				<img src="./assets/images/about/ClientSatisfaction.png" alt="" class="why-choose__icon">
				<span class="why-choose__number">04</span>
			</div>
			<h3 class="why-choose__title">Client Satisfaction</h3>
			<p class="why-choose__desc">
				We recognize and deliver realistic solutions to our customers’ desires and demands.
			</p>
			</div>

			<div class="why-choose__card">
			<div class="why-choose__icon-wrapper">
				<img src="./assets/images/about/StrongVendorBase.png" alt="" class="why-choose__icon">
				<span class="why-choose__number">05</span>
			</div>
			<h3 class="why-choose__title">Strong Vendor Base</h3>
			<p class="why-choose__desc">
				We select our suppliers on the basis of quality, capacity, financial stability, and delivery timelines.
			</p>
			</div>

			<div class="why-choose__card">
			<div class="why-choose__icon-wrapper">
				<img src="./assets/images/about/Flexibility.png" alt="" class="why-choose__icon">
				<span class="why-choose__number">06</span>
			</div>
			<h3 class="why-choose__title">Flexibility</h3>
			<p class="why-choose__desc">
				We produce goods as per customer needs and operate with full transparency and ethics.
			</p>
			</div>

			<div class="why-choose__card">
			<div class="why-choose__icon-wrapper">
				<img src="./assets/images/about/Packaging.png" alt="" class="why-choose__icon">
				<span class="why-choose__number">07</span>
			</div>
			<h3 class="why-choose__title">Packaging</h3>
			<p class="why-choose__desc">
				We use high quality packaging materials to ensure safety during transit and delivery.
			</p>
			</div>

			<div class="why-choose__card">
			<div class="why-choose__icon-wrapper">
				<img src="./assets/images/about/TimelyDelivery.png" alt="" class="why-choose__icon">
				<span class="why-choose__number">08</span>
			</div>
			<h3 class="why-choose__title">Timely Delivery</h3>
			<p class="why-choose__desc">
				We ensure that orders are delivered within a specified time frame using proven methods.
			</p>
			</div>

		</div>
	</section>

	<!-- CONNECT WITH US -->
	<section class="help-banner">
		<div class="help-banner__content">
			<h2 class="help-banner__text">You Can Connect With Us<br>When Need Help!</h2>
			<a href="contact.php" class="help-banner__button">Discover More</a>
		</div>
	</section>


	<!-- ===================================================
         Footer Section
         - Included via PHP for consistency across pages.
         =================================================== -->
	<?php include("footer.html"); ?>

	<!-- FOR THE COUNTER -->
	<script>
		document.addEventListener("DOMContentLoaded", () => {
			const counters = document.querySelectorAll('.stats__number');
			
			counters.forEach(counter => {
			const updateCount = () => {
				const target = +counter.getAttribute('data-target');
				const suffix = counter.getAttribute('data-suffix') || '';
				const count = +counter.innerText.replace(/\D/g, '');
				const increment = target / 100;

				if (count < target) {
				counter.innerText = Math.ceil(count + increment) + suffix;
				setTimeout(updateCount, 20);
				} else {
				counter.innerText = target + suffix;
				}
			};

			updateCount();
			});
		});
	</script>


</body>

</html>