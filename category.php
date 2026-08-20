<?php

require_once __DIR__ . "/includes/confige.php";

/*
|--------------------------------------------------------------------------
| Category SEO Data
|--------------------------------------------------------------------------
*/

$category = strtolower(
    trim($_GET["category"] ?? "")
);

$categorySEO = [
    // your existing categories...
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
        "title" => "Plants | Greenleaf Nursery",

        "description" =>
            "Explore our collection of healthy indoor, outdoor, flowering and fruit plants from Greenleaf Nursery.",

        "heading" => "Our Plants",

        "image" => "assets/images/categories/plants.jpg"
    ];
}


/*
|--------------------------------------------------------------------------
| Website URL
|--------------------------------------------------------------------------
*/

$siteUrl = BASE_URL;


/*
|--------------------------------------------------------------------------
| Category URL
|--------------------------------------------------------------------------
*/

$categoryUrl = $category
    ? $siteUrl . "category/" . urlencode($category)
    : $siteUrl . "category/";


/*
|--------------------------------------------------------------------------
| OG Image
|--------------------------------------------------------------------------
*/

$ogImage =
    rtrim($siteUrl, "/") .
    "/" .
    ltrim($seo["image"], "/");


require_once __DIR__ . "/includes/header.php";

?>


<!-- =========================================================
     CATEGORY PAGE
========================================================= -->

<section class="category-page section-padding" aria-labelledby="categoryTitle">

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


            <div class="category-divider" aria-hidden="true"></div>

        </header>


        <!-- =========================================================
     BREADCRUMB
========================================================= -->

        <nav aria-label="breadcrumb" class="category-breadcrumb">

            <ol class="breadcrumb justify-content-center mb-0">

                <li class="breadcrumb-item">
                    <a href="<?= BASE_URL ?>">Home</a>
                </li>

                <li class="breadcrumb-item">
                    <a href="<?= BASE_URL ?>#categories">Categories</a>
                </li>

                <?php if (!empty($category)): ?>

                <li class="breadcrumb-item active" aria-current="page">
                    <?= htmlspecialchars(
                    $seo["heading"],
                    ENT_QUOTES,
                    "UTF-8"
                ); ?>
                </li>

                <?php else: ?>

                <li class="breadcrumb-item active" aria-current="page">
                    Plants
                </li>

                <?php endif; ?>

            </ol>

        </nav>
        <!-- =================================================
             PRODUCTS
        ================================================= -->

        <div id="categoryPlantsContainer" class="row g-4 category-products" aria-live="polite">

            <div class="col-12">

                <div class="category-loading">

                    <div class="loading-icon" aria-hidden="true">
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

        <div id="categoryEmptyState" class="category-empty" style="display:none;">

            <div class="empty-icon" aria-hidden="true">
                🌱
            </div>


            <h2>
                No Plants Found
            </h2>


            <p>
                We don't have any plants in this category right now.
                Please check back soon.
            </p>


            <a href="index.php#categories" class="primary-btn">
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


<script>
const BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>

<script src="<?= BASE_URL ?>assets/js/category-plants.js"></script>


<?php require_once "includes/footer.php"; ?>