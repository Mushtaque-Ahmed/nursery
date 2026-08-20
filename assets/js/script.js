/* =========================================
   LOAD PLANTS
========================================= */

document.addEventListener("DOMContentLoaded", function () {
    loadPlants();
});
let allPlants = [];

async function loadPlants() {

    const container =
        document.getElementById("plantsContainer");

    if (!container) {
        console.error("plantsContainer not found");
        return;
    }

    try {

        const response =
            await fetch(BASE_URL + "api/get_products.php");

        if (!response.ok) {
            throw new Error(
                "Could not connect to product API"
            );
        }

        const data =
            await response.json();

        // console.log("Products API:", data);

        if (!data.success) {
            throw new Error(
                data.message ||
                "Could not load plants"
            );
        }

        const products =
            data.products || [];
        allPlants = products;

        if (products.length === 0) {

            container.innerHTML = `
                <div class="col-12 text-center">
                    <p>
                        No plants available at the moment.
                    </p>
                </div>
            `;

            return;
        }

        container.innerHTML =
            products
                .map(createPlantCard)
                .join("");

    } catch (error) {

        console.error(
            "Product API error:",
            error
        );

        container.innerHTML = `
            <div class="col-12 text-center">
                <p>
                    Unable to load plants.
                    Please try again later.
                </p>
            </div>
        `;
    }
}
/*===========================================
category wise products
===========================================*/

function displayPlants(products) {

    const container =
        document.getElementById("plantsContainer");

    if (!container) {
        return;
    }

    if (products.length === 0) {

        container.innerHTML = `
            <div class="col-12 text-center">
                <p>
                    No plants found in this category.
                </p>
            </div>
        `;

        return;
    }

    container.innerHTML =
        products
            .map(createPlantCard)
            .join("");
}
/* =========================================
   CREATE PLANT CARD
========================================= */

function createPlantCard(product) {

    /*
    | Image
    */

    const image = product.image
        ? product.image
        : "assets/images/placeholder.jpg";


    /*
    | Category
    */

    const category =
        product.category || "Plant";


    /*
    | Price
    */

    const price =
        Number(product.price || 0)
            .toLocaleString("en-IN");


    /*
    | Sale price
    */

    const hasSale =
        product.sale_price !== null &&
        product.sale_price !== "" &&
        Number(product.sale_price) > 0;


    const salePrice =
        hasSale
            ? Number(product.sale_price)
                .toLocaleString("en-IN")
            : null;


    /*
    | Featured badge
    */

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


    /*
    | WhatsApp
    */

    const message =
        `Hello, I want to order ${product.name}`;

    const whatsappUrl =
        "https://wa.me/916001349602?text=" +
        encodeURIComponent(message);


    /*
    | Available
    */

    const isAvailable =
        product.available === true ||
        Number(product.available) === 1;


    return `

        <div class="col-6 col-lg-3">

            <article class="plant-card">

                <div class="plant-image">

                    ${badge}

                    <img
                        src="${image}"
                        alt="${escapeHTML(product.name)}"
                        loading="lazy"
                        onerror="this.onerror=null; this.src='assets/images/placeholder.jpg';"
                    >

                </div>


                <div class="plant-content">

                    <small class="plant-category">
                        ${escapeHTML(category)}
                    </small>


                    <h3>
                        ${escapeHTML(product.name)}
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
                                    rel="noopener"
                                    aria-label="Order ${escapeHTML(product.name)} on WhatsApp">

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