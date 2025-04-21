<?php
if (isset($_GET["category"])) {
    $data = [
        "all" => [
            "title" => "All Products",
            "name" => [
                "Whole Spices",
                "Ground Spices",
                "Grains & Pulses",
                "Dry Fruits",
                "Makhana"
            ],
            "image_url" => [
                "assets\\images\\products\\all\\1.png",
                "assets\\images\\products\\all\\2.png",
                "assets\\images\\products\\all\\3.png",
                "assets\\images\\products\\all\\4.png",
                "assets\\images\\products\\all\\5.png"
            ],
            "description" => [
                "Premium whole spices for authentic flavor.",
                "Freshly ground spices for convenient cooking.",
                "Nutritious grains and pulses for healthy meals.",
                "Premium quality dry fruits for snacking and cooking.",
                "Light and nutritious fox nuts for healthy snacking."
            ]
        ],
        "wholeSpices" => [
            "title" => "Whole Spices Collection",
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
            "image_url" => [
                "assets\\images\\products\\wholeSpices\\1.png",
                "assets\\images\\products\\wholeSpices\\2.png",
                "assets\\images\\products\\wholeSpices\\3.png",
                "assets\\images\\products\\wholeSpices\\4.png",
                "assets\\images\\products\\wholeSpices\\5.png",
                "assets\\images\\products\\wholeSpices\\6.png",
                "assets\\images\\products\\wholeSpices\\7.png",
                "assets\\images\\products\\wholeSpices\\8.png",
                "assets\\images\\products\\wholeSpices\\9.png",
                "assets\\images\\products\\wholeSpices\\10.png",
                "assets\\images\\products\\wholeSpices\\11.png",
                "assets\\images\\products\\wholeSpices\\12.png",
                "assets\\images\\products\\wholeSpices\\13.png",
                "assets\\images\\products\\wholeSpices\\14.png"
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
            "title" => "Premium Ground Spices",
            "name" => [
                "Red Chilli Powder",
                "Kashmiri Red Chilli Powder",
                "Turmeric Powder",
                "Coriander-Cumin Powder",
                "Coriander Powder",
                "Cumin Powder",
                "Black Pepper Powder",
                "Dry Ginger Powder",
                "Dry Mango Powder",
                "Hing (Asafoetida)"
            ],
            "image_url" => [
                "assets\\images\\products\\groundSpices\\1.png",
                "assets\\images\\products\\groundSpices\\2.png",
                "assets\\images\\products\\groundSpices\\3.png",
                "assets\\images\\products\\groundSpices\\4.png",
                "assets\\images\\products\\groundSpices\\5.png",
                "assets\\images\\products\\groundSpices\\6.png",
                "assets\\images\\products\\groundSpices\\7.png",
                "assets\\images\\products\\groundSpices\\8.png",
                "assets\\images\\products\\groundSpices\\9.png",
                "assets\\images\\products\\groundSpices\\10.png"
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
        "grainsAndPulses" => [
            "title" => "Nutritious Grains & Pulses",
            "name" => [
                "Basmati Rice",
                "Brown Rice",
                "Red Lentils (Masoor Dal)",
                "Yellow Split Peas (Chana Dal)",
                "Black Gram (Urad Dal)",
                "Green Moong Dal",
                "Chickpeas (Kabuli Chana)",
                "Black Chickpeas (Kala Chana)",
                "Pearl Millet (Bajra)",
                "Quinoa"
            ],
            "image_url" => [
                "assets\\images\\products\\grainsAndPulses\\1.png",
                "assets\\images\\products\\grainsAndPulses\\2.png",
                "assets\\images\\products\\grainsAndPulses\\3.png",
                "assets\\images\\products\\grainsAndPulses\\4.png",
                "assets\\images\\products\\grainsAndPulses\\5.png",
                "assets\\images\\products\\grainsAndPulses\\6.png",
                "assets\\images\\products\\grainsAndPulses\\7.png",
                "assets\\images\\products\\grainsAndPulses\\8.png",
                "assets\\images\\products\\grainsAndPulses\\9.png",
                "assets\\images\\products\\grainsAndPulses\\10.png"
            ],
            "description" => [
                "Long-grain aromatic rice perfect for biryanis and pulaos.",
                "Whole grain rice with bran layer intact for added nutrition.",
                "Quick-cooking red lentils for soups and stews.",
                "Split chickpeas with a nutty flavor for dals and curries.",
                "Protein-rich lentils used in dosas and dals.",
                "Nutritious split mung beans for light, digestible dishes.",
                "Large beige chickpeas for hummus and chanas.",
                "Smaller, darker chickpeas with earthy flavor and high fiber.",
                "Ancient grain with nutty flavor and high nutrition.",
                "Protein-packed pseudocereal for salads and bowls."
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
            "image_url" => [
                "assets\\images\\products\\dryFruits\\1.png",
                "assets\\images\\products\\dryFruits\\2.png",
                "assets\\images\\products\\dryFruits\\3.png",
                "assets\\images\\products\\dryFruits\\4.png",
                "assets\\images\\products\\dryFruits\\5.png",
                "assets\\images\\products\\dryFruits\\6.png",
                "assets\\images\\products\\dryFruits\\7.png",
                "assets\\images\\products\\dryFruits\\8.png"
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
            "image_url" => [
                "assets\\images\\products\\makhana\\1.png",
                "assets\\images\\products\\makhana\\2.png",
                "assets\\images\\products\\makhana\\3.png",
                "assets\\images\\products\\makhana\\4.png",
                "assets\\images\\products\\makhana\\5.png",
                "assets\\images\\products\\makhana\\6.png",
                "assets\\images\\products\\makhana\\7.png"
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
    - Included via PHP to allow reusability across pages.
    =================================================== -->
        <?php include("nav.php"); ?>

        <div class="container">
            <div class="wrapper">
                <div class="page_title_wrapper">
                    <h2 class="page_title"><?php echo $data[$_GET["category"]]["title"]; ?></h2>
                </div>

                <?php
                $category = $_GET["category"];
                if (isset($data[$category])) {
                    $names = $data[$category]["name"];
                    $images = $data[$category]["image_url"];
                    $descriptions = $data[$category]["description"];

                    if (count($names) > 0 && count($images) === count($names)) {
                        echo "<div class='products_grid_wrapper'>";
                        echo "<div class='products_grid'>";
                        foreach ($names as $index => $product_name) {
                            $img_url = $images[$index];
                            $description = $descriptions[$index];
                            $img_url_fixed = str_replace("\\", "/", $img_url); // convert for CSS

                            // If we're in the "all" category, make items clickable
                            if ($category === "all") {
                                // Map the category name to its corresponding array key
                                $category_mapping = [
                                    "Whole Spices" => "wholeSpices",
                                    "Ground Spices" => "groundSpices",
                                    "Grains & Pulses" => "grainsAndPulses",
                                    "Dry Fruits" => "dryFruits",
                                    "Makhana" => "makhana"
                                ];

                                $target_category = $category_mapping[$product_name];
                                echo "<a href='?category={$target_category}' class='product_item' style=\"background-image: url('$img_url_fixed');\">";
                                echo "<div class='overlay'>";
                                echo "<h3 class='product_name'>$product_name</h3>";
                                echo "<p class='product_desc'>$description</p>";
                                echo "</div>";
                                echo "</a>";
                            } else {
                                // Regular non-clickable display for specific category items
                                echo "<div class='product_item' style=\"background-image: url('$img_url_fixed');\">";
                                echo "<div class='overlay'>";
                                echo "<h3 class='product_name'>$product_name</h3>";
                                echo "<p class='product_desc'>$description</p>";
                                echo "</div>";
                                echo "</div>";
                            }
                        }
                        echo "</div>";
                        echo "</div>";
                    } else {
                        echo "<p class='no_products'>No products found for this category.</p>";
                    }
                } else {
                    echo "<p class='no_products'>Category not found.</p>";
                }
                ?>
            </div>
        </div>


        <?php
        // Add this right before the closing </body> tag

        // Only show the back button if we're not on the 'all' category
        if (isset($_GET["category"]) && $_GET["category"] !== "all") {
            echo '<div class="floating-back-btn">
                    <a href="?category=all">All Products</a>
                </div>';
        }
        ?>

        <!-- ===================================================
    Footer Section
    - Included via PHP for consistency across pages.
    =================================================== -->
        <?php include("footer.html"); ?>

    </body>

    </html>

<?php
} else {
    header("Location:index.php");
}
?>