<?php

function compressImageToWebP(
    $tmpFile,
    $originalName,
    $uploadDir,
    $maxSizeKB = 100
) {

    if (!function_exists("imagewebp")) {
        throw new Exception(
            "PHP GD WebP support is not enabled."
        );
    }


    if (!file_exists($tmpFile)) {
        throw new Exception(
            "Uploaded image not found."
        );
    }


    $imageInfo = getimagesize($tmpFile);

    if (!$imageInfo) {
        throw new Exception(
            "Invalid image file."
        );
    }


    $width  = $imageInfo[0];
    $height = $imageInfo[1];
    $type   = $imageInfo[2];


    /*
    |--------------------------------------------------------------------------
    | Create source image
    |--------------------------------------------------------------------------
    */

    switch ($type) {

        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($tmpFile);
            break;

        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($tmpFile);
            break;

        case IMAGETYPE_WEBP:
            $source = imagecreatefromwebp($tmpFile);
            break;

        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($tmpFile);
            break;

        default:
            throw new Exception(
                "Only JPG, PNG, GIF and WebP images are supported."
            );
    }


    if (!$source) {
        throw new Exception(
            "Could not read uploaded image."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Maximum dimensions
    |--------------------------------------------------------------------------
    */

    $maxWidth  = 1200;
    $maxHeight = 1200;


    $scale = min(
        $maxWidth / $width,
        $maxHeight / $height,
        1
    );


    $newWidth = max(
        1,
        (int) ($width * $scale)
    );


    $newHeight = max(
        1,
        (int) ($height * $scale)
    );


    /*
    |--------------------------------------------------------------------------
    | Create resized canvas
    |--------------------------------------------------------------------------
    */

    $canvas = imagecreatetruecolor(
        $newWidth,
        $newHeight
    );


    /*
    |--------------------------------------------------------------------------
    | White background
    |--------------------------------------------------------------------------
    */

    $white = imagecolorallocate(
        $canvas,
        255,
        255,
        255
    );

    imagefill(
        $canvas,
        0,
        0,
        $white
    );


    imagecopyresampled(
        $canvas,
        $source,
        0,
        0,
        0,
        0,
        $newWidth,
        $newHeight,
        $width,
        $height
    );


    /*
    |--------------------------------------------------------------------------
    | Create directory
    |--------------------------------------------------------------------------
    */

    if (!is_dir($uploadDir)) {

        if (!mkdir(
            $uploadDir,
            0755,
            true
        )) {

            imagedestroy($source);
            imagedestroy($canvas);

            throw new Exception(
                "Could not create upload directory."
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Generate filename
    |--------------------------------------------------------------------------
    */

    $filename = uniqid(
        "plant_",
        true
    ) . ".webp";


    $outputPath =
        rtrim($uploadDir, "/\\") .
        DIRECTORY_SEPARATOR .
        $filename;


    /*
    |--------------------------------------------------------------------------
    | Target size
    |--------------------------------------------------------------------------
    */

    $maxBytes =
        $maxSizeKB * 1024;


    /*
    |--------------------------------------------------------------------------
    | Compression settings
    |--------------------------------------------------------------------------
    */

    $qualities = [
        85,
        80,
        75,
        70,
        65,
        60,
        55,
        50,
        45,
        40,
        35,
        30
    ];


    $success = false;

    $currentImage = $canvas;

    $currentWidth = $newWidth;
    $currentHeight = $newHeight;


    /*
    |--------------------------------------------------------------------------
    | Try compression and resizing
    |--------------------------------------------------------------------------
    */

    for ($attempt = 0; $attempt < 6; $attempt++) {

        foreach ($qualities as $quality) {

            imagewebp(
                $currentImage,
                $outputPath,
                $quality
            );


            clearstatcache(
                true,
                $outputPath
            );


            if (
                file_exists($outputPath) &&
                filesize($outputPath) <= $maxBytes
            ) {

                $success = true;
                break 2;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Reduce dimensions by 20%
        |--------------------------------------------------------------------------
        */

        $nextWidth =
            max(
                300,
                (int) ($currentWidth * 0.8)
            );


        $nextHeight =
            max(
                300,
                (int) ($currentHeight * 0.8)
            );


        if (
            $nextWidth === $currentWidth ||
            $nextHeight === $currentHeight
        ) {
            break;
        }


        $smallCanvas =
            imagecreatetruecolor(
                $nextWidth,
                $nextHeight
            );


        $white = imagecolorallocate(
            $smallCanvas,
            255,
            255,
            255
        );


        imagefill(
            $smallCanvas,
            0,
            0,
            $white
        );


        imagecopyresampled(
            $smallCanvas,
            $currentImage,
            0,
            0,
            0,
            0,
            $nextWidth,
            $nextHeight,
            $currentWidth,
            $currentHeight
        );


        if ($currentImage !== $canvas) {
            imagedestroy($currentImage);
        }


        $currentImage = $smallCanvas;

        $currentWidth = $nextWidth;
        $currentHeight = $nextHeight;
    }


    /*
    |--------------------------------------------------------------------------
    | Cleanup
    |--------------------------------------------------------------------------
    */

    if ($currentImage !== $canvas) {
        imagedestroy($currentImage);
    }

    imagedestroy($source);
    imagedestroy($canvas);


    /*
    |--------------------------------------------------------------------------
    | Final validation
    |--------------------------------------------------------------------------
    */

    if (
        !$success ||
        !file_exists($outputPath)
    ) {

        if (file_exists($outputPath)) {
            unlink($outputPath);
        }

        throw new Exception(
            "Could not compress image below {$maxSizeKB} KB."
        );
    }


    clearstatcache(
        true,
        $outputPath
    );


    return [
        "filename" => $filename,
        "path" => $outputPath,
        "size" => filesize($outputPath)
    ];
}?>