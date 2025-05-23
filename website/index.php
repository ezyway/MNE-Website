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
	<title>XXXXXXXX - Exports</title>

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
	<link href="styles/index.css" rel="stylesheet">

	<!-- ===================================================
         Scripts
         =================================================== -->
	<script src="scripts/index.js" defer></script>
</head>

<body>
	<!-- ===================================================
         Navigation Section
         - Included via PHP to allow reusability across pages.
         =================================================== -->
	<?php include("nav.php"); ?>


	<!-- Bruce Banner Section -->
	<div class="banner">
		<div class="banner__track">
			<?php
			$imageDir = "assets/images/home";
			$images = glob($imageDir . "/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);

			// Filter out _m versions for looping
			$desktopImages = array_filter($images, function ($img) {
				return !preg_match('/_m\.(jpg|jpeg|png|gif|webp)$/i', $img);
			});

			foreach ($desktopImages as $img) {
				$mobileVersion = preg_replace('/\.(jpg|jpeg|png|gif|webp)$/i', '_m.$1', $img);
				echo "<div class='banner__slide'>
						<picture>
							<source media='(max-width: 768px)' srcset='$mobileVersion'>
							<img src='$img' alt='Banner Image' class='banner__image'>
						</picture>
					</div>";
			}
			?>

		</div>

		<button class="banner__nav banner__nav--prev">&#10094;</button>
		<button class="banner__nav banner__nav--next">&#10095;</button>
	</div>


	<!-- Licences Marquee -->
	<section class="icon-scroll">
		<div class="icon-scroll__wrapper">
			<div class="icon-scroll__container">
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/1.png" alt="Icon 1" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/2.png" alt="Icon 2" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/3.png" alt="Icon 3" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/4.png" alt="Icon 4" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/5.png" alt="Icon 5" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/6.png" alt="Icon 6" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/7.png" alt="Icon 7" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/8.png" alt="Icon 8" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/9.png" alt="Icon 9" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/10.png" alt="Icon 10" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/11.png" alt="Icon 11" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/12.png" alt="Icon 12" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/13.png" alt="Icon 13" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/14.png" alt="Icon 14" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/licences/15.png" alt="Icon 15" /></div>
			</div>
		</div>
	</section>


	<!-- About Section -->
	<section class="about">
		<div class="about__content">
			<h2 class="about__heading">About Us</h2>
			<p class="about__text">
				We are a globally recognized export company, committed to delivering top-quality products across international markets.
				Our core values of reliability, innovation, and sustainability drive every part of our business.
				With a strong network and a forward-thinking approach, we continuously meet the evolving demands of our partners worldwide.
				<a href="about.php" class="about__link">Read more →</a>
			</p>
		</div>
		<div class="about__image-wrapper">
			<img src="assets/logo.png" alt="Company Logo" class="about__image" />
		</div>
	</section>


	<!-- Shipping Partners Marquee -->
	<section class="icon-scroll">
		<div class="icon-scroll__wrapper">
			<div class="icon-scroll__container">
				<div class="icon-scroll__item"><img src="assets/icons/home/shipping_partners/1.png" alt="Icon 1" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/shipping_partners/2.png" alt="Icon 2" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/shipping_partners/3.png" alt="Icon 3" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/shipping_partners/4.png" alt="Icon 4" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/shipping_partners/5.png" alt="Icon 5" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/shipping_partners/6.png" alt="Icon 6" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/shipping_partners/7.png" alt="Icon 7" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/shipping_partners/8.png" alt="Icon 8" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/shipping_partners/9.png" alt="Icon 9" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/shipping_partners/10.png" alt="Icon 10" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/shipping_partners/11.png" alt="Icon 11" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/shipping_partners/12.png" alt="Icon 12" /></div>
				<div class="icon-scroll__item"><img src="assets/icons/home/shipping_partners/13.png" alt="Icon 13" /></div>
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