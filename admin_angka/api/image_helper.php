<?php

/* =========================================
   COMPRESS IMAGE TO WEBP
   TARGET: UNDER 20 KB
========================================= */

function compressImageToWebP(
    $tmpFile,
    $originalName,
    $uploadDir,
    $maxSizeKB = 20
) {

    /* -----------------------------------------
       Check GD
    ----------------------------------------- */

    if (!function_exists("imagecreatefromjpeg")) {

        throw new Exception(
            "PHP GD extension is not enabled."
        );

    }


    /* -----------------------------------------
       Check upload
    ----------------------------------------- */

    if (!file_exists($tmpFile)) {

        throw new Exception(
            "Uploaded image not found."
        );

    }


    /* -----------------------------------------
       Get image information
    ----------------------------------------- */

    $imageInfo = getimagesize($tmpFile);


    if (!$imageInfo) {

        throw new Exception(
            "Invalid image file."
        );

    }


    $width  = $imageInfo[0];
    $height = $imageInfo[1];
    $type   = $imageInfo[2];


    /* -----------------------------------------
       Create source image
    ----------------------------------------- */

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


    /* -----------------------------------------
       Maximum dimensions
    ----------------------------------------- */

    $maxWidth  = 1200;
    $maxHeight = 1200;


    $scale = min(
        $maxWidth / $width,
        $maxHeight / $height,
        1
    );


    $newWidth =
        max(1, (int)($width * $scale));


    $newHeight =
        max(1, (int)($height * $scale));


    /* -----------------------------------------
       Create resized image
    ----------------------------------------- */

    $canvas = imagecreatetruecolor(
        $newWidth,
        $newHeight
    );


    /* -----------------------------------------
       White background
       Helps PNG transparency become
       a normal WebP image.
    ----------------------------------------- */

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


    /* -----------------------------------------
       Resize
    ----------------------------------------- */

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


    /* -----------------------------------------
       Create upload directory
    ----------------------------------------- */

    if (!is_dir($uploadDir)) {

        if (!mkdir(
            $uploadDir,
            0755,
            true
        )) {

            throw new Exception(
                "Could not create upload directory."
            );

        }

    }


    /* -----------------------------------------
       Generate unique filename
    ----------------------------------------- */

    $filename =
        uniqid("plant_", true) .
        ".webp";


    $outputPath =
        rtrim($uploadDir, "/\\") .
        DIRECTORY_SEPARATOR .
        $filename;


    /* -----------------------------------------
       Try different WebP qualities
       until image reaches target.
    ----------------------------------------- */

    $qualities = [
        80,
        70,
        60,
        50,
        40,
        30,
        20,
        10
    ];


    $success = false;


    foreach ($qualities as $quality) {

        imagewebp(
            $canvas,
            $outputPath,
            $quality
        );


        if (file_exists($outputPath)) {

            $size =
                filesize($outputPath);


            if (
                $size <=
                ($maxSizeKB * 1024)
            ) {

                $success = true;

                break;

            }

        }

    }


    /* -----------------------------------------
       If still larger than 20 KB,
       reduce dimensions.
    ----------------------------------------- */

    if (!$success) {

        $smallWidth =
            (int)($newWidth * 0.75);


        $smallHeight =
            (int)($newHeight * 0.75);


        while (
            filesize($outputPath) >
            ($maxSizeKB * 1024) &&
            $smallWidth >= 300
        ) {

            $smallCanvas =
                imagecreatetruecolor(
                    $smallWidth,
                    $smallHeight
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
                $canvas,
                0,
                0,
                0,
                0,
                $smallWidth,
                $smallHeight,
                $newWidth,
                $newHeight
            );


            imagewebp(
                $smallCanvas,
                $outputPath,
                30
            );


            imagedestroy(
                $smallCanvas
            );


            $smallWidth =
                (int)($smallWidth * 0.75);


            $smallHeight =
                (int)($smallHeight * 0.75);

        }

    }


    /* -----------------------------------------
       Cleanup
    ----------------------------------------- */

    imagedestroy($source);

    imagedestroy($canvas);


    /* -----------------------------------------
       Final check
    ----------------------------------------- */

    if (
        !file_exists($outputPath)
    ) {

        throw new Exception(
            "Could not create WebP image."
        );

    }


    return [
        "filename" => $filename,
        "path" => $outputPath,
        "size" => filesize($outputPath)
    ];

}?>