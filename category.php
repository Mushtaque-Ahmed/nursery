<?php

/*
|--------------------------------------------------------------------------
| Category SEO Data
|--------------------------------------------------------------------------
*/

$category = strtolower(
    trim($_GET["category"] ?? "")
);


$categorySEO = [

    "indoor" => [
        "title" => "Indoor Plants | Beautiful Plants for Your Home",
        "description" =>
            "Explore our collection of indoor plants perfect for homes, offices and indoor spaces. Find beautiful, healthy plants from our nursery.",
        "heading" => "Indoor Plants",
        "image" => "assets/images/categories/indoor-plants.jpg"
    ],

    "outdoor" => [
        "title" => "Outdoor Plants | Plants for Gardens & Outdoor Spaces",
        "description" =>
            "Discover outdoor plants perfect for gardens, balconies and outdoor spaces. Browse our collection of healthy nursery plants.",
        "heading" => "Outdoor Plants",
        "image" => "assets/images/categories/outdoor-plants.jpg"
    ],

    "flowering" => [
        "title" => "Flowering Plants | Beautiful Plants & Flowers",
        "description" =>
            "Shop beautiful flowering plants from our nursery. Add colour and natural beauty to your home, balcony or garden.",
        "heading" => "Flowering Plants",
        "image" => "assets/images/categories/flowering-plants.jpg"
    ],

    "fruit" => [
        "title" => "Fruit Plants | Grow Your Own Fruit",
        "description" =>
            "Explore fruit plants from our nursery and grow fresh fruits at home or in your garden.",
        "heading" => "Fruit Plants",
        "image" => "assets/images/categories/fruit-plants.jpg"
    ]

];


/*
|--------------------------------------------------------------------------
| Default SEO
|--------------------------------------------------------------------------
*/

if (isset($categorySEO[$category])) {

    $seo = $categorySEO[$category];

} else {

    $category = "";

    $seo = [

        "title" =>
            "Plants | Greenleaf Nursery",

        "description" =>
            "Explore our collection of healthy indoor, outdoor, flowering and fruit plants from Greenleaf Nursery.",

        "heading" =>
            "Our Plants",

        "image" =>
            "assets/images/categories/plants.jpg"

    ];

}


/*
|--------------------------------------------------------------------------
| Website URL
|--------------------------------------------------------------------------
|
| Change this when your real domain is available.
|
*/

$siteUrl = $siteUrl ?? "http://localhost/nursery";


/*
|--------------------------------------------------------------------------
| Category URL
|--------------------------------------------------------------------------
*/

$categoryUrl =
    $siteUrl .
    "/category.php" .
    (
        $category
            ? "?category=" . urlencode($category)
            : ""
    );


/*
|--------------------------------------------------------------------------
| OG Image
|--------------------------------------------------------------------------
*/

$ogImage =
    $siteUrl .
    "/" .
    $seo["image"];


require_once "includes/header.php";

?>


<!-- =========================================================
     CATEGORY PAGE
========================================================= -->

<section
    class="category-page section-padding"
    aria-labelledby="categoryTitle"
>

    <div class="container">


        <!-- =================================================
             CATEGORY HEADER
        ================================================= -->

        <header class="category-page-header text-center">

            <span class="section-label">
                Our Collection
            </span>


            <h1 id="categoryTitle">

                <?= htmlspecialchars(
                    $seo["heading"],
                    ENT_QUOTES,
                    "UTF-8"
                ); ?>

            </h1>


            <p id="categoryDescription">

                <?= htmlspecialchars(
                    $seo["description"],
                    ENT_QUOTES,
                    "UTF-8"
                ); ?>

            </p>


            <div
                class="category-divider"
                aria-hidden="true"
            ></div>

        </header>


        <!-- =================================================
             PRODUCTS
        ================================================= -->

        <div
            id="categoryPlantsContainer"
            class="row g-4 category-products"
            aria-live="polite"
        >

            <div class="col-12">

                <div class="category-loading">

                    <div
                        class="loading-icon"
                        aria-hidden="true"
                    >
                        🌿
                    </div>

                    <p>
                        Loading plants...
                    </p>

                </div>

            </div>

        </div>


        <!-- =================================================
             EMPTY STATE
        ================================================= -->

        <div
            id="categoryEmptyState"
            class="category-empty"
            style="display:none;"
        >

            <div
                class="empty-icon"
                aria-hidden="true"
            >
                🌱
            </div>


            <h2>
                No Plants Found
            </h2>


            <p>
                We don't have any plants in this category right now.
                Please check back soon.
            </p>


            <a
                href="index.php#categories"
                class="primary-btn"
            >
                Explore Categories
            </a>

        </div>

    </div>

</section>


<!-- =========================================================
     CATEGORY JSON-LD
========================================================= -->

<script type="application/ld+json">

<?= json_encode(

    [
        "@context" => "https://schema.org",

        "@type" => "CollectionPage",

        "name" => $seo["heading"],

        "description" => $seo["description"],

        "url" => $categoryUrl

    ],

    JSON_UNESCAPED_SLASHES |
    JSON_UNESCAPED_UNICODE |
    JSON_PRETTY_PRINT

); ?>

</script>


<script src="assets/js/category-plants.js"></script>


<?php require_once "includes/footer.php"; ?>