<?php
require_once __DIR__ . '/lib/db.php';

// Determine active category (default to "all")
$category = isset($_GET["category"]) ? trim($_GET["category"]) : 'all';
$validSlugs = array_column(get_categories(), 'slug');
if ($category !== 'all' && !in_array($category, $validSlugs, true)) {
    $category = 'all';
}

if ($category === 'all') {
    $category_data = [
        "title"    => "Complete Export Commodity Catalog",
        "tagline"  => "Six Pillars of Indian Agricultural Excellence",
        "subtitle" => "Sourced directly from verified farm clusters across Gujarat, Kerala, Rajasthan, and Punjab. Cleaned via Buhler Sortex optical sorters and packed under moisture-lock export standards.",
    ];
    $categories = get_categories();
} else {
    $category_data = get_category_by_slug($category);
    if (!$category_data) {
        $category = 'all';
        $category_data = [
            "title"    => "Complete Export Commodity Catalog",
            "tagline"  => "Six Pillars of Indian Agricultural Excellence",
            "subtitle" => "Sourced directly from verified farm clusters across Gujarat, Kerala, Rajasthan, and Punjab. Cleaned via Buhler Sortex optical sorters and packed under moisture-lock export standards.",
        ];
        $categories = get_categories();
    } else {
        $products = get_products_by_category((int)$category_data['id']);
    }
}

function slugify($name)
{
    $noParen = preg_replace('/\s*\(.*?\)\s*/', '', $name);
    $lower = strtolower($noParen);
    $slug  = preg_replace('/[^a-z0-9]+/', '_', $lower);
    return trim($slug, '_');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- ===================================================
         Metadata & Document Setup
         =================================================== -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Explore Maruti Nandan Exports' complete catalog of certified Indian spices, Basmati rice, pulses, dry fruits, and gourmet makhana for international container export.">
	<meta name="keywords" content="Indian Spices Export, Basmati Rice Exporter, Pulses Export India, Cashews Exporter Gujarat, Makhana Exporter Jamnagar, APEDA Certified Agro Exporter">
	<meta name="author" content="Maruti Nandan Exports">

	<!-- Open Graph Meta Tags -->
	<meta property="og:title" content="<?php echo htmlspecialchars($category_data["title"]); ?> | Maruti Nandan Exports">
	<meta property="og:description" content="<?php echo htmlspecialchars($category_data["subtitle"]); ?>">
	<meta property="og:type" content="website">
	<meta property="og:image" content="assets/logo.png">

	<!-- Document Title -->
	<title><?php echo htmlspecialchars($category_data["title"]); ?> | Maruti Nandan Exports</title>

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/png" href="assets/logo.png">

	<!-- ===================================================
         Fonts & Stylesheets
         =================================================== -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,400;1,9..144,600&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

	<!-- Custom Styles -->
	<link href="styles/global.css" rel="stylesheet">
	<link href="styles/products.css" rel="stylesheet">

	<!-- ===================================================
         Scripts
         =================================================== -->
	<script src="scripts/products.js" defer></script>
</head>

<body>
	<!-- Ambient Background Physics System -->
	<div class="ambient-bg" aria-hidden="true">
		<div class="ambient__float" data-depth="0.8" style="left: 8%; top: 12%; width: 6em;">
			<img src="assets/images/hero_floats/turmeric.png" alt="Turmeric">
		</div>
		<div class="ambient__float" data-depth="1.2" style="left: 82%; top: 18%; width: 5.5em;">
			<img src="assets/images/hero_floats/cardamom.png" alt="Cardamom">
		</div>
		<div class="ambient__float" data-depth="0.6" style="left: 15%; top: 75%; width: 4.5em;">
			<img src="assets/images/hero_floats/clove.png" alt="Clove">
		</div>
		<div class="ambient__float" data-depth="1.0" style="left: 72%; top: 68%; width: 5em;">
			<img src="assets/images/hero_floats/star_anise.png" alt="Star Anise">
		</div>
		<div class="ambient__float" data-depth="0.9" style="left: 45%; top: 8%; width: 4em;">
			<img src="assets/images/hero_floats/chilli.png" alt="Chilli">
		</div>
		<div class="ambient__float" data-depth="1.1" style="left: 90%; top: 45%; width: 4.8em;">
			<img src="assets/images/hero_floats/cinnamon.png" alt="Cinnamon">
		</div>
		<div class="ambient__float" data-depth="1.3" style="left: 52%; top: 86%; width: 5.2em;">
			<img src="assets/images/hero_floats/clove.png" alt="Clove">
		</div>
	</div>

	<!-- ===================================================
         Navigation Section
         =================================================== -->
	<?php include("nav.php"); ?>

	<!-- ===================================================
         Hero Header Banner with Frosted Glass Card
         =================================================== -->
	<header class="products-hero">
		<div class="products-hero__card glass-panel">
			<nav class="breadcrumb" aria-label="Breadcrumb">
				<a href="index.php" class="breadcrumb__link">Home</a>
				<span class="breadcrumb__separator" aria-hidden="true">/</span>
				<a href="products.php?category=all" class="breadcrumb__link">Our Products</a>
				<?php if ($category !== "all") : ?>
					<span class="breadcrumb__separator" aria-hidden="true">/</span>
					<span class="breadcrumb__current"><?php echo htmlspecialchars($category_data["title"]); ?></span>
				<?php endif; ?>
			</nav>

			<div class="products-hero__badge-wrap">
				<span class="eyebrow eyebrow--emerald">
					<span class="pulse-dot" aria-hidden="true"></span>
					<?php echo htmlspecialchars($category_data["tagline"]); ?>
				</span>
			</div>

			<h1 class="products-hero__title">
				<?php echo htmlspecialchars($category_data["title"]); ?>
			</h1>

			<p class="products-hero__subtitle">
				<?php echo htmlspecialchars($category_data["subtitle"]); ?>
			</p>

			<!-- Category Filter Pills Bar -->
			<div class="category-pills-bar" role="navigation" aria-label="Product Categories">
				<a href="products.php?category=all" class="category-pill <?php echo $category === 'all' ? 'category-pill--active' : ''; ?>">
					<span>🌟 All Products</span>
				</a>
				<?php
				$catEmoji = ['wholeSpices'=>'🌿','groundSpices'=>'🌶️','grains'=>'🌾','pulses'=>'🫘','dryFruits'=>'🌰','makhana'=>'⚪'];
				foreach (get_categories() as $c):
					$em = $catEmoji[$c['slug']] ?? '📦';
				?>
					<a href="products.php?category=<?php echo htmlspecialchars($c['slug']); ?>" class="category-pill <?php echo $category === $c['slug'] ? 'category-pill--active' : ''; ?>">
						<span><?php echo $em; ?> <?php echo htmlspecialchars($c['name']); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</header>


	<!-- ===================================================
         Main Products Grid Section
         =================================================== -->
	<main id="main-content" class="products-section" aria-label="Commodity Showcase">
		<div class="section-glass-box glass-panel">

			<div class="grid-header">
				<div class="grid-header__info">
					<span class="eyebrow eyebrow--gold">
						<?php
						if ($category === 'all') {
							echo count($categories) . ' Export Pillars';
						} else {
							echo count($products) . ' Certified Export Varieties';
						}
						?>
					</span>
					<h2 class="grid-header__title">
						<?php echo $category === 'all' ? 'Explore Our Export Divisions' : 'Export Grade Specifications'; ?>
					</h2>
				</div>
				<a href="contact.php" class="btn btn-ghost btn-compact">
					<span>Request Price List</span>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
				</a>
			</div>

			<?php if ($category === "all") : ?>
				<!-- Category Divisions Bento Grid -->
				<div class="categories-bento-grid">
					<?php foreach ($categories as $cat) :
						$slug = slugify($cat['name']);
						if (!empty($cat['image_path'])) {
							$imgUrl = $cat['image_path'];
						} else {
							$imgUrl = "assets/images/products/all/{$slug}.png";
						}
						$varietyCount = $cat['count_label'] ?: (count(get_products_by_category((int)$cat['id'])) . ' Varieties');
					?>
						<a href="products.php?category=<?php echo htmlspecialchars($cat['slug']); ?>" class="cat-card glass-panel" style="background-image: url('<?php echo $imgUrl; ?>');">
							<div class="cat-card__overlay">
								<span class="cat-card__badge"><?php echo htmlspecialchars($varietyCount); ?></span>
								<div class="cat-card__content">
									<h3 class="cat-card__title"><?php echo htmlspecialchars($cat['name']); ?></h3>
									<p class="cat-card__desc"><?php echo htmlspecialchars($cat['card_description'] ?: ''); ?></p>
									<span class="cat-card__link-text">
										<span>View Specifications</span>
										<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
									</span>
								</div>
							</div>
						</a>
					<?php endforeach; ?>
				</div>

			<?php else : ?>
				<!-- Individual Product Cards Grid -->
				<div class="products-grid">
					<?php foreach ($products as $prod) :
						$slug = slugify($prod['name']);
						if (!empty($prod['image_path'])) {
							$imgUrl = $prod['image_path'];
						} else {
							$imgUrl = "assets/images/products/{$category}/{$slug}.png";
						}
					?>
						<div class="product-card glass-panel">
							<div class="product-card__image-wrap">
								<img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" class="product-card__image" loading="lazy" width="280" height="220" onerror="this.src='assets/images/products/all/whole_spices.png'">
								<span class="product-card__grade-badge">Grade A</span>
							</div>

							<div class="product-card__body">
								<h3 class="product-card__name"><?php echo htmlspecialchars($prod['name']); ?></h3>
								<p class="product-card__desc"><?php echo htmlspecialchars($prod['description']); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="grid-back-row">
					<a href="products.php?category=all" class="btn btn-secondary">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
						<span>View All Categories</span>
					</a>
				</div>
			<?php endif; ?>

		</div>
	</main>


	<!-- ===================================================
         Section: Accreditations Marquee
         =================================================== -->
	<section class="products-section" aria-label="Quality Accreditations">
		<div class="section-glass-box glass-panel">
			<div class="trust-marquee__header">
				<span class="eyebrow eyebrow--emerald">Verified Export Standards</span>
				<h2 class="trust-marquee__title">Internationally Certified Quality Assurance</h2>
			</div>

			<div class="trust-marquee__wrapper">
				<div class="trust-marquee__track" id="licencesTrack">
					<div class="trust-marquee__item"><img src="assets/icons/home/licences/1.png" alt="APEDA" loading="lazy" width="64" height="64" /><span>APEDA Certified</span></div>
					<div class="trust-marquee__item"><img src="assets/icons/home/licences/2.png" alt="FSSAI" loading="lazy" width="64" height="64" /><span>FSSAI Approved</span></div>
					<div class="trust-marquee__item"><img src="assets/icons/home/licences/3.png" alt="Spices Board" loading="lazy" width="64" height="64" /><span>Spices Board</span></div>
					<div class="trust-marquee__item"><img src="assets/icons/home/licences/4.png" alt="ISO" loading="lazy" width="64" height="64" /><span>ISO Standard</span></div>
					<div class="trust-marquee__item"><img src="assets/icons/home/licences/5.png" alt="Quality" loading="lazy" width="64" height="64" /><span>Quality Graded</span></div>
					<div class="trust-marquee__item"><img src="assets/icons/home/licences/6.png" alt="Export" loading="lazy" width="64" height="64" /><span>Export Compliant</span></div>
					<div class="trust-marquee__item"><img src="assets/icons/home/licences/7.png" alt="Lab" loading="lazy" width="64" height="64" /><span>Lab Tested</span></div>
					<div class="trust-marquee__item"><img src="assets/icons/home/licences/8.png" alt="Phyto" loading="lazy" width="64" height="64" /><span>Phytosanitary</span></div>
				</div>
			</div>
		</div>
	</section>


	<!-- ===================================================
         Section: Call to Action Banner
         =================================================== -->
	<section class="products-section" aria-label="Trade Inquiry">
		<div class="cta-banner__card glass-panel">
			<div class="cta-banner__content">
				<span class="eyebrow">Custom Consignments &amp; Private Label</span>
				<h2 class="cta-banner__title">Looking for Specific Grades or Custom Packaging?</h2>
				<p class="cta-banner__text">
					We fulfill customized commodity grade parameters, private branding, multi-wall paper sack packaging, and direct FOB/CIF container shipments to 25+ global ports.
				</p>
				<div class="cta-banner__actions">
					<a href="contact.php" class="btn btn-primary">
						<span>Request a Quote</span>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
					</a>
					<a href="about.php" class="btn btn-secondary">
						<span>Learn About Us</span>
					</a>
				</div>
			</div>
		</div>
	</section>

	<?php include("footer.html"); ?>

	<!-- ===================================================
         Scripts
         =================================================== -->
	<script src="scripts/reveal.js"></script>

</body>

</html>