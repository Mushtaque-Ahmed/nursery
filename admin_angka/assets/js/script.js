/* =========================================
   GREENLEAF NURSERY ADMIN
   PRODUCT MANAGEMENT
========================================= */

let products = [];


/* =========================================
   PAGE NAVIGATION
========================================= */

const pages =
    document.querySelectorAll(".page");

const navigationButtons =
    document.querySelectorAll("[data-page]");


function showPage(pageName) {

    pages.forEach(page => {

        page.classList.remove("active");

    });


    const selectedPage =
        document.getElementById(pageName);


    if (selectedPage) {

        selectedPage.classList.add("active");

    }


    navigationButtons.forEach(button => {

        button.classList.remove("active");


        if (
            button.dataset.page === pageName
        ) {

            button.classList.add("active");

        }

    });


    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });

}


/* =========================================
   NAVIGATION BUTTONS
========================================= */

navigationButtons.forEach(button => {

    button.addEventListener(
        "click",
        () => {

            showPage(
                button.dataset.page
            );

        }
    );

});


/* =========================================
   PAGE LINKS
========================================= */

document
    .querySelectorAll("[data-page-link]")
    .forEach(button => {

        button.addEventListener(
            "click",
            () => {

                showPage(
                    button.dataset.pageLink
                );

            }
        );

    });


/* =========================================
   ADD PRODUCT BUTTONS
========================================= */

const dashboardAddBtn =
    document.getElementById(
        "dashboardAddBtn"
    );


const productsAddBtn =
    document.getElementById(
        "productsAddBtn"
    );


if (dashboardAddBtn) {

    dashboardAddBtn.addEventListener(
        "click",
        () => {

            resetProductForm();

            showPage("add-product");

        }
    );

}


if (productsAddBtn) {

    productsAddBtn.addEventListener(
        "click",
        () => {

            resetProductForm();

            showPage("add-product");

        }
    );

}


/* =========================================
   CATEGORY NAME
========================================= */

function getCategoryName(category) {

    const categories = {

        indoor: "Indoor Plants",

        outdoor: "Outdoor Plants",

        flowering: "Flowering Plants",

        fruit: "Fruit Plants"

    };


    return (
        categories[category] ||
        category ||
        ""
    );

}


/* =========================================
   FORMAT PRICE
========================================= */

function formatPrice(price) {

    return `₹${Number(price).toLocaleString("en-IN")}`;

}


/* =========================================
   STOCK STATUS
========================================= */

function getStockStatus(stock) {

    stock = Number(stock);


    if (stock <= 0) {

        return {

            text: "Out of Stock",

            className: "stock-out"

        };

    }


    if (stock <= 5) {

        return {

            text: "Low Stock",

            className: "stock-low"

        };

    }


    return {

        text: "In Stock",

        className: "stock-good"

    };

}


/* =========================================
   RENDER PRODUCTS
========================================= */

function renderProducts() {

    const productsGrid =
        document.getElementById(
            "productsGrid"
        );


    const recentProducts =
        document.getElementById(
            "recentProducts"
        );


    if (!productsGrid) {

        return;

    }


    productsGrid.innerHTML = "";


    /* =====================================
       EMPTY STATE
    ===================================== */

    if (products.length === 0) {

        productsGrid.innerHTML = `

            <div class="empty-state">

                <span>🌱</span>

                <h2>No Plants Found</h2>

                <p>
                    Add your first nursery plant.
                </p>

            </div>

        `;

    }


    /* =====================================
       PRODUCT CARDS
    ===================================== */

    products.forEach(product => {

        productsGrid.appendChild(
            createProductCard(product)
        );

    });


    /* =====================================
       RECENT PRODUCTS
    ===================================== */

    if (recentProducts) {

        recentProducts.innerHTML = "";


        products
            .slice(-4)
            .reverse()
            .forEach(product => {

                recentProducts.appendChild(
                    createProductRow(product)
                );

            });

    }


    updateStatistics();

}


/* =========================================
   CREATE PRODUCT CARD
========================================= */

