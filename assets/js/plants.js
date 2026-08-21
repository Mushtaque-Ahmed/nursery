document.addEventListener("DOMContentLoaded", () => {

    const container =
        document.getElementById("plantsContainer");

    const pagination =
        document.getElementById("plantsPagination");

    const searchInput =
        document.getElementById("plantSearch");

    const categorySelect =
        document.getElementById("plantCategory");

    const resultInfo =
        document.getElementById("plantsResultInfo");

    const emptyState =
        document.getElementById("plantsEmpty");


    if (!container) {
        return;
    }


    let currentPage = 1;

    let searchTimer = null;


    /*
    |--------------------------------------------------------------------------
    | Nursery Phone
    |--------------------------------------------------------------------------
    */

    let nurseryPhone = "";


    /*
    |--------------------------------------------------------------------------
    | Load Plants
    |--------------------------------------------------------------------------
    */

    async function loadPlants(page = 1) {

        currentPage = page;


        container.innerHTML = `
            <div class="col-12 text-center py-5">

                <div class="spinner-border text-success"
                     role="status">
                </div>

                <p class="mt-3">
                    Loading plants...
                </p>

            </div>
        `;


        emptyState.style.display = "none";

        pagination.innerHTML = "";


        const search =
            searchInput
                ? searchInput.value.trim()
                : "";


        const category =
            categorySelect
                ? categorySelect.value
                : "";


        const params =
            new URLSearchParams();


        params.set(
            "page",
            page
        );


        if (search) {

            params.set(
                "search",
                search
            );
        }


        if (category) {

            params.set(
                "category",
                category
            );
        }


        try {

            const response =
                await fetch(
                    BASE_URL + "api/get_plants.php?" +
                    params.toString()
                );


            const result =
                await response.json();


            if (
                !response.ok ||
                !result.success
            ) {

                throw new Error(
                    result.message ||
                    "Unable to load plants"
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Get Nursery Phone
            |--------------------------------------------------------------------------
            */

            nurseryPhone =
                String(result.phone || "")
                    .replace(/\D/g, "");


            /*
            |--------------------------------------------------------------------------
            | Render Plants
            |--------------------------------------------------------------------------
            */

            renderPlants(
                result.products || []
            );


            /*
            |--------------------------------------------------------------------------
            | Pagination
            |--------------------------------------------------------------------------
            */

            renderPagination(
                result.page,
                result.total_pages
            );


            /*
            |--------------------------------------------------------------------------
            | Result Info
            |--------------------------------------------------------------------------
            */

            if (result.total > 0) {

                const start =
                    ((result.page - 1) *
                        result.limit) + 1;


                const end =
                    Math.min(
                        result.page *
                        result.limit,
                        result.total
                    );


                resultInfo.textContent =
                    `Showing ${start}-${end} of ${result.total} plants`;

            } else {

                resultInfo.textContent =
                    "No plants found";
            }


        } catch (error) {

            console.error(
                "Plants error:",
                error
            );


            container.innerHTML = `
                <div class="col-12">

                    <div class="alert alert-danger">

                        Unable to load plants.
                        Please try again.

                    </div>

                </div>
            `;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Render Plants
    |--------------------------------------------------------------------------
    */

    function renderPlants(products) {

        container.innerHTML = "";


        if (!products.length) {

            emptyState.style.display =
                "block";

            return;
        }


        emptyState.style.display =
            "none";


        products.forEach(product => {

            const col =
                document.createElement("div");


            col.className =
                "col-12 col-sm-6 col-lg-4 col-xl-3";


            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            const image =
                product.image ||
                "assets/images/plants.jpg";


            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            const description =
                product.description ||
                "Healthy plant from Greenleaf Nursery.";


            /*
            |--------------------------------------------------------------------------
            | Price
            |--------------------------------------------------------------------------
            */

            let priceHTML = "";


            if (
                product.sale_price !== null &&
                Number(product.sale_price) <
                Number(product.price)
            ) {

                priceHTML = `
                    <span class="sale-price">
                        ₹${formatPrice(product.sale_price)}
                    </span>

                    <span class="old-price">
                        ₹${formatPrice(product.price)}
                    </span>
                `;

            } else {

                priceHTML = `
                    <span class="sale-price">
                        ₹${formatPrice(product.price)}
                    </span>
                `;
            }


            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            let stockHTML = "";


            if (Number(product.stock) > 0) {

                stockHTML = `
                    <span class="plant-stock">
                        ${product.stock} available
                    </span>
                `;

            } else {

                stockHTML = `
                    <span class="plant-stock plant-out-stock">
                        Out of stock
                    </span>
                `;
            }


            /*
            |--------------------------------------------------------------------------
            | WhatsApp Order
            |--------------------------------------------------------------------------
            */

            let whatsappHTML = "";


            if (
                Number(product.stock) > 0 &&
                nurseryPhone
            ) {

                const message =
                    `Hello, I want to order ${product.name}`;


                const whatsappUrl =
                    `https://wa.me/${nurseryPhone}?text=` +
                    encodeURIComponent(message);


                whatsappHTML = `
                    <a
                        href="${whatsappUrl}"
                        class="whatsapp-btn"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Order ${escapeHTML(product.name)} on WhatsApp"
                        title="Order on WhatsApp">

                        <i class="bi bi-whatsapp"></i>

                       

                    </a>
                `;

            } else if (Number(product.stock) > 0) {

                /*
                |--------------------------------------------------------------------------
                | Phone number is not configured
                |--------------------------------------------------------------------------
                */

                whatsappHTML = `
                    <span class="plant-contact-unavailable">
                        Contact Nursery
                    </span>
                `;
            }


            /*
            |--------------------------------------------------------------------------
            | Plant Card
            |--------------------------------------------------------------------------
            */

            col.innerHTML = `

                <article class="plant-card">

                    <div class="plant-image-wrapper">

                        ${product.featured
                    ? `
                                    <span class="plant-badge">
                                        Featured
                                    </span>
                                `
                    : ""
                }


                        <img
                            src="${escapeHTML(image)}"
                            alt="${escapeHTML(product.name)}"
                            loading="lazy"
                            width="600"
                            height="450"
                            onerror="this.onerror=null; this.src='${BASE_URL}assets/images/plants.jpg';"
                        >

                    </div>


                    <div class="plant-card-body">

                        <div class="plant-category">
                            ${escapeHTML(product.category || "Plant")}
                        </div>


                        <h2 class="plant-card-title">
                            ${escapeHTML(product.name)}
                        </h2>


                        <p class="plant-description">
                            ${escapeHTML(description)}
                        </p>


                        <div class="plant-price">
                            ${priceHTML}
                        </div>


                       <div class="plant-card-footer">

                            <div class="plant-stock-wrapper">
                              ${stockHTML}
                             </div>

                             <div class="plant-whatsapp-wrapper">
                                ${whatsappHTML}
                             </div>

                        </div>

                    </div>

                </article>

            `;


            container.appendChild(col);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    function renderPagination(
        page,
        totalPages
    ) {

        pagination.innerHTML = "";


        if (totalPages <= 1) {
            return;
        }


        const nav =
            document.createElement("ul");


        nav.className =
            "pagination";


        /*
        |--------------------------------------------------------------------------
        | Previous
        |--------------------------------------------------------------------------
        */

        const previous =
            document.createElement("li");


        previous.className =
            `page-item ${page <= 1
                ? "disabled"
                : ""
            }`;


        previous.innerHTML = `
            <button
                class="page-link"
                aria-label="Previous page"
                ${page <= 1
                ? "disabled"
                : ""
            }
            >
                <i class="bi bi-chevron-left"></i>
            </button>
        `;


        if (page > 1) {

            previous
                .querySelector("button")
                .addEventListener(
                    "click",
                    () => {

                        loadPlants(page - 1);

                        scrollToPlants();

                    }
                );
        }


        nav.appendChild(previous);


        /*
        |--------------------------------------------------------------------------
        | Page Numbers
        |--------------------------------------------------------------------------
        */

        for (
            let i = 1;
            i <= totalPages;
            i++
        ) {

            const item =
                document.createElement("li");


            item.className =
                `page-item ${i === page
                    ? "active"
                    : ""
                }`;


            item.innerHTML = `
                <button
                    class="page-link"
                    aria-label="Page ${i}"
                    ${i === page
                    ? 'aria-current="page"'
                    : ""
                }
                >
                    ${i}
                </button>
            `;


            item
                .querySelector("button")
                .addEventListener(
                    "click",
                    () => {

                        loadPlants(i);

                        scrollToPlants();

                    }
                );


            nav.appendChild(item);

        }


        /*
        |--------------------------------------------------------------------------
        | Next
        |--------------------------------------------------------------------------
        */

        const next =
            document.createElement("li");


        next.className =
            `page-item ${page >= totalPages
                ? "disabled"
                : ""
            }`;


        next.innerHTML = `
            <button
                class="page-link"
                aria-label="Next page"
                ${page >= totalPages
                ? "disabled"
                : ""
            }
            >
                <i class="bi bi-chevron-right"></i>
            </button>
        `;


        if (page < totalPages) {

            next
                .querySelector("button")
                .addEventListener(
                    "click",
                    () => {

                        loadPlants(page + 1);

                        scrollToPlants();

                    }
                );
        }


        nav.appendChild(next);


        pagination.appendChild(nav);

    }


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if (searchInput) {

        searchInput.addEventListener(
            "input",
            () => {

                clearTimeout(
                    searchTimer
                );


                searchTimer =
                    setTimeout(
                        () => {

                            loadPlants(1);

                        },
                        400
                    );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

    if (categorySelect) {

        categorySelect.addEventListener(
            "change",
            () => {

                loadPlants(1);

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Scroll
    |--------------------------------------------------------------------------
    */

    function scrollToPlants() {

        const section =
            document.querySelector(
                ".plants-list-section"
            );


        if (section) {

            section.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Price Formatting
    |--------------------------------------------------------------------------
    */

    function formatPrice(price) {

        return Number(price)
            .toLocaleString("en-IN", {
                maximumFractionDigits: 2
            });

    }


    /*
    |--------------------------------------------------------------------------
    | HTML Escape
    |--------------------------------------------------------------------------
    */

    function escapeHTML(value) {

        const div =
            document.createElement("div");


        div.textContent =
            value ?? "";


        return div.innerHTML;

    }


    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */

    loadPlants(1);

});