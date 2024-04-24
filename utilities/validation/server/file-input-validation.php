<?php

    require_once('utilities/database/db-controller.php');
    require_once('entities/file/file-model.php');

    function validateProfileImage(FileImage $image){

        $errors = array();
        
        // Check the file size (2MB limit)
        $maxFileSize = 2 * 1024 * 1024; // 2MB in bytes
        if ($image->size > $maxFileSize) {

            $errors[] = "Image size exceeds 2MB.";

        }

        // Check if the file is an image
        $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
        if (!in_array($image->type, $allowedTypes)) {

            $errors[] = "Only JPEG, PNG, and GIF images are allowed.";

        }

        // Check if there was an upload error
        if ($image->error !== UPLOAD_ERR_OK) {

            $errors[] = "Error uploading the image.";

        }

        return $errors;

    }

    function validateProfileBanner(FileImage $image){

        $errors = array();
        
        // Check the file size (5MB limit)
        $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
        if ($image->size > $maxFileSize) {

            $errors[] = "Image size exceeds 5MB.";

        }

        // Check if the file is an image
        $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
        if (!in_array($image->type, $allowedTypes)) {

            $errors[] = "Only JPEG, PNG, and GIF images are allowed.";

        }

        // Check if there was an upload error
        if ($image->error !== UPLOAD_ERR_OK) {

            $errors[] = "Error uploading the image.";

        }

        return $errors;

    }

?>