<?php
if (isset($_GET["category"])) {
    $data = [
        "category_mapping" => [
            "Whole Spices" => "wholeSpices",
            "Ground Spices" => "groundSpices",
            "Grains" => "grains",
            "Pulses" => "pulses",
            "Dry Fruits" => "dryFruits",
            "Makhana" => "makhana"
        ],

        "all" => [
            "title" => "All Products",
            "name" => [
                "Whole Spices",
                "Ground Spices",
                "Grains",
                "Pulses",
                "Dry Fruits",
                "Makhana"
            ],
            "description" => [
                "Premium whole spices for authentic flavor.",
                "Freshly ground spices for convenient cooking.",
                "Nutritious whole grains for healthy meals.",
                "Protein-rich pulses for hearty dishes.",
                "Premium quality dry fruits for snacking and cooking.",
                "Light and nutritious fox nuts for healthy snacking."
            ]
        ],

        "wholeSpices" => [
            "title" => "Whole Spices",
            "name" => [
                "Cumin Seeds",
                "Mustard Seeds",
                "Fenugreek Seeds",
                "Sesame Seeds",
                "Carom Seeds",
                "Fennel Seeds",
                "Coriander Seeds",
                "Cinnamon",
                "Bay Leaves",
                "Clove",
                "Cardamom",
                "Dry Red Chilli",
                "Poppy Seeds",
                "Star Anise"
            ],
            "description" => [
                "Aromatic seeds essential for tempering.",
                "Tiny seeds with a pungent flavor for pickling and curries.",
                "Bitter seeds with medicinal properties and distinctive aroma.",
                "Nutty seeds rich in calcium and healthy oils.",
                "Aromatic seeds with a thyme-like flavor for digestion.",
                "Sweet, licorice-flavored seeds for savory and sweet dishes.",
                "Citrusy, nutty seeds used in garam masala and curries.",
                "Sweet, woody bark that adds warmth to dishes.",
                "Aromatic leaves that add flavor to rice and curries.",
                "Intensely aromatic flower buds for sweet and savory dishes.",
                "Sweet, floral pods used in biryanis and desserts.",
                "Whole dried chilies for heat and color in cooking.",
                "Tiny seeds with a nutty flavor for texture and thickening.",
                "Beautiful star-shaped spice with a licorice flavor."
            ]
        ],

        "groundSpices" => [
            "title" => "Ground Spices",
            "name" => [
                "Red Chilli Powder",
                "Kashmiri Red Chilli Powder",
                "Turmeric Powder",
                "Coriander Cumin Powder",
                "Coriander Powder",
                "Cumin Powder",
                "Black Pepper Powder",
                "Dry Ginger Powder",
                "Dry Mango Powder",
                "Hing (Asafoetida)"
            ],
            "description" => [
                "Hot and spicy powder for adding heat to dishes.",
                "Vibrant red powder for rich color with mild heat.",
                "Golden yellow powder with anti-inflammatory properties.",
                "Perfect blend of coriander and cumin for balanced flavor.",
                "Mild, citrusy powder from ground coriander seeds.",
                "Aromatic, earthy powder for curries and spice blends.",
                "Pungent, sharp powder that adds heat to any dish.",
                "Warm, spicy powder with digestive benefits.",
                "Tangy powder that adds sourness to dishes.",
                "Pungent resin that adds onion-garlic flavor when cooked."
            ]
        ],

        "grains" => [
            "title" => "Grains",
            "name" => [
                "Rice",
                "Wheat",
                "Millets",
                "Barley",
                "Maize",
                "Sorghum"
            ],
            "description" => [
                "Versatile grain available in multiple varieties for everyday cooking.",
                "Wholesome staple grain perfect for breads, rotis and baking.",
                "Ancient grains rich in nutrients and fiber for healthy alternatives.",
                "Chewy, nutty grain ideal for soups, stews and healthy salads.",
                "Sweet, versatile grain for snacks, meals and flour.",
                "Drought-resistant ancient grain with a mild, earthy flavor."
            ]
        ],

        "pulses" => [
            "title" => "Pulses",
            "name" => [
                "Chickpea (Kabuli Chana)",
                "Black Chickpea (Kala Chana)",
                "Split Chickpea (Chana Dal)",
                "White Pea (Matar)",
                "Black Eyed Pea (Lobia)",
                "Pigeon Pea (Arhar/Toor)",
                "Green Gram (Moong Beans)",
                "Black Gram (Urad/Mah)",
                "Moth Beans",
                "Kidney Beans (Rajma)",
                "Soy Beans",
                "Lentils (Masoor)",
                "Yellow Corn"
            ],
            "description" => [
                "Large beige chickpeas for hummus, curries and salads.",
                "Smaller, darker chickpeas with earthy flavor and high fiber.",
                "Split chickpeas with a nutty flavor for dals and curries.",
                "White, round legumes perfect for curries and snacks.",
                "Distinctive beans with black 'eye' marking, mild and versatile.",
                "Yellow lentils with a mild flavor, perfect for dals.",
                "Small round beans that can be used whole or split.",
                "Rich, creamy beans essential for dosas and specialized dishes.",
                "Small, elongated beans with earthy flavor, popular in Rajasthani cuisine.",
                "Red, kidney-shaped beans for hearty dishes and chili.",
                "Protein-rich beans used in various vegetarian preparations.",
                "Quick-cooking red lentils for soups and stews.",
                "Sweet, nutritious corn kernels for soups and salads."
            ]
        ],

        "dryFruits" => [
            "title" => "Premium Dry Fruits",
            "name" => [
                "Almonds",
                "Cashew",
                "Pistachio",
                "Figs",
                "Apricot",
                "Raisins",
                "Dates",
                "Walnuts"
            ],
            "description" => [
                "Crunchy nuts rich in vitamin E and healthy fats.",
                "Creamy, kidney-shaped nuts perfect for snacking and cooking.",
                "Green nuts with a sweet flavor and high antioxidants.",
                "Sweet, chewy fruits packed with fiber and minerals.",
                "Tangy dried fruits rich in vitamin A and fiber.",
                "Sweet dried grapes for natural sweetness in dishes.",
                "Nature's candy with caramel-like sweetness and iron.",
                "Brain-shaped nuts rich in omega-3 fatty acids."
            ]
        ],

        "makhana" => [
            "title" => "Gourmet Makhana (Fox Nuts)",
            "name" => [
                "Plain Makhana",
                "Roasted Makhana",
                "Pink Salt Makhana",
                "Black Pepper Makhana",
                "Peri Peri Makhana",
                "Masala Makhana",
                "Cheddar Cheese Makhana"
            ],
            "description" => [
                "Natural puffed lotus seeds, light and nutritious.",
                "Lightly roasted fox nuts for extra crunch and flavor.",
                "Delicate pink salt seasoned makhana for a subtle taste.",
                "Spicy black pepper coated fox nuts for bold flavor.",
                "Hot and spicy makhana with African peri peri seasoning.",
                "Traditional Indian spice blend coated fox nuts.",
                "Cheesy, savory makhana with cheddar flavor."
            ]
        ]
    ];
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
            $img_url = "assets/images/products/{$categoryKey}/{$slug}.png";

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