function createProductCard(product) {

    const card =
        document.createElement("div");


    card.className =
        "product-card";


    const stockStatus =
        getStockStatus(
            product.stock
        );


    let priceHTML =
        formatPrice(product.price);


    if (
        product.sale_price !== null &&
        product.sale_price !== "" &&
        Number(product.sale_price) > 0
    ) {

        priceHTML = `

            <span
                style="
                    text-decoration:line-through;
                    color:#9ca3af;
                    font-size:12px;
                    margin-right:5px;
                "
            >
                ${formatPrice(product.price)}
            </span>

            ${formatPrice(product.sale_price)}

        `;

    }


    card.innerHTML = `

        <img
            class="product-card-image"
            src="${escapeHTML(product.image || "")}"
            alt="${escapeHTML(product.name)}"
            onerror="
                this.src='https://via.placeholder.com/600x400?text=Plant'
            "
        >


        <div class="product-card-body">


            <div class="product-card-top">

                <div>

                    <h3>
                        ${escapeHTML(product.name)}
                    </h3>


                    <div class="product-category">

                        ${escapeHTML(
                            getCategoryName(
                                product.category
                            )
                        )}

                    </div>

                </div>


                <div>

                    ${
                        Number(product.featured) === 1
                        ? `
                            <span class="badge badge-featured">
                                Featured
                            </span>
                        `
                        : ""
                    }

                </div>

            </div>


            <div class="product-card-price">

                ${priceHTML}

            </div>


            <div class="product-stock">

                <span>

                    Stock:

                    <strong>
                        ${product.stock}
                    </strong>

                </span>


                <span
                    class="${stockStatus.className}"
                >
                    ${stockStatus.text}
                </span>

            </div>


            <div>

                ${
                    Number(product.available) === 1
                    ? `
                        <span class="badge badge-available">
                            Available
                        </span>
                    `
                    : `
                        <span class="badge badge-unavailable">
                            Unavailable
                        </span>
                    `
                }

            </div>


            <div class="product-actions">


                <button
                    type="button"
                    class="edit-btn"
                    onclick="editProduct(${product.id})"
                >
                    ✏️ Edit
                </button>


                <button
                    type="button"
                    class="delete-btn"
                    onclick="deleteProduct(${product.id})"
                >
                    🗑️ Delete
                </button>


            </div>


        </div>

    `;


    return card;

}


/* =========================================
   RECENT PRODUCT ROW
========================================= */

function createProductRow(product) {

    const row =
        document.createElement("div");


    row.className =
        "product-row";


    row.innerHTML = `

        <img
            class="product-row-image"
            src="${escapeHTML(product.image || "")}"
            alt="${escapeHTML(product.name)}"
            onerror="
                this.src='https://via.placeholder.com/100?text=Plant'
            "
        >


        <div class="product-row-info">

            <h3>
                ${escapeHTML(product.name)}
            </h3>


            <p>
                ${escapeHTML(
                    getCategoryName(
                        product.category
                    )
                )}
            </p>

        </div>


        <div class="product-price">

            ${
                product.sale_price
                ? formatPrice(
                    product.sale_price
                )
                : formatPrice(
                    product.price
                )
            }

        </div>

    `;


    return row;

}


/* =========================================
   UPDATE STATISTICS
========================================= */

function updateStatistics() {

    const totalPlants =
        document.getElementById(
            "totalPlants"
        );


    if (totalPlants) {

        totalPlants.textContent =
            products.length;

    }


    const inStock =
        products.filter(
            product =>
                Number(product.stock) > 0
        ).length;


    const lowStock =
        products.filter(
            product =>
                Number(product.stock) > 0 &&
                Number(product.stock) <= 5
        ).length;


    const featured =
        products.filter(
            product =>
                Number(product.featured) === 1
        ).length;


    const statCards =
        document.querySelectorAll(
            ".stat-card"
        );


    if (statCards.length >= 4) {

        statCards[1]
            .querySelector("strong")
            .textContent = inStock;


        statCards[2]
            .querySelector("strong")
            .textContent = lowStock;


        statCards[3]
            .querySelector("strong")
            .textContent = featured;

    }

}


