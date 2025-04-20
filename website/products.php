<?php
if (isset($_GET["category"])) {
    $data = [
        "all" => [
            "title" => "All Products",
            "name" => [
                "Whole Spices",
                "Ground Spices",
                "Blended Spices",
                "Grains & Pulses",
                "Dry Fruits",
                "Makhana"
            ],
            "image_url" => [
                "assets\\images\\products\\all\\1.png",
                "assets\\images\\products\\all\\1.png",
                "assets\\images\\products\\all\\1.png",
                "assets\\images\\products\\all\\1.png",
                "assets\\images\\products\\all\\1.png",
                "assets\\images\\products\\all\\1.png"
            ],
            "desc" => [
                "Aromatic spices in their natural form.",
                "Finely ground spices for quick use.",
                "Custom blends for flavorful cooking.",
                "Staple grains and nutritious pulses.",
                "Sweet and healthy dry fruits.",
                "Crispy and light lotus seeds."
            ]
        ],
        "wholeSpices" => [
            "title" => "Whole Spices",
            "name" => [
                "Whole Spices",
                "Ground Spices",
                "Blended Spices",
                "Grains & Pulses",
                "Dry Fruits",
                "Makhana"
            ],
            "image_url" => [
                "assets\\images\\products\\all\\1.png",
                "assets\\images\\products\\all\\1.png",
                "assets\\images\\products\\all\\1.png",
                "assets\\images\\products\\all\\1.png",
                "assets\\images\\products\\all\\1.png",
                "assets\\images\\products\\all\\1.png"
            ],
            "desc" => [
                "Aromatic spices in their natural form.",
                "Finely ground spices for quick use.",
                "Custom blends for flavorful cooking.",
                "Staple grains and nutritious pulses.",
                "Sweet and healthy dry fruits.",
                "Crispy and light lotus seeds."
            ]
        ],
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
                $descs = $data[$category]["desc"];

                if (count($names) > 0 && count($images) === count($names)) {
                    echo "<div class='products_grid_wrapper'>";
                    echo "<div class='products_grid'>";
                    foreach ($names as $index => $product_name) {
                        $img_url = $images[$index];
                        $desc = $descs[$index];
                        $img_url_fixed = str_replace("\\", "/", $img_url); // convert for CSS
                        echo "<div class='product_item' style=\"background-image: url('$img_url_fixed');\">";
                            echo "<div class='overlay'>";
                                echo "<h3 class='product_name'>$product_name</h3>";
                                echo "<p class='product_desc'>$desc</p>";
                            echo "</div>";
                        echo "</div>";
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

