/* =========================================
   CATEGORY PLANTS PAGE
========================================= */

document.addEventListener("DOMContentLoaded", function () {

    loadCategoryPlants();

});


/* =========================================
   GET CATEGORY FROM CLEAN URL
========================================= */

function getCategoryFromURL() {

    const pathParts =
        window.location.pathname
            .split("/")
            .filter(Boolean);


    const categoryIndex =
        pathParts.indexOf("category");


    if (
        categoryIndex !== -1 &&
        pathParts[categoryIndex + 1]
    ) {

        return decodeURIComponent(
            pathParts[categoryIndex + 1]
        ).toLowerCase();

    }


    return null;

}


/* =========================================
   LOAD CATEGORY PLANTS
========================================= */

async function loadCategoryPlants() {

    const container =
        document.getElementById(
            "categoryPlantsContainer"
        );


    if (!container) {
        return;
    }


    /* -----------------------------------------
       Get category
    ----------------------------------------- */

    const category =
        getCategoryFromURL();


    /* -----------------------------------------
       Category information
    ----------------------------------------- */

    const categoryNames = {

        indoor: "Indoor Plants",

        outdoor: "Outdoor Plants",

        flowering: "Flowering Plants",

        fruit: "Fruit Plants"

    };


    const categoryDescriptions = {

        indoor:
            "Beautiful indoor plants perfect for your home, office and indoor spaces.",

        outdoor:
            "Healthy outdoor plants perfect for gardens, balconies and open spaces.",

        flowering:
            "Beautiful flowering plants to add colour and natural beauty to your garden.",

        fruit:
            "Fruit plants that are perfect for growing fresh fruits in your garden."

    };


    /* -----------------------------------------
       No category
    ----------------------------------------- */

    if (!category) {

        document.getElementById(
            "categoryTitle"
        ).textContent =
            "All Plants";


        document.getElementById(
            "categoryDescription"
        ).textContent =
            "Explore our complete collection of plants.";


        loadAllPlants(container);

        return;

    }


    /* -----------------------------------------
       Invalid category
    ----------------------------------------- */

    if (!categoryNames[category]) {

        document.getElementById(
            "categoryTitle"
        ).textContent =
            "Plants";


        document.getElementById(
            "categoryDescription"
        ).textContent =
            "Explore our collection of beautiful plants.";

    } else {

        /* -----------------------------------------
           Update heading
        ----------------------------------------- */

        document.getElementById(
            "categoryTitle"
        ).textContent =
            categoryNames[category];


        document.getElementById(
            "categoryDescription"
        ).textContent =
            categoryDescriptions[category];

    }


    /* -----------------------------------------
       API
    ----------------------------------------- */

    try {

        const response =
            await fetch(
                BASE_URL + "api/get_category_products.php?category=" +
                encodeURIComponent(category)
            );


        if (!response.ok) {

            throw new Error(
                "Could not connect to category API"
            );

        }


        const data =
            await response.json();


        console.log(
            "Category API:",
            data
        );


        if (!data.success) {

            throw new Error(
                data.message ||
                "Could not load plants"
            );

        }


        const products =
            data.products || [];


        /* -----------------------------------------
           No products
        ----------------------------------------- */

        if (products.length === 0) {

            container.innerHTML = `

                <div class="col-12">

                    <div class="category-no-products text-center">

                        <div class="empty-icon">
                            🌱
                        </div>

                        <h2>
                            No Plants Found
                        </h2>

                        <p>
                            There are currently no plants
                            in this category.
                        </p>

                    </div>

                </div>

            `;

            return;

        }


        /* -----------------------------------------
           Render products
        ----------------------------------------- */

        container.innerHTML =
            products
                .map(createCategoryPlantCard)
                .join("");


    } catch (error) {

        console.error(
            "Category API error:",
            error
        );


        container.innerHTML = `

            <div class="col-12">

                <div class="category-error text-center">

                    <div class="empty-icon">
                        🌿
                    </div>

                    <h2>
                        Unable to Load Plants
                    </h2>

                    <p>
                        Please try again later.
                    </p>

                </div>

            </div>

        `;

    }

}


/* =========================================
   LOAD ALL PLANTS
========================================= */