/* =========================================
   IMAGE PREVIEW
========================================= */

const plantImage =
    document.getElementById(
        "plantImage"
    );


const imagePreview =
    document.getElementById(
        "imagePreview"
    );


if (plantImage) {

    plantImage.addEventListener(
        "change",
        function () {

            imagePreview.innerHTML = "";


            const file =
                this.files[0];


            if (!file) {

                return;

            }


            const reader =
                new FileReader();


            reader.onload =
                function (event) {

                    imagePreview.innerHTML = `

                        <div class="image-preview-item">

                            <img
                                src="${event.target.result}"
                                alt="Plant preview"
                            >

                        </div>

                    `;

                };


            reader.readAsDataURL(file);

        }
    );

}


/* =========================================
   PRODUCT FORM
========================================= */

const plantForm =
    document.getElementById(
        "plantForm"
    );


const cancelBtn =
    document.getElementById(
        "cancelBtn"
    );


/* =========================================
   FORM SUBMIT
========================================= */

if (plantForm) {

    plantForm.addEventListener(
        "submit",
        handleProductSubmit
    );

}


/* =========================================
   ADD / UPDATE PRODUCT
========================================= */

async function handleProductSubmit(event) {

    event.preventDefault();


    const editingId =
        plantForm.dataset.editingId || "";


    const name =
        document
            .getElementById("plantName")
            .value
            .trim();


    const category =
        document
            .getElementById("plantCategory")
            .value;


    const stock =
        document
            .getElementById("plantStock")
            .value;


    const price =
        document
            .getElementById("plantPrice")
            .value;


    const salePrice =
        document
            .getElementById("salePrice")
            .value;


    const description =
        document
            .getElementById("plantDescription")
            .value
            .trim();


    const available =
        document
            .getElementById("available")
            .checked;


    const featured =
        document
            .getElementById("featured")
            .checked;


    const imageInput =
        document.getElementById(
            "plantImage"
        );


    /* =====================================
       VALIDATION
    ===================================== */

    if (!name) {

        showToast(
            "Please enter plant name."
        );

        return;

    }


    if (!category) {

        showToast(
            "Please select a category."
        );

        return;

    }


    if (!price) {

        showToast(
            "Please enter price."
        );

        return;

    }


    /*
     * Image required only for ADD.
     */

    if (
        !editingId &&
        imageInput.files.length === 0
    ) {

        showToast(
            "Please select a plant image."
        );

        return;

    }


    /* =====================================
       FORMDATA
    ===================================== */

    const formData =
        new FormData();


    if (editingId) {

        formData.append(
            "id",
            editingId
        );

    }


    formData.append(
        "name",
        name
    );


    formData.append(
        "category",
        category
    );


    formData.append(
        "price",
        price
    );


    formData.append(
        "sale_price",
        salePrice
    );


    formData.append(
        "stock",
        stock
    );


    formData.append(
        "description",
        description
    );


    formData.append(
        "available",
        available ? "1" : "0"
    );


    formData.append(
        "featured",
        featured ? "1" : "0"
    );


    /*
     * Image is optional during EDIT.
     */

    if (
        imageInput.files.length > 0
    ) {

        formData.append(
            "image",
            imageInput.files[0]
        );

    }


    /* =====================================
       API URL
    ===================================== */

    const apiUrl =
        editingId
            ? "api/update_product.php"
            : "api/add_product.php";


    /* =====================================
       BUTTON
    ===================================== */

    const submitButton =
        plantForm.querySelector(
            'button[type="submit"]'
        );


    const originalText =
        submitButton
            ? submitButton.textContent
            : "";


    if (submitButton) {

        submitButton.disabled =
            true;


        submitButton.textContent =
            editingId
                ? "Updating..."
                : "Publishing...";

    }


    /* =====================================
       SEND TO PHP
    ===================================== */

    try {

        const response =
            await fetch(
                apiUrl,
                {
                    method: "POST",
                    body: formData
                }
            );


        const result =
            await response.json();


        if (
            !response.ok ||
            !result.success
        ) {

            throw new Error(
                result.message ||
                "Could not save product."
            );

        }


        /* =================================
           SUCCESS
        ================================= */

        showToast(
            editingId
                ? "🌱 Plant updated successfully!"
                : "🌱 Plant added successfully!"
        );


        /* =================================
           RESET
        ================================= */

        resetProductForm();


        /* =================================
           RELOAD DATABASE PRODUCTS
        ================================= */

        await loadProductsFromAPI();


        /* =================================
           PRODUCTS PAGE
        ================================= */

        showPage("products");


    } catch (error) {

        console.error(
            "Product API error:",
            error
        );


        showToast(
            error.message ||
            "Something went wrong."
        );


    } finally {

        if (submitButton) {

            submitButton.disabled =
                false;


            submitButton.textContent =
                originalText ||
                "Publish Plant";

        }

    }

}


