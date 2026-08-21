<?php

require_once "includes/confige.php";


/*
|--------------------------------------------------------------------------
| Website URL
|--------------------------------------------------------------------------
*/

$siteUrl = BASE_URL;


/*
|--------------------------------------------------------------------------
| About Page SEO
|--------------------------------------------------------------------------
*/

$seo = [

    "title" =>
        "About Greenleaf Nursery | Quality Plants for Every Space",

    "description" =>
        "Learn about Greenleaf Nursery and explore our collection of healthy indoor, outdoor, flowering and fruit plants for homes, gardens and offices.",

    "heading" =>
        "About Greenleaf Nursery",

    "image" =>
        "assets/images/categories/plants.jpg"

];


/*
|--------------------------------------------------------------------------
| Canonical URL
|--------------------------------------------------------------------------
*/

$categoryUrl =
    $siteUrl . "about";


/*
|--------------------------------------------------------------------------
| Open Graph Image
|--------------------------------------------------------------------------
*/

$ogImage =
    $siteUrl . $seo["image"];


require_once "includes/header.php";

?>


<!-- =========================================================
     ABOUT PAGE
========================================================= -->

<main class="about-page">
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
                        About Us
                    </li>

                </ol>

            </nav>

        </div>

    </section>

    <!-- =====================================================
         HERO
    ====================================================== -->

    <section class="about-hero">

        <div class="container">

            <div class="row align-items-center g-5">


                <!-- TEXT -->

                <div class="col-lg-6">

                    <span class="about-label">
                        About Us
                    </span>


                    <h1>
                        Growing Green Spaces,
                        One Plant at a Time
                    </h1>


                    <p class="about-hero-text">

                        Welcome to
                        <strong>Greenleaf Nursery</strong>,
                        your place to discover beautiful and
                        healthy plants for your home, garden,
                        balcony and office.

                    </p>


                    <p>

                        We believe that plants can make everyday
                        spaces more peaceful, beautiful and
                        refreshing. Our goal is to make it easier
                        for plant lovers to find the right plants
                        and care for them with confidence.

                    </p>


                    <a href="<?= BASE_URL ?>#categories" class="about-btn">
                        Explore Our Plants

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>


                <!-- IMAGE -->

                <div class="col-lg-6">

                    <div class="about-image">

                        <img src="<?= BASE_URL ?>assets/images/plants.webp" alt="Healthy plants at Greenleaf Nursery"
                            class="img-fluid" width="800" height="600" loading="eager">

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         OUR STORY
    ====================================================== -->

    <section class="about-story">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-9">

                    <div class="about-content-card">

                        <span class="about-label">
                            Our Story
                        </span>


                        <h2>
                            A Nursery Built Around a Love for Plants
                        </h2>


                        <p>

                            Greenleaf Nursery was created with a
                            simple idea: bringing healthy and
                            beautiful plants closer to people.

                        </p>


                        <p>

                            Whether you are buying your first
                            houseplant, creating a balcony garden
                            or growing plants in your outdoor
                            space, choosing the right plant can
                            make a big difference.

                        </p>


                        <p>

                            Our collection includes indoor plants,
                            outdoor plants, flowering plants and
                            fruit plants selected for different
                            types of spaces and gardening needs.

                        </p>


                        <p>

                            We want every plant to be more than
                            just another purchase. We want it to
                            become part of your home, garden and
                            everyday life.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         WHY CHOOSE US
    ====================================================== -->

    <section class="why-choose-us">

        <div class="container">

            <div class="section-heading text-center">

                <span class="about-label">
                    Why Greenleaf
                </span>

                <h2>
                    Why Choose Greenleaf Nursery?
                </h2>

                <p>
                    We focus on making your plant-buying
                    experience simple and enjoyable.
                </p>

            </div>


            <div class="row g-4 mt-3">


                <!-- CARD 1 -->

                <div class="col-md-6 col-lg-3">

                    <div class="feature-card">

                        <div class="feature-icon">

                            <i class="bi bi-flower1"></i>

                        </div>


                        <h3>
                            Healthy Plants
                        </h3>


                        <p>
                            We focus on offering plants that are
                            healthy, attractive and suitable for
                            different growing environments.
                        </p>

                    </div>

                </div>


                <!-- CARD 2 -->

                <div class="col-md-6 col-lg-3">

                    <div class="feature-card">

                        <div class="feature-icon">

                            <i class="bi bi-house-heart"></i>

                        </div>


                        <h3>
                            Plants for Every Space
                        </h3>


                        <p>
                            Find plants suitable for homes,
                            balconies, offices, gardens and
                            other spaces.
                        </p>

                    </div>

                </div>


                <!-- CARD 3 -->

                <div class="col-md-6 col-lg-3">

                    <div class="feature-card">

                        <div class="feature-icon">

                            <i class="bi bi-person-check"></i>

                        </div>


                        <h3>
                            Helpful Guidance
                        </h3>


                        <p>
                            We aim to help plant lovers choose
                            suitable plants and understand their
                            basic care requirements.
                        </p>

                    </div>

                </div>


                <!-- CARD 4 -->

                <div class="col-md-6 col-lg-3">

                    <div class="feature-card">

                        <div class="feature-icon">

                            <i class="bi bi-tree"></i>

                        </div>


                        <h3>
                            Love for Nature
                        </h3>


                        <p>
                            We believe that adding more greenery
                            to our surroundings creates healthier
                            and more pleasant spaces.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         PLANT COLLECTION
    ====================================================== -->

    <section class="plant-collection">

        <div class="container">

            <div class="row align-items-center g-5">


                <div class="col-lg-6">

                    <div class="collection-image">

                        <img src="<?= BASE_URL ?>assets/images/flowering-plants.png"
                            alt="Flowering plants at Greenleaf Nursery" class="img-fluid" width="800" height="600"
                            loading="lazy">

                    </div>

                </div>


                <div class="col-lg-6">

                    <span class="about-label">
                        Our Collection
                    </span>


                    <h2>
                        Find the Right Plant
                        for Your Space
                    </h2>


                    <p>

                        Every space has different light,
                        temperature and care conditions.
                        That's why our collection includes
                        different types of plants for different
                        gardening needs.

                    </p>


                    <ul class="plant-list">

                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Indoor Plants
                        </li>

                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Outdoor Plants
                        </li>

                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Flowering Plants
                        </li>

                        <li>
                            <i class="bi bi-check-circle-fill"></i>
                            Fruit Plants
                        </li>

                    </ul>


                    <a href="<?= BASE_URL ?>plants" class="about-btn">
                        View Plant Collection

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         CTA
    ====================================================== -->

    <section class="about-cta">

        <div class="container">

            <div class="about-cta-content text-center">

                <span class="about-label">
                    Let's Grow Together
                </span>


                <h2>
                    Ready to Add More Greenery
                    to Your Space?
                </h2>


                <p>
                    Explore our plants or get in touch with
                    Greenleaf Nursery for more information.
                </p>


                <div class="about-cta-buttons">

                    <a href="<?= BASE_URL ?>plants" class="about-btn">
                        Explore Plants
                    </a>


                    <a href="<?= BASE_URL ?>contact" class="about-outline-btn">
                        Contact Us
                    </a>

                </div>

            </div>

        </div>

    </section>


</main>


<!-- =========================================================
     ABOUT PAGE CSS
========================================================= -->



<!-- =========================================================
     ABOUT PAGE JSON-LD
========================================================= -->

<script type="application/ld+json">
<?= json_encode(

    [
        "@context" => "https://schema.org",

        "@type" => "AboutPage",

        "name" =>
            $seo["heading"],

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