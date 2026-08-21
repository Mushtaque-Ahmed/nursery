<?php

/*
|--------------------------------------------------------------------------
| GreenLeaf Nursery - Dynamic Sitemap
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/includes/confige.php";
require_once __DIR__ . "/admin_angka/api/db.php";


/*
|--------------------------------------------------------------------------
| Sitemap Header
|--------------------------------------------------------------------------
*/

header("Content-Type: application/xml; charset=UTF-8");


/*
|--------------------------------------------------------------------------
| Website URL
|--------------------------------------------------------------------------
|
| BASE_URL should be:
|
| http://localhost/nursery/
|
| Production:
|
| https://yourdomain.com/nursery/
|
*/

$siteUrl = rtrim(BASE_URL, "/") . "/";


/*
|--------------------------------------------------------------------------
| Helper Function
|--------------------------------------------------------------------------
*/

function xmlEscape($value)
{
    return htmlspecialchars(
        $value,
        ENT_XML1 | ENT_QUOTES,
        "UTF-8"
    );
}


/*
|--------------------------------------------------------------------------
| Start XML
|--------------------------------------------------------------------------
*/

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "\n";

echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    echo "\n";


    /*
    |--------------------------------------------------------------------------
    | Static Pages
    |--------------------------------------------------------------------------
    */

    $staticPages = [

    [
    "url" => "",
    "priority" => "1.0",
    "changefreq" => "weekly"
    ],

    [
    "url" => "about",
    "priority" => "0.8",
    "changefreq" => "monthly"
    ],

    [
    "url" => "plants",
    "priority" => "0.9",
    "changefreq" => "weekly"
    ],

    [
    "url" => "contact",
    "priority" => "0.7",
    "changefreq" => "monthly"
    ]

    ];


    foreach ($staticPages as $page) {

    echo " <url>\n";

        echo " <loc>" .
            xmlEscape($siteUrl . $page["url"]) .
            "</loc>\n";

        echo " <changefreq>" .
            $page["changefreq"] .
            "</changefreq>\n";

        echo " <priority>" .
            $page["priority"] .
            "</priority>\n";

        echo " </url>\n";
    }


    /*
    |--------------------------------------------------------------------------
    | Dynamic Categories
    |--------------------------------------------------------------------------
    */

    try {

    $categorySql = "
    SELECT DISTINCT category
    FROM plants
    WHERE available = 1
    AND category IS NOT NULL
    AND category != ''
    ORDER BY category ASC
    ";

    $categoryStmt =
    $pdo->prepare($categorySql);

    $categoryStmt->execute();

    $categories =
    $categoryStmt->fetchAll(PDO::FETCH_COLUMN);


    foreach ($categories as $category) {

    $categorySlug =
    strtolower(
    trim($category)
    );

    /*
    |--------------------------------------------------------------------------
    | Convert category to URL-friendly slug
    |--------------------------------------------------------------------------
    */

    $categorySlug =
    preg_replace(
    "/[^a-z0-9_-]+/",
    "-",
    $categorySlug
    );

    $categorySlug =
    trim(
    $categorySlug,
    "-"
    );


    if ($categorySlug === "") {
    continue;
    }


    echo " <url>\n";

        echo " <loc>" .
            xmlEscape(
            $siteUrl .
            "category/" .
            $categorySlug
            ) .
            "</loc>\n";

        echo " <changefreq>weekly</changefreq>\n";

        echo " <priority>0.8</priority>\n";

        echo " </url>\n";
    }


    } catch (PDOException $e) {

    /*
    |--------------------------------------------------------------------------
    | Do not break sitemap if category query fails
    |--------------------------------------------------------------------------
    */

    }


    /*
    |--------------------------------------------------------------------------
    | Dynamic Plants
    |--------------------------------------------------------------------------
    |
    | If your plant detail URL is:
    |
    | /nursery/plant/plant-name
    |
    | this section generates those URLs.
    |
    */

    try {

    $plantSql = "
    SELECT
    id,
    name,
    updated_at,
    created_at
    FROM plants
    WHERE available = 1
    ORDER BY id DESC
    ";


    $plantStmt =
    $pdo->prepare($plantSql);

    $plantStmt->execute();


    $plants =
    $plantStmt->fetchAll(
    PDO::FETCH_ASSOC
    );


    foreach ($plants as $plant) {

    /*
    |--------------------------------------------------------------------------
    | Create Plant Slug
    |--------------------------------------------------------------------------
    */

    $slug =
    strtolower(
    trim(
    $plant["name"]
    )
    );


    $slug =
    preg_replace(
    "/[^a-z0-9]+/",
    "-",
    $slug
    );


    $slug =
    trim(
    $slug,
    "-"
    );


    if ($slug === "") {
    continue;
    }


    /*
    |--------------------------------------------------------------------------
    | Last Modified Date
    |--------------------------------------------------------------------------
    */

    $lastModified =
    !empty($plant["updated_at"])
    ? $plant["updated_at"]
    : ($plant["created_at"] ?? null);


    echo " <url>\n";


        echo " <loc>" .
            xmlEscape(
            $siteUrl .
            "plant/" .
            $slug
            ) .
            "</loc>\n";


        if ($lastModified) {

        $timestamp =
        strtotime(
        $lastModified
        );


        if ($timestamp !== false) {

        echo " <lastmod>" .
            date(
            "Y-m-d",
            $timestamp
            ) .
            "</lastmod>\n";
        }
        }


        echo " <changefreq>weekly</changefreq>\n";

        echo " <priority>0.7</priority>\n";

        echo " </url>\n";
    }


    } catch (PDOException $e) {

    /*
    |--------------------------------------------------------------------------
    | Do not break sitemap if plant query fails
    |--------------------------------------------------------------------------
    */

    }


    /*
    |--------------------------------------------------------------------------
    | Close XML
    |--------------------------------------------------------------------------
    */

    echo "</urlset>";

?>