/* =========================================
   EDIT PRODUCT
========================================= */

function editProduct(id) {

    const product =
        products.find(
            item =>
                Number(item.id) ===
                Number(id)
        );


    if (!product) {

        showToast(
            "Product not found."
        );

        return;

    }


    /* =====================================
       FILL FORM
    ===================================== */

    document.getElementById(
        "plantName"
    ).value =
        product.name || "";


    document.getElementById(
        "plantCategory"
    ).value =
        product.category || "";


    document.getElementById(
        "plantStock"
    ).value =
        product.stock ?? 0;


    document.getElementById(
        "plantPrice"
    ).value =
        product.price ?? "";


    document.getElementById(
        "salePrice"
    ).value =
        product.sale_price ?? "";


    document.getElementById(
        "plantDescription"
    ).value =
        product.description || "";


    document.getElementById(
        "available"
    ).checked =
        Number(product.available) === 1;


    document.getElementById(
        "featured"
    ).checked =
        Number(product.featured) === 1;


    /* =====================================
       EXISTING IMAGE
    ===================================== */

    imagePreview.innerHTML = "";


    if (product.image) {

        imagePreview.innerHTML = `

            <div class="image-preview-item">

                <img
                    src="${escapeHTML(product.image)}"
                    alt="${escapeHTML(product.name)}"
                >

            </div>

        `;

    }


    /* =====================================
       STORE EDITING ID
    ===================================== */

    plantForm.dataset.editingId =
        product.id;


    /* =====================================
       CHANGE BUTTON
    ===================================== */

    const submitButton =
        plantForm.querySelector(
            'button[type="submit"]'
        );


    if (submitButton) {

        submitButton.textContent =
            "Update Plant";

    }


    /* =====================================
       OPEN FORM
    ===================================== */

    showPage("add-product");

}


/* =========================================
   RESET PRODUCT FORM
========================================= */

function resetProductForm() {

    if (!plantForm) {

        return;

    }


    plantForm.reset();


    delete plantForm.dataset.editingId;


    if (imagePreview) {

        imagePreview.innerHTML = "";

    }


    if (plantImage) {

        plantImage.value = "";

    }


    const submitButton =
        plantForm.querySelector(
            'button[type="submit"]'
        );


    if (submitButton) {

        submitButton.textContent =
            "Publish Plant";

    }

}


/* =========================================
   CANCEL
========================================= */

if (cancelBtn) {

    cancelBtn.addEventListener(
        "click",
        () => {

            resetProductForm();

            showPage("products");

        }
    );

}


/* =========================================
   SEARCH
========================================= */

const searchInput =
    document.getElementById(
        "searchInput"
    );


const categoryFilter =
    document.getElementById(
        "categoryFilter"
    );


if (searchInput) {

    searchInput.addEventListener(
        "input",
        filterProducts
    );

}


if (categoryFilter) {

    categoryFilter.addEventListener(
        "change",
        filterProducts
    );

}


