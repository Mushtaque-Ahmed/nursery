<?php require_once "includes/header.php"; ?>

<?php
require_once "includes/confige.php";
?>



<!-- =========================================================
     CONTACT PAGE
========================================================= -->

<main class="contact-page">

    <div class="container">


        <!-- =================================================
             HEADER
        ================================================= -->

        <header class="contact-header">

            <span class="contact-label">
                Get In Touch
            </span>

            <h1>
                Contact GreenLeaf Nursery
            </h1>

            <p>
                Have a question about our plants or need help
                choosing the right plant for your space?
                We'd love to hear from you.
            </p>

        </header>


        <!-- =================================================
             CONTACT CONTENT
        ================================================= -->

        <div class="row g-4">


            <!-- =================================================
                 CONTACT INFORMATION
            ================================================= -->

            <div class="col-lg-5">

                <div class="contact-card">

                    <h2>
                        Nursery Information
                    </h2>


                    <!-- Address -->

                    <div class="contact-item">

                        <div class="contact-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>

                        <div>

                            <h3>
                                Address
                            </h3>

                            <p id="contactAddress">
                                Loading address...
                            </p>

                        </div>

                    </div>


                    <!-- Phone -->

                    <div class="contact-item">

                        <div class="contact-icon">
                            <i class="bi bi-telephone"></i>
                        </div>

                        <div>

                            <h3>
                                Phone
                            </h3>

                            <a id="contactPhone" href="#">
                                Loading phone...
                            </a>

                        </div>

                    </div>


                    <!-- Opening Hours -->

                    <div class="contact-item">

                        <div class="contact-icon">
                            <i class="bi bi-clock"></i>
                        </div>

                        <div>

                            <h3>
                                Opening Hours
                            </h3>

                            <p>
                                Monday - Saturday<br>
                                8:00 AM - 6:00 PM
                            </p>

                        </div>

                    </div>


                    <!-- WhatsApp -->

                    <a id="contactWhatsapp" href="#" target="_blank" rel="noopener noreferrer" class="whatsapp-button"
                        style="display:none;">
                        <i class="bi bi-whatsapp"></i>

                        Chat on WhatsApp
                    </a>


                    <!-- Social -->

                    <div id="contactSocial" class="social-links">

                        <a id="contactFacebook" href="#" target="_blank" rel="noopener noreferrer" class="social-link"
                            aria-label="Facebook" style="display:none;">
                            <i class="bi bi-facebook"></i>
                        </a>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 GOOGLE MAP
            ================================================= -->

            <div class="col-lg-7">

                <div class="map-card">

                    <iframe src="https://www.google.com/maps?q=Guwahati,Assam,India&output=embed" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade" title="GreenLeaf Nursery Location"></iframe>

                </div>

            </div>

        </div>

    </div>

</main>


<!-- =========================================================
     SETTINGS JS
========================================================= -->
<script>
const BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>
<script src="assets/js/contact.js"></script>


<?php require_once "includes/footer.php"; ?>