document.addEventListener("DOMContentLoaded", function () {

    /* =========================================
       CURRENT YEAR
    ========================================= */

    const yearElement = document.getElementById("currentYear");

    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }


    /* =========================================
       CLOSE MOBILE NAVBAR AFTER CLICK
    ========================================= */

    const navLinks = document.querySelectorAll(
        "#mainNavbar .nav-link"
    );

    const navbar = document.getElementById("mainNavbar");

    navLinks.forEach(function (link) {

        link.addEventListener("click", function () {

            if (navbar.classList.contains("show")) {

                const bsCollapse =
                    bootstrap.Collapse.getInstance(navbar);

                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }

        });

    });


    /* =========================================
       ACTIVE NAVIGATION
    ========================================= */

    const sections = document.querySelectorAll("section[id]");
    const navigationLinks =
        document.querySelectorAll(".navbar-nav .nav-link");

    window.addEventListener("scroll", function () {

        let currentSection = "";

        sections.forEach(function (section) {

            const sectionTop =
                section.offsetTop - 150;

            if (window.scrollY >= sectionTop) {
                currentSection = section.getAttribute("id");
            }

        });

        navigationLinks.forEach(function (link) {

            link.classList.remove("active");

            const href = link.getAttribute("href");

            if (href === "#" + currentSection) {
                link.classList.add("active");
            }

        });

    });


    /* =========================================
       SMOOTH SCROLL
    ========================================= */

    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {

        anchor.addEventListener("click", function (event) {

            const targetId =
                this.getAttribute("href");

            if (targetId === "#") {
                return;
            }

            const target =
                document.querySelector(targetId);

            if (target) {

                event.preventDefault();

                const navbarHeight =
                    document.querySelector(".navbar")
                    .offsetHeight;

                const targetPosition =
                    target.getBoundingClientRect().top +
                    window.scrollY -
                    navbarHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: "smooth"
                });

            }

        });

    });


    /* =========================================
       WHATSAPP ORDER TRACKING
    ========================================= */

    const whatsappButtons =
        document.querySelectorAll(".whatsapp-btn");

    whatsappButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            console.log(
                "WhatsApp order initiated:",
                this.getAttribute("aria-label")
            );

        });

    });

});