function filterProducts() {

    if (!searchInput || !categoryFilter) {

        return;

    }


    const search =
        searchInput.value
            .toLowerCase()
            .trim();


    const category =
        categoryFilter.value;


    const cards =
        document.querySelectorAll(
            ".product-card"
        );


    products.forEach(
        (product, index) => {

            const card =
                cards[index];


            if (!card) {

                return;

            }


            const matchesSearch =
                String(product.name)
                    .toLowerCase()
                    .includes(search);


            const matchesCategory =
                category === "all" ||
                product.category === category;


            card.style.display =
                matchesSearch &&
                matchesCategory
                    ? ""
                    : "none";

        }
    );

}


/* =========================================
   TOAST
========================================= */

let toastTimer;


function showToast(message) {

    const toast =
        document.getElementById(
            "toast"
        );


    if (!toast) {

        return;

    }


    toast.textContent =
        message;


    toast.classList.add(
        "show"
    );


    clearTimeout(
        toastTimer
    );


    toastTimer =
        setTimeout(
            () => {

                toast.classList.remove(
                    "show"
                );

            },
            2500
        );

}


/* =========================================
   HTML ESCAPE
========================================= */

function escapeHTML(value) {

    return String(value)

        .replaceAll(
            "&",
            "&amp;"
        )

        .replaceAll(
            "<",
            "&lt;"
        )

        .replaceAll(
            ">",
            "&gt;"
        )

        .replaceAll(
            '"',
            "&quot;"
        )

        .replaceAll(
            "'",
            "&#039;"
        );

}


/* =========================================
   LOAD PRODUCTS FROM MYSQL
========================================= */

async function loadProductsFromAPI() {

    try {

        const response =
            await fetch(
                "api/get_products.php"
            );


        const result =
            await response.json();


        if (
            !response.ok ||
            !result.success
        ) {

            throw new Error(
                result.message ||
                "Could not load products."
            );

        }


        products =
            result.products || [];


        renderProducts();


    } catch (error) {

        console.error(
            "Load products error:",
            error
        );


        showToast(
            "Could not load products."
        );

    }

}


/* =========================================
   DELETE PLACEHOLDER
========================================= */

/* =========================================
   DELETE PRODUCT
========================================= */

/* =========================================
   DELETE PRODUCT
========================================= */

async function deleteProduct(id) {

    const product = products.find(
        item => Number(item.id) === Number(id)
    );

    if (!product) {
        showToast("Product not found.");
        return;
    }

    const confirmed = confirm(
        `Are you sure you want to delete "${product.name}"?`
    );

    if (!confirmed) {
        return;
    }

    try {

        const formData = new FormData();

        formData.append("id", id);


        console.log("Deleting product ID:", id);


        const response = await fetch(
            "api/delete_product.php",
            {
                method: "POST",
                body: formData
            }
        );


        /* =========================================
           GET RAW RESPONSE FIRST
        ========================================= */

        const text = await response.text();

        console.log("Delete PHP response:", text);


        /* =========================================
           CONVERT JSON
        ========================================= */

        let result;

        try {

            result = JSON.parse(text);

        } catch (jsonError) {

            console.error(
                "PHP did not return valid JSON:",
                text
            );

            throw new Error(
                "PHP returned an invalid response."
            );

        }


        /* =========================================
           CHECK PHP RESPONSE
        ========================================= */

        if (!response.ok || !result.success) {

            throw new Error(
                result.message ||
                `Delete failed. HTTP ${response.status}`
            );

        }


        /* =========================================
           SUCCESS
        ========================================= */

        showToast(
            "🗑️ Product deleted successfully!"
        );


        /* Reload products from MySQL */

        await loadProductsFromAPI();


    } catch (error) {

        console.error(
            "Delete product error:",
            error
        );

        showToast(
            error.message ||
            "Could not delete product."
        );

    }

}


/* =========================================
   INITIALIZE
========================================= */

loadProductsFromAPI();

showPage("dashboard");