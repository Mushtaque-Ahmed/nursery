<?php

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../includes/confige.php";
require_once __DIR__ . "/../admin_angka/api/db.php";


/*
|--------------------------------------------------------------------------
| Only GET requests
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "GET") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Only GET requests are allowed"
    ]);

    exit;
}


try {

/*
|--------------------------------------------------------------------------
| Get Nursery Phone
|--------------------------------------------------------------------------
*/

$settingsSql = "
    SELECT phone
    FROM nursery_settings
    ORDER BY id ASC
    LIMIT 1
";

$settingsStmt = $pdo->prepare($settingsSql);
$settingsStmt->execute();

$settings = $settingsStmt->fetch(PDO::FETCH_ASSOC);

$phone = !empty($settings["phone"])
    ? $settings["phone"]
    : "";

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    $page = isset($_GET["page"])
        ? (int) $_GET["page"]
        : 1;

    if ($page < 1) {
        $page = 1;
    }


    $limit = 12;

    $offset =
        ($page - 1) * $limit;


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    $search =
        trim($_GET["search"] ?? "");


    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

    $category =
        trim($_GET["category"] ?? "");


    /*
    |--------------------------------------------------------------------------
    | WHERE
    |--------------------------------------------------------------------------
    */

    $where = [
        "available = 1"
    ];

    $params = [];


    /*
    |--------------------------------------------------------------------------
    | Search Filter
    |--------------------------------------------------------------------------
    */

    if ($search !== "") {

        $where[] = "
            (
                name LIKE :search_name
                OR category LIKE :search_category
                OR description LIKE :search_description
            )
        ";

        $searchValue =
            "%" . $search . "%";


        $params[":search_name"] =
            $searchValue;

        $params[":search_category"] =
            $searchValue;

        $params[":search_description"] =
            $searchValue;
    }


    /*
    |--------------------------------------------------------------------------
    | Category Filter
    |--------------------------------------------------------------------------
    */

    if ($category !== "") {

        $where[] =
            "category = :category";

        $params[":category"] =
            $category;
    }


    /*
    |--------------------------------------------------------------------------
    | WHERE SQL
    |--------------------------------------------------------------------------
    */

    $whereSql =
        implode(" AND ", $where);


    /*
    |--------------------------------------------------------------------------
    | Count
    |--------------------------------------------------------------------------
    */

    $countSql = "
        SELECT COUNT(*)
        FROM plants
        WHERE $whereSql
    ";


    $countStmt =
        $pdo->prepare($countSql);


    foreach ($params as $key => $value) {

        $countStmt->bindValue(
            $key,
            $value,
            PDO::PARAM_STR
        );
    }


    $countStmt->execute();


    $totalProducts =
        (int) $countStmt->fetchColumn();


    /*
    |--------------------------------------------------------------------------
    | Total Pages
    |--------------------------------------------------------------------------
    */

    $totalPages =
        max(
            1,
            (int) ceil(
                $totalProducts / $limit
            )
        );


    /*
    |--------------------------------------------------------------------------
    | Correct Invalid Page
    |--------------------------------------------------------------------------
    */

    if ($page > $totalPages) {

        $page =
            $totalPages;

        $offset =
            ($page - 1) * $limit;
    }


    /*
    |--------------------------------------------------------------------------
    | Get Products
    |--------------------------------------------------------------------------
    */

    $sql = "
        SELECT
            id,
            name,
            category,
            price,
            sale_price,
            stock,
            description,
            image,
            available,
            featured
        FROM plants
        WHERE $whereSql
        ORDER BY featured DESC, id DESC
        LIMIT :limit OFFSET :offset
    ";


    $stmt =
        $pdo->prepare($sql);


    /*
    |--------------------------------------------------------------------------
    | Bind Filters
    |--------------------------------------------------------------------------
    */

    foreach ($params as $key => $value) {

        $stmt->bindValue(
            $key,
            $value,
            PDO::PARAM_STR
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Bind Pagination
    |--------------------------------------------------------------------------
    */

    $stmt->bindValue(
        ":limit",
        $limit,
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ":offset",
        $offset,
        PDO::PARAM_INT
    );


    $stmt->execute();


    $products =
        $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );


    /*
    |--------------------------------------------------------------------------
    | Convert Values
    |--------------------------------------------------------------------------
    */

    foreach ($products as &$product) {

        $product["id"] =
            (int) $product["id"];


        $product["price"] =
            (float) $product["price"];


        $product["sale_price"] =
            $product["sale_price"] !== null
                ? (float) $product["sale_price"]
                : null;


        $product["stock"] =
            (int) $product["stock"];


        $product["available"] =
            (bool) $product["available"];


        $product["featured"] =
            (bool) $product["featured"];


        /*
        |--------------------------------------------------------------------------
        | Image URL
        |--------------------------------------------------------------------------
        */

        if (!empty($product["image"])) {

            $product["image"] =
                BASE_URL .
                "admin_angka/" .
                $product["image"];
        }

    }

    unset($product);


    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

    echo json_encode([

        "success" => true,

        "count" =>
            count($products),

        "total" =>
            $totalProducts,

        "page" =>
            $page,

        "limit" =>
            $limit,

        "total_pages" =>
            $totalPages,

        "search" =>
            $search,

        "category" =>
            $category,
            "phone" => $phone,

        "products" =>
            $products

    ], JSON_UNESCAPED_SLASHES);


} catch (PDOException $e) {

    error_log(
        "Get plants error: " .
        $e->getMessage()
    );


    http_response_code(500);

    echo json_encode([

        "success" => false,

        "message" =>
            "Could not load plants",

        /*
        |--------------------------------------------------------------------------
        | TEMPORARY DEBUG
        |--------------------------------------------------------------------------
        | Remove this after testing.
        |--------------------------------------------------------------------------
        */

        "error" =>
            $e->getMessage()

    ]);

}?>