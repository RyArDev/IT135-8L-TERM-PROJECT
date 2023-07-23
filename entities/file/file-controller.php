<?php

    require_once('entities/file/file-model.php');
    require_once('entities/user/user-model.php');
    require_once('utilities/error/controller-error-handler.php');
    require_once('utilities/settings/stage-settings.php');

    function uploadProfileImage(UserProfileEdit $userProfile, FileImage $image){

        try{

            $fileNameParts = explode('.', $image->name);
            $fileExt = strtolower(end($fileNameParts));
            $config = json_decode(file_get_contents(getConfigFile()), true);
            
            // Move the uploaded file to its final destination
            $uploadDirectory = $config['FILE_DIRECTORY']['Images']['User'] . $userProfile->userId . "/";
            $newFileName = uniqid("profile_", true) . "." . $fileExt;
            $imagePath = $uploadDirectory . $newFileName;

            if (!file_exists($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }

            $pattern = $uploadDirectory . 'profile_*';
            $existingFiles = glob($pattern);
            $profileExists = false;
            $profileFilePath = '';

            foreach ($existingFiles as $file) {
                // Get the filename without the path
                $filename = basename($file);
                
                // Check if the filename starts with "profile_"
                if (strpos($filename, 'profile_') === 0) {
                    $profileExists = true;
                    $profileFilePath = $file;
                    break;
                }
            }

            if ($profileExists) {

                unlink($profileFilePath);

            }

            move_uploaded_file($image->tempPath, $imagePath);
            $userProfile->profileImageName = $newFileName;
            $userProfile->profileImageUrl = $imagePath;

        } catch (Exception $e) {
            
            echo "Moving Uploaded User Profile Image Failed: " . $e->getMessage();
            return;

        }

    }

    function uploadProfileBanner(UserProfileEdit $userProfile, FileImage $image){

        try{

            $fileNameParts = explode('.', $image->name);
            $fileExt = strtolower(end($fileNameParts));
            $config = json_decode(file_get_contents(getConfigFile()), true);
            
            // Move the uploaded file to its final destination
            $uploadDirectory = $config['FILE_DIRECTORY']['Images']['User'] . $userProfile->userId . "/";
            $newFileName = uniqid("banner_", true) . "." . $fileExt;
            $imagePath = $uploadDirectory . $newFileName;

            if (!file_exists($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }

            $pattern = $uploadDirectory . 'banner_*';
            $existingFiles = glob($pattern);
            $profileExists = false;
            $profileFilePath = '';

            foreach ($existingFiles as $file) {
                // Get the filename without the path
                $filename = basename($file);
                
                // Check if the filename starts with "profile_"
                if (strpos($filename, 'banner_') === 0) {
                    $profileExists = true;
                    $profileFilePath = $file;
                    break;
                }
            }

            if ($profileExists) {

                unlink($profileFilePath);

            }

            move_uploaded_file($image->tempPath, $imagePath);
            $userProfile->profileBannerName = $newFileName;
            $userProfile->profileBannerUrl = $imagePath;

        } catch (Exception $e) {
            
            echo "Moving Uploaded User Profile Banner Failed: " . $e->getMessage();
            return;

        }

    }

?>