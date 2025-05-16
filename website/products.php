<?php
if (isset($_GET["category"])) {
    include("products_data.php");
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- ===================================================
            Metadata & Document Setup
        =================================================== -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="XXXXXX">
        <title><?php echo $data[$_GET["category"]]["title"]; ?></title>
        <link rel="shortcut icon" type="image/x-icon" href="assets/logo.ico">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <!-- Custom Styles -->
        <link href="styles/global.css" rel="stylesheet">
        <link href="styles/products.css" rel="stylesheet">

        <!-- JS File -->
        <script src="scripts/products.js" defer></script>

    </head>

    <body>
        <!-- ===================================================
            Navigation Section
        =================================================== -->
        <?php include("nav.php"); ?>

        <?php
            // Get information to be used
            $category      = $_GET["category"];
            $category_data = $data[$category];
            $names         = $category_data["name"];
            $descriptions  = $category_data["description"];
        ?>

        <!-- HEADER BANNER WITH BREADCRUMB -->
        <div class="headerBannerWrapper">
            <div class="about-banner">

                <div class="about-banner__left">
                    <div class="about-banner__content">

                        <h1 class="about-banner__title">
                            <?php echo $data[$_GET["category"]]["title"]; ?>
                        </h1>

                        <div class="breadcrumb">
                            <a href="index.php" class="breadcrumb__link">Home</a>
                            <span class="breadcrumb__separator">&gt;</span>
                            <a href="products.php?category=all" class="breadcrumb__link">Our Products</a>
                            <?php if ($category !== "all") : ?>
                                <span class="breadcrumb__separator">&gt;</span>
                                <a href="products.php?category=<?php echo $category; ?>" class="breadcrumb__link">
                                    <?php echo $category_data["title"]; ?>
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

                <div class="about-banner__right">
                    <img src="assets/images/header_img.png" alt="">
                </div>

            </div>
        </div>

        <?php
        function slugify($name)
        {
            // 1) remove any parenthetical text
            $noParen = preg_replace('/\s*\(.*?\)\s*/', '', $name);
            // 2) lowercase
            $lower = strtolower($noParen);
            // 3) replace non-alphanumerics with underscores
            $slug  = preg_replace('/[^a-z0-9]+/', '_', $lower);
            // 4) trim leading/trailing underscores
            return trim($slug, '_');
        }

        function renderProductItem($name, $description, $categoryKey, $is_link = false, $target_category = "")
        {
            $slug    = slugify($name);
            $img_url = "assets/images/products/{$categoryKey}/{$slug}.webp";

            $tag       = $is_link
                ? "a href='?category=$target_category'"
                : "div";
            $close_tag = $is_link
                ? "a"
                : "div";

            return "
                <$tag class='product_item' style=\"background-image:url('$img_url');\">
                    <div class='overlay'>
                        <h3 class='product_name'>{$name}</h3>
                        <p class='product_desc'>{$description}</p>
                    </div>
                </$close_tag>
            ";
        }

        echo "<div class='products_grid_wrapper'><div class='products_grid'>";
        foreach ($names as $i => $name) {
            $desc = $descriptions[$i];
            if ($category === "all") {
                $target = $data["category_mapping"][$name];
                echo renderProductItem($name, $desc, "all", true, $target);
            } else {
                echo renderProductItem($name, $desc, $category);
            }
        }
        echo "</div></div>";
        ?>

        <?php

        // Only show the back button if we're not on the 'all' category
        if (isset($_GET["category"]) && $_GET["category"] !== "all") {
            echo '<div class="floating-back-btn">
                    <a href="?category=all">All Products</a>
                </div>';
        }
        ?>

        <!-- ===================================================
            Footer Section
        =================================================== -->
        <?php include("footer.html"); ?>

    </body>

    </html>

<?php
} else {
    header("Location:index.php");
}
?>