<?php

require_once "includes/confige.php";


/*
|--------------------------------------------------------------------------
| Plants Page SEO
|--------------------------------------------------------------------------
*/

$siteUrl = BASE_URL;


$seo = [

    "title" =>
        "Plants Collection | Indoor, Outdoor, Flowering & Fruit Plants",

    "description" =>
        "Explore Greenleaf Nursery's collection of indoor, outdoor, flowering and fruit plants. Find healthy plants for your home, garden, balcony and office.",

    "heading" =>
        "Our Plants",

    "image" =>
        "assets/images/categories/plants.jpg"

];


$categoryUrl =
    $siteUrl . "plants";


$ogImage =
    $siteUrl . $seo["image"];


require_once "includes/header.php";

?>
<main class="plants-page">
    <section class="page-header py-4">

        <div class="container">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL ?>">
                            <i class="bi bi-house"></i>
                            Home
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Plants
                    </li>

                </ol>

            </nav>

        </div>

    </section>
    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <section class="plants-header">

        <div class="container">

            <div class="text-center">

                <span class="section-label">
                    Greenleaf Collection
                </span>

                <h1>
                    Our Plants
                </h1>

                <p>
                    Explore our collection of healthy indoor,
                    outdoor, flowering and fruit plants.
                </p>

            </div>

        </div>

    </section>


    <!-- =====================================================
         SEARCH & FILTER
    ====================================================== -->

    <section class="plants-toolbar">

        <div class="container">

            <div class="row g-3 align-items-center">

                <div class="col-lg-7">

                    <div class="plant-search">

                        <i class="bi bi-search"></i>

                        <input type="search" id="plantSearch" class="form-control" placeholder="Search plants..."
                            autocomplete="off">

                    </div>

                </div>


                <div class="col-lg-5">

                    <select id="plantCategory" class="form-select">

                        <option value="">
                            All Categories
                        </option>

                        <option value="garden">
                            Garden Plants
                        </option>

                        <option value="outdoor">
                            Outdoor Plants
                        </option>

                        <option value="flowering">
                            Flowering Plants
                        </option>

                        <option value="fruit">
                            Fruit Plants
                        </option>

                    </select>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         PRODUCTS
    ====================================================== -->

    <section class="plants-list-section">

        <div class="container">


            <!-- Result information -->

            <div id="plantsResultInfo" class="plants-result-info">
                Loading plants...
            </div>


            <!-- Products -->

            <div id="plantsContainer" class="row g-4" aria-live="polite">

            </div>


            <!-- Empty -->

            <div id="plantsEmpty" class="plants-empty" style="display:none;">

                <div class="empty-icon">
                    🌱
                </div>

                <h2>
                    No Plants Found
                </h2>

                <p>
                    We couldn't find any plants matching
                    your search.
                </p>

            </div>


            <!-- =================================================
                 PAGINATION
            ================================================== -->

            <nav id="plantsPagination" class="plants-pagination" aria-label="Plants pagination">

            </nav>

        </div>

    </section>

</main>
<script>
const BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>
<script src="<?= BASE_URL ?>assets/js/plants.js"></script>
<script type="application/ld+json">
<?= json_encode(

    [
        "@context" => "https://schema.org",

        "@type" => "CollectionPage",

        "name" =>
            "Greenleaf Nursery Plants Collection",

        "description" =>
            $seo["description"],

        "url" =>
            $categoryUrl,

        "isPartOf" => [

            "@type" => "WebSite",

            "name" =>
                "Greenleaf Nursery",

            "url" =>
                $siteUrl

        ]

    ],

    JSON_UNESCAPED_SLASHES |
    JSON_UNESCAPED_UNICODE |
    JSON_PRETTY_PRINT

); ?>
</script>


<?php require_once "includes/footer.php"; ?>