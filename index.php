<?php require_once "includes/header.php"; ?>

<!-- PAGE CONTENT HERE -->

<!-- ================= HERO ================= -->
<header id="home" class="hero-section">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <span class="hero-badge">
                    <i class="bi bi-leaf"></i>
                    Bring Nature Home
                </span>

                <h1 class="hero-title">
                    Beautiful Plants for a
                    <span>Greener Life</span>
                </h1>

                <p class="hero-text">
                    Discover healthy and beautiful plants for your home,
                    balcony and garden. Choose your favourite plant and
                    order directly through WhatsApp.
                </p>

                <div class="hero-buttons">

                    <a href="<?=BASE_URL?>plants" class="btn btn-primary-custom btn-lg">
                        Explore Plants
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                    <a href="#categories" class="btn btn-outline-custom btn-lg">
                        View Categories
                    </a>

                </div>

                <div class="hero-features">

                    <div>
                        <i class="bi bi-check-circle-fill"></i>
                        Healthy Plants
                    </div>

                    <div>
                        <i class="bi bi-check-circle-fill"></i>
                        Quality Guaranteed
                    </div>

                    <div>
                        <i class="bi bi-check-circle-fill"></i>
                        Easy Ordering
                    </div>

                </div>

            </div>


            <div class="col-lg-6">

                <div class="hero-image-wrapper">

                    <div class="hero-circle"></div>

                    <img src="assets/images/hero-plant.webp" alt="Beautiful green indoor plants"
                        class="img-fluid hero-image">

                    <div class="floating-card floating-card-one">
                        <i class="bi bi-flower1"></i>

                        <div>
                            <strong>Fresh Plants</strong>
                            <small>Ready for your home</small>
                        </div>
                    </div>

                    <div class="floating-card floating-card-two">
                        <strong>100+</strong>
                        <small>Plant Varieties</small>
                    </div>

                </div>

            </div>

        </div>

    </div>
</header>


<!-- ================= CATEGORIES ================= -->
<section id="categories" class="section-padding">

    <div class="container">

        <div class="section-heading text-center">

            <span class="section-label">
                Explore
            </span>

            <h2>
                Find the Perfect Plant
            </h2>

            <p>
                Choose from our collection of plants for every space
                and gardening style.
            </p>

        </div>


        <div class="row g-4">

            <div class="col-6 col-lg-3">

                <a href="category/fruit" class="category-card">

                    <div class="category-icon green">
                        <i class="bi bi-house-heart"></i>
                    </div>

                    <h3>Fruits Plants</h3>

                    <p>Plants for your home</p>

                </a>

            </div>


            <div class="col-6 col-lg-3">

                <a href="category/outdoor" class="category-card">

                    <div class="category-icon orange">
                        <i class="bi bi-brightness-high"></i>
                    </div>

                    <h3>Outdoor Plants</h3>

                    <p>Perfect for gardens</p>

                </a>

            </div>


            <div class="col-6 col-lg-3">

                <a href="category/flowering" class="category-card">

                    <div class="category-icon pink">
                        <i class="bi bi-flower2"></i>
                    </div>

                    <h3>Flowering Plants</h3>

                    <p>Add colour to life</p>

                </a>

            </div>


            <div class="col-6 col-lg-3">

                <a href="category/garden" class="category-card" data-category="Garden">

                    <div class="category-icon blue">
                        <i class="bi bi-tree"></i>
                    </div>

                    <h3>Garden Plants</h3>

                    <p>Grow your garden</p>

                </a>

            </div>

        </div>

    </div>

</section>


<!-- ================= FEATURED PLANTS ================= -->
<section id="plants" class="plants-section section-padding">

    <div class="container">

        <div class="row align-items-end mb-5">

            <div class="col-md-7">

                <span class="section-label">
                    Our Collection
                </span>

                <h2>
                    Popular Plants
                </h2>

                <p class="mb-0">
                    Explore some of our most loved plants.
                </p>

            </div>

            <div class="col-md-5 text-md-end mt-3 mt-md-0">

                <a href="#" class="view-all-link">
                    View All Plants
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

        </div>


        <div class="row g-4" id="plantsContainer">

            <!-- Products will be loaded from API -->

            <div class="col-12 text-center" id="plantsLoading">
                <p>Loading plants...</p>
            </div>

        </div>

    </div>

</section>


<!-- ================= WHY CHOOSE US ================= -->
<section id="about" class="why-section section-padding">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <img src="<?= BASE_URL ?>assets/images/arecanut-plant.webp"
                    alt="Healthy arecanut plants at GreenLeaf Nursery" class="img-fluid rounded-4 shadow" width="800"
                    height="600" loading="lazy">

            </div>


            <div class="col-lg-6">

                <span class="section-label">
                    Why GreenLeaf
                </span>

                <h2>
                    We Help You Grow a Greener World
                </h2>

                <p class="about-text">
                    At GreenLeaf Nursery, we believe plants make every
                    space more beautiful. We carefully select healthy
                    plants so you can bring nature closer to your home.
                </p>


                <div class="benefit">

                    <div class="benefit-icon">
                        <i class="bi bi-flower1"></i>
                    </div>

                    <div>
                        <h3>Healthy Plants</h3>
                        <p>
                            Carefully maintained plants ready for your home
                            and garden.
                        </p>
                    </div>

                </div>


                <div class="benefit">

                    <div class="benefit-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <div>
                        <h3>Quality You Can Trust</h3>
                        <p>
                            We focus on quality and healthy plant growth.
                        </p>
                    </div>

                </div>


                <div class="benefit">

                    <div class="benefit-icon">
                        <i class="bi bi-whatsapp"></i>
                    </div>

                    <div>
                        <h3>Easy Ordering</h3>
                        <p>
                            Select your plant and contact us directly
                            through WhatsApp.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ================= CTA ================= -->
<section class="cta-section">

    <div class="container">

        <div class="cta-box text-center">

            <span class="cta-icon">
                <i class="bi bi-leaf"></i>
            </span>

            <h2>
                Ready to Make Your Space Greener?
            </h2>

            <p>
                Explore our plant collection and find your next favourite
                plant today.
            </p>

            <a href="<?=BASE_URL?>plants" class="btn btn-light btn-lg">
                Explore Plants
                <i class="bi bi-arrow-right ms-2"></i>
            </a>

        </div>

    </div>

</section>

<script>
const BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>
<script src="<?= BASE_URL ?>assets/js/script.js"></script>
<?php require_once "includes/footer.php"; ?>