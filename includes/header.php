<?php
require_once "confige.php";
$siteUrl = $siteUrl ?? BASE_URL;

$seo = $seo ?? [
    "title" => "Greenleaf Nursery | Quality Plants for Your Home & Garden",
    "description" => "Explore beautiful indoor, outdoor, flowering and fruit plants from Greenleaf Nursery.",
    "image" => "assets/images/categories/plants.jpg"
];

$categoryUrl = $categoryUrl ?? $siteUrl;

$ogImage = $ogImage ?? (
    $siteUrl . "/" . $seo["image"]
);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars(
        $seo["title"] ?? "Greenleaf Nursery",
        ENT_QUOTES,
        "UTF-8"
    ); ?>
    </title>


    <meta name="description" content="<?= htmlspecialchars(
        $seo["description"] ?? "",
        ENT_QUOTES,
        "UTF-8"
    ); ?>">


    <!-- Canonical -->

    <link rel="canonical" href="<?= htmlspecialchars(
        $categoryUrl ?? $siteUrl,
        ENT_QUOTES,
        "UTF-8"
    ); ?>">


    <!-- =========================================================
     OPEN GRAPH
========================================================= -->

    <meta property="og:type" content="website">

    <meta property="og:title" content="<?= htmlspecialchars(
        $seo["title"] ?? "",
        ENT_QUOTES,
        "UTF-8"
    ); ?>">

    <meta property="og:description" content="<?= htmlspecialchars(
        $seo["description"] ?? "",
        ENT_QUOTES,
        "UTF-8"
    ); ?>">

    <meta property="og:url" content="<?= htmlspecialchars(
        $categoryUrl ?? $siteUrl,
        ENT_QUOTES,
        "UTF-8"
    ); ?>">

    <meta property="og:image" content="<?= htmlspecialchars(
        $ogImage ?? "",
        ENT_QUOTES,
        "UTF-8"
    ); ?>">

    <meta property="og:image:alt" content="<?= htmlspecialchars(
        $seo["heading"] ?? "Greenleaf Nursery",
        ENT_QUOTES,
        "UTF-8"
    ); ?>">

    <meta property="og:site_name" content="Greenleaf Nursery">


    <!-- =========================================================
     TWITTER / X
========================================================= -->

    <meta name="twitter:card" content="summary_large_image">

    <meta name="twitter:title" content="<?= htmlspecialchars(
        $seo["title"] ?? "",
        ENT_QUOTES,
        "UTF-8"
    ); ?>">

    <meta name="twitter:description" content="<?= htmlspecialchars(
        $seo["description"] ?? "",
        ENT_QUOTES,
        "UTF-8"
    ); ?>">

    <meta name="twitter:image" content="<?= htmlspecialchars(
        $ogImage ?? "",
        ENT_QUOTES,
        "UTF-8"
    ); ?>">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/contact.css">

</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">

            <a class="navbar-brand d-flex align-items-center" href="index.html">
                <span class="brand-icon">
                    <i class="bi bi-flower1"></i>
                </span>

                <span class="ms-2">
                    <strong>GreenLeaf</strong>
                    <small>Nursery</small>
                </span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">

                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#categories">Categories</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#plants">Plants</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact">Contact Us</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a href="#plants" class="btn btn-primary-custom">
                            Explore Plants
                        </a>
                    </li>

                </ul>

            </div>
        </div>
    </nav>