async function loadAllPlants(container) {

    try {

        const response =
            await fetch(
                BASE_URL + "api/get_products.php"
            );


        if (!response.ok) {

            throw new Error(
                "Could not load products"
            );

        }


        const data =
            await response.json();


        if (!data.success) {

            throw new Error(
                data.message ||
                "Could not load products"
            );

        }


        const products =
            data.products || [];


        if (products.length === 0) {

            container.innerHTML = `

                <div class="col-12 text-center">

                    <p>
                        No plants available.
                    </p>

                </div>

            `;

            return;

        }


        container.innerHTML =
            products
                .map(createCategoryPlantCard)
                .join("");


    } catch (error) {

        console.error(
            "Products API error:",
            error
        );


        container.innerHTML = `

            <div class="col-12 text-center">

                <p>
                    Unable to load plants.
                </p>

            </div>

        `;

    }

}


/* =========================================
   CREATE PLANT CARD
========================================= */

function createCategoryPlantCard(product) {

    /* -----------------------------------------
       Image
    ----------------------------------------- */

    const image =
        product.image ||
        "assets/images/placeholder.jpg";


    /* -----------------------------------------
       Category
    ----------------------------------------- */

    const category =
        product.category ||
        "Plant";


    /* -----------------------------------------
       Product name
    ----------------------------------------- */

    const productName =
        product.name ||
        "Plant";


    /* -----------------------------------------
       Price
    ----------------------------------------- */

    const price =
        Number(product.price || 0)
            .toLocaleString("en-IN");


    /* -----------------------------------------
       Sale price
    ----------------------------------------- */

    const hasSale =
        product.sale_price !== null &&
        product.sale_price !== "" &&
        Number(product.sale_price) > 0;


    const salePrice =
        hasSale
            ? Number(product.sale_price)
                .toLocaleString("en-IN")
            : null;


    /* -----------------------------------------
       Featured badge
    ----------------------------------------- */

    let badge = "";


    if (
        product.featured === true ||
        Number(product.featured) === 1
    ) {

        badge = `

            <span class="plant-badge">
                Featured
            </span>

        `;

    }


    /* -----------------------------------------
       WhatsApp
    ----------------------------------------- */

    const message =
        `Hello, I want to order ${productName}`;


    const whatsappUrl =
        "https://wa.me/919999999999?text=" +
        encodeURIComponent(message);


    /* -----------------------------------------
       Availability
    ----------------------------------------- */

    const isAvailable =
        product.available === true ||
        Number(product.available) === 1;


    /* -----------------------------------------
       Card
    ----------------------------------------- */

    return `

        <div class="col-6 col-lg-3">

            <article class="plant-card">

                <div class="plant-image">

                    ${badge}


                    <img
                        src="${escapeHTML(image)}"
                        alt="${escapeHTML(productName)}"
                        loading="lazy"
                        onerror="this.onerror=null; this.src='assets/images/placeholder.jpg';"
                    >

                </div>


                <div class="plant-content">


                    <small class="plant-category">

                        ${escapeHTML(category)}

                    </small>


                    <h3>

                        ${escapeHTML(productName)}

                    </h3>


                    <div class="plant-bottom">


                        <div class="plant-price">

                            ${hasSale

            ? `

                                    <strong>
                                        ₹${salePrice}
                                    </strong>

                                    <del>
                                        ₹${price}
                                    </del>

                                `

            : `

                                    <strong>
                                        ₹${price}
                                    </strong>

                                `
        }

                        </div>


                        ${isAvailable

            ? `

                                <a
                                    href="${whatsappUrl}"
                                    class="whatsapp-btn"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="Order ${escapeHTML(productName)} on WhatsApp"
                                >

                                    <i class="bi bi-whatsapp"></i>

                                </a>

                            `

            : `

                                <span class="out-of-stock">
                                    Out of Stock
                                </span>

                            `
        }


                    </div>


                </div>

            </article>

        </div>

    `;

}


/* =========================================
   ESCAPE HTML
========================================= */

function escapeHTML(value) {

    return String(value ?? "")

        .replace(/&/g, "&amp;")

        .replace(/</g, "&lt;")

        .replace(/>/g, "&gt;")

        .replace(/"/g, "&quot;")

        .replace(/'/g, "&#039;");

}