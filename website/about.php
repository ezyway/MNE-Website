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
	<script src="scripts/about.js" defer></script>
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
				<img src="assets/images/header_img.png" alt="">
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
							<img src="./assets/icons/about/checkmark.png" alt="">
							<strong>We Ensure Quality & Packaging</strong>
						</div>
					</div>

					<hr class="custom_divider">

					<div class="export-info__highlight">
						<div class="export-info__checkmarkAndText">
							<img src="./assets/icons/about/checkmark.png" alt="">
							<strong>Trusted & Legally Approved Certified Services</strong>
						</div>
					</div>
				</div>

				<p class="export-info__paragraph">
					We, Maruti Nandan Shipping, are engaged in exporting a wide range of high-quality food products from India. Our offerings include premium Whole Spices, Ground Spices, Grains, Pulses, Dry Fruits, and Makhana (Fox Nuts).Sourced from trusted producers, our products reflect the rich agricultural heritage and flavors of India. We cater to global markets, serving wholesalers, retailers, and individuals seeking authentic and nutritious ingredients. With a commitment to consistency and quality, we aim to bring the taste of India to kitchens around the world.
				</p>

				<p class="export-info__paragraph">
					We are regularly exporting to many countries like China, Vietnam, Indonesia, Thailand, Malaysia, Philippines, United Arab Emirates, United States...
				</p>

				<!-- <div class="export-info__brands">
					<span class="export-info__brands-title">Our Brands</span>
					<img src="peanuts-logo.png" alt="Logo 1" class="export-info__brand-logo">
					<img src="pure-spices-logo.png" alt="Logo 2" class="export-info__brand-logo">
				</div> -->
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
					<span class="why-choose__number">1</span>
					<h3 class="why-choose__title">Product Quality</h3>
					<img src="./assets/icons/about/product_quality.png" alt="" class="why-choose__icon">
				</div>
			</div>

			<div class="why-choose__card">
				<div class="why-choose__icon-wrapper">
					<span class="why-choose__number">2</span>
					<h3 class="why-choose__title">Personalized Services</h3>
					<img src="./assets/icons/about/personalized_services.png" alt="" class="why-choose__icon">
				</div>
			</div>

			<div class="why-choose__card">
				<div class="why-choose__icon-wrapper">
					<span class="why-choose__number">3</span>
					<h3 class="why-choose__title">Competitive Price</h3>
					<img src="./assets/icons/about/competitive_price.png" alt="" class="why-choose__icon">
				</div>
			</div>

			<div class="why-choose__card">
				<div class="why-choose__icon-wrapper">
					<span class="why-choose__number">4</span>
					<h3 class="why-choose__title">Client Satisfaction</h3>
					<img src="./assets/icons/about/client_satisfaction.png" alt="" class="why-choose__icon">
				</div>
			</div>

			<div class="why-choose__card">
				<div class="why-choose__icon-wrapper">
					<span class="why-choose__number">5</span>
					<h3 class="why-choose__title">Strong Vendor Base</h3>
					<img src="./assets/icons/about/strong_vendor_base.png" alt="" class="why-choose__icon">
				</div>
			</div>

			<div class="why-choose__card">
				<div class="why-choose__icon-wrapper">
					<span class="why-choose__number">6</span>
					<h3 class="why-choose__title">Flexibility</h3>
					<img src="./assets/icons/about/flexibility.png" alt="" class="why-choose__icon">
				</div>
			</div>

			<div class="why-choose__card">
				<div class="why-choose__icon-wrapper">
					<span class="why-choose__number">7</span>
					<h3 class="why-choose__title">Packaging</h3>
					<img src="./assets/icons/about/packaging.png" alt="" class="why-choose__icon">
				</div>
			</div>

			<div class="why-choose__card">
				<div class="why-choose__icon-wrapper">
					<span class="why-choose__number">8</span>
					<h3 class="why-choose__title">Timely Delivery</h3>
					<img src="./assets/icons/about/timely_delivery.png" alt="" class="why-choose__icon">
				</div>
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