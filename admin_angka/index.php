<?php

require_once  "includes/auth.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GreenLeaf Nursery - Admin</title>
    <link rel="manifest" href="manifest.json">

    <meta name="theme-color" content="#2f6f3e">

    <meta name="mobile-web-app-capable" content="yes">

    <meta name="apple-mobile-web-app-capable" content="yes">

    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <meta name="apple-mobile-web-app-title" content="GreenLeaf Admin">

    <link rel="apple-touch-icon" href="assets/images/icon-192.png">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <!-- Mobile Header -->
    <header class="mobile-header">
        <div>
            <span class="logo-icon">🌿</span>
            <span class="logo-text">GreenLeaf</span>
        </div>

        <button class="notification-btn">🔔</button>
    </header>


    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="brand">
            <span class="brand-icon">🌿</span>

            <div>
                <h2>GreenLeaf</h2>
                <span> <?= htmlspecialchars($_SESSION["admin_name"]) ?></span>
            </div>
        </div>

        <nav class="sidebar-nav">

            <button class="nav-item active" data-page="dashboard">
                <span>📊</span>
                Dashboard
            </button>

            <button class="nav-item" data-page="products">
                <span>🌱</span>
                Products
            </button>

            <button class="nav-item" data-page="add-product">
                <span>➕</span>
                Add Plant
            </button>

            <button class="nav-item" data-page="orders">
                <span>🛍️</span>
                Orders
            </button>

            <button class="nav-item" data-page="settings">
                <span>⚙️</span>
                Settings
            </button>


        </nav>

        <div class="sidebar-bottom">
            <button class="logout-btn logoutButton" type="button">
                🚪 Logout
            </button>
        </div>

    </aside>


    <!-- Main Content -->
    <main class="main-content">

        <!-- Dashboard -->
        <section class="page active" id="dashboard">

            <div class="page-header">
                <div>
                    <h1>Dashboard</h1>
                    <p>Welcome back! Manage your nursery plants.</p>
                </div>

                <button class="primary-btn" id="dashboardAddBtn">
                    + Add Plant
                </button>
            </div>


            <!-- Statistics -->
            <div class="stats-grid">

                <div class="stat-card">
                    <div class="stat-icon">🌱</div>

                    <div>
                        <span>Total Plants</span>
                        <strong id="totalPlants">24</strong>
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-icon">✅</div>

                    <div>
                        <span>In Stock</span>
                        <strong>20</strong>
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-icon">⚠️</div>

                    <div>
                        <span>Low Stock</span>
                        <strong>4</strong>
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-icon">⭐</div>

                    <div>
                        <span>Featured</span>
                        <strong>8</strong>
                    </div>
                </div>

            </div>


            <!-- Recent Products -->
            <div class="section-card">

                <div class="section-header">
                    <div>
                        <h2>Recent Plants</h2>
                        <p>Your latest nursery products</p>
                    </div>

                    <button class="text-btn" data-page-link="products">
                        View All
                    </button>
                </div>


                <div class="product-list" id="recentProducts">

                    <!-- Javascript will load products here -->

                </div>

            </div>

        </section>


        <!-- Products -->
        <section class="page" id="products">

            <div class="page-header">

                <div>
                    <h1>Plants</h1>
                    <p>Manage your nursery products.</p>
                </div>

                <button class="primary-btn" id="productsAddBtn">
                    + Add Plant
                </button>

            </div>


            <div class="product-toolbar">

                <div class="search-box">
                    🔍
                    <input type="text" id="searchInput" placeholder="Search plants...">
                </div>

                <select id="categoryFilter">
                    <option value="all">All Categories</option>
                    <option value="garden">Garden Plants</option>
                    <option value="outdoor">Outdoor Plants</option>
                    <option value="flowering">Flowering Plants</option>
                    <option value="fruit">Fruit Plants</option>
                </select>

            </div>


            <div class="products-grid" id="productsGrid">

                <!-- Javascript will load products -->

            </div>

        </section>


        <!-- Add Product -->
        <section class="page" id="add-product">

            <div class="page-header">

                <div>
                    <h1>Add New Plant</h1>
                    <p>Add a new plant to your nursery.</p>
                </div>

            </div>


            <form class="plant-form" id="plantForm">

                <!-- Image Upload -->
                <div class="form-card">

                    <h2>Plant Photos</h2>

                    <p class="form-description">
                        Upload clear photos of your plant.
                    </p>


                    <label class="image-upload">

                        <input type="file" id="plantImage" accept="image/*" multiple>

                        <div class="upload-content">
                            <span>📷</span>

                            <strong>
                                Add Plant Photos
                            </strong>

                            <small>
                                Tap to choose photos
                            </small>
                        </div>

                    </label>


                    <div class="image-preview" id="imagePreview"></div>

                </div>


                <!-- Plant Information -->
                <div class="form-card">

                    <h2>Plant Information</h2>


                    <div class="form-group">

                        <label for="plantName">
                            Plant Name
                        </label>

                        <input type="text" id="plantName" placeholder="e.g. Monstera Deliciosa" required>

                    </div>


                    <div class="form-row">

                        <div class="form-group">

                            <label for="plantCategory">
                                Category
                            </label>

                            <select id="plantCategory" required>

                                <option value="">
                                    Select category
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


                        <div class="form-group">

                            <label for="plantStock">
                                Stock
                            </label>

                            <input type="number" id="plantStock" placeholder="0" min="0" required>

                        </div>

                    </div>


                    <div class="form-row">

                        <div class="form-group">

                            <label for="plantPrice">
                                Price (₹)
                            </label>

                            <input type="number" id="plantPrice" placeholder="450" min="0" required>

                        </div>


                        <div class="form-group">

                            <label for="salePrice">
                                Sale Price (₹)
                            </label>

                            <input type="number" id="salePrice" placeholder="399" min="0">

                        </div>

                    </div>


                    <div class="form-group">

                        <label for="plantDescription">
                            Description
                        </label>

                        <textarea id="plantDescription" rows="5"
                            placeholder="Write something about this plant..."></textarea>

                    </div>

                </div>


                <!-- Settings -->
                <div class="form-card">

                    <h2>Product Settings</h2>


                    <label class="toggle-row">

                        <div>
                            <strong>Available</strong>

                            <span>
                                Show this plant as available.
                            </span>
                        </div>

                        <input type="checkbox" id="available" checked>

                        <span class="toggle"></span>

                    </label>


                    <label class="toggle-row">

                        <div>
                            <strong>Featured Plant</strong>

                            <span>
                                Show this plant in featured products.
                            </span>
                        </div>

                        <input type="checkbox" id="featured">

                        <span class="toggle"></span>

                    </label>

                </div>


                <div class="form-actions">

                    <button type="button" class="secondary-btn" id="cancelBtn">
                        Cancel
                    </button>

                    <button type="submit" class="primary-btn">
                        Publish Plant
                    </button>

                </div>

            </form>

        </section>


        <!-- Orders -->
        <section class="page" id="orders">

            <div class="page-header">

                <div>
                    <h1>Orders</h1>
                    <p>Manage customer orders.</p>
                </div>

            </div>


            <div class="empty-state">

                <span>🛍️</span>

                <h2>No Orders Yet</h2>

                <p>
                    Customer orders will appear here.
                </p>

            </div>

        </section>


        <!-- Settings -->
        <section class="page" id="settings">

            <div class="page-header">

                <div>
                    <h1>Settings</h1>
                    <p>Manage your nursery settings.</p>
                </div>

            </div>


            <div class="form-card">

                <h2>Nursery Information</h2>

                <div class="form-group">
                    <label for="nursery_name">Nursery Name</label>

                    <input type="text" id="nursery_name" name="nursery_name" value="GreenLeaf Nursery" required>
                </div>


                <div class="form-group">
                    <label for="nursery_address">Nursery Address</label>

                    <textarea id="nursery_address" name="nursery_address" rows="3"
                        placeholder="Enter nursery address"></textarea>
                </div>


                <div class="form-group">
                    <label for="phone">Phone Number</label>

                    <input type="tel" id="phone" name="phone" placeholder="919876543210" maxlength="12"
                        inputmode="numeric">

                    <small>
                        Enter 91 followed by your 10-digit mobile number.
                    </small>
                </div>


                <div class="form-group">
                    <label for="facebook_url">Facebook Page URL</label>

                    <input type="url" id="facebook_url" name="facebook_url"
                        placeholder="https://www.facebook.com/yourpage">
                </div>


                <div class="form-group">
                    <label for="whatsapp_url">WhatsApp URL</label>

                    <input type="url" id="whatsapp_url" name="whatsapp_url" placeholder="https://wa.me/919876543210">
                </div>


                <button type="button" id="saveNurserySettings" class="primary-btn">
                    Save Settings
                </button>
                <!-- Account / Logout -->
                <div class="form-card logout-card">

                    <h2>Account</h2>

                    <p class="form-description">
                        Manage your admin account.
                    </p>

                    <button class="logout-mobile-page-btn logoutButton" type="button">
                        🚪 Logout
                    </button>

                </div>

            </div>


        </section>

    </main>


    <!-- Mobile Bottom Navigation -->
    <nav class="bottom-nav">

        <button class="bottom-item active" data-page="dashboard">
            <span>📊</span>
            <small>Home</small>
        </button>


        <button class="bottom-item" data-page="products">
            <span>🌱</span>
            <small>Plants</small>
        </button>


        <button class="add-nav-btn" data-page="add-product">
            <span>+</span>
        </button>


        <button class="bottom-item" data-page="orders">
            <span>🛍️</span>
            <small>Orders</small>
        </button>


        <button class="bottom-item" data-page="settings">
            <span>⚙️</span>
            <small>More</small>
        </button>

    </nav>


    <!-- Toast -->
    <div class="toast" id="toast">
        Plant added successfully!
    </div>


    <script src="assets/js/script.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/logout.js"></script>



</body>

</html>