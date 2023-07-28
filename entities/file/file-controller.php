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

            while (file_exists($uploadDirectory . $newFileName)) {

                $newFileName = uniqid("profile_", true) . "." . $fileExt;
            
            }
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

            while (file_exists($uploadDirectory . $newFileName)) {

                $newFileName = uniqid("banner_", true) . "." . $fileExt;
            
            }

            $imagePath = $uploadDirectory . $newFileName;

            if (!file_exists($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }

            $pattern = $uploadDirectory . 'banner_*';
            $existingFiles = glob($pattern);
            $bannerExists = false;
            $bannerFilePath = '';

            foreach ($existingFiles as $file) {
                // Get the filename without the path
                $filename = basename($file);
                
                // Check if the filename starts with "profile_"
                if (strpos($filename, 'banner_') === 0) {
                    $bannerExists = true;
                    $bannerFilePath = $file;
                    break;
                }
            }

            if ($bannerExists) {

                unlink($bannerFilePath);

            }

            move_uploaded_file($image->tempPath, $imagePath);
            $userProfile->profileBannerName = $newFileName;
            $userProfile->profileBannerUrl = $imagePath;

        } catch (Exception $e) {
            
            echo "Moving Uploaded User Profile Banner Failed: " . $e->getMessage();
            return;

        }

    }

    function moveCkFinderImages($id, $body, $type, &$previousImagePaths) {
        
        try {

            $config = json_decode(file_get_contents(getConfigFile()), true);
            $uploadDirectory = $config['FILE_DIRECTORY']['Images'][ucwords($type)] . $id . "/";

            // New variable to store initial image paths
            $directoyImagePaths = glob($uploadDirectory . 'ckfinder_*.*');
            $currentImagePaths = array();

            $body = html_entity_decode($body);
            $body = str_replace('<figure', '<div', $body);
            $body = str_replace('</figure>', '</div>', $body);
            
            preg_match_all('/<oembed\b[^>]*url=["\'](.*?)["\'][^>]*><\/oembed>/i', $body, $oembedMatches);
            if (!empty($oembedMatches[1])) {
                
                foreach ($oembedMatches[1] as $oembedUrl) {
                    
                    $oembedContent = getEmbedContent($oembedUrl, "Youtube");
                    
                    if ($oembedContent !== null) {
                        
                        $oembedContent = str_replace('<iframe', '<iframe class="youtube-embed"', $oembedContent);
                        $oembedTag = '<oembed url="' . $oembedUrl . '"></oembed>';
                        $body = str_replace($oembedTag, $oembedContent, $body);

                    }
                }
                
            }
            
            $body = preg_replace('/<oembed\b[^>]*>(.*?)<\/oembed>/i', '', $body);
            $body = '<!DOCTYPE html><html><body>' . $body . '</body></html>';

            // Create a new DOMDocument instance
            $dom = new DOMDocument();
    
            // Load the HTML content into the DOMDocument
            $dom->loadHTML($body, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            
            // Create a new DOMXPath instance
            $xpath = new DOMXPath($dom);
    
            // Find all image elements in the DOM using XPath
            $imageElements = $xpath->query('//img[@src]');

            // Loop through each image element and replace its "src" attribute
            foreach ($imageElements as $image) {

                $currentPath = $image->getAttribute('src');

                // Replace the source only if it matches the pattern "/assets/ckfinder/images/"
                if (strpos($currentPath, '/assets/ckfinder/images/') !== false) {

                    // Extract the filename from the new image source
                    $fileName = basename($currentPath);
                    $fileNameParts = explode('.', $fileName);
                    $fileExt = strtolower(end($fileNameParts));
    
                    if (!file_exists($uploadDirectory)) {

                        mkdir($uploadDirectory, 0777, true);
                        
                    }

                    // Calculate the hash of the image file content
                    $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($currentPath, '/');
                    $imageContent = file_get_contents($imagePath);
                    $imageHash = md5($imageContent);
                    
                    // Check if an image with the same content exists in the destination directory
                    $existingImagePath = null;

                    foreach ($directoyImagePaths as $path) {

                        $existingContent = file_get_contents($path);
                        $existingHash = md5($existingContent);
                        
                        if ($imageHash === $existingHash) {

                            $existingImagePath = $path;
                            break;

                        }

                    }

                    if ($existingImagePath) {

                        // If an existing image with the same content is found, use it instead
                        $newPath = $existingImagePath;

                    } else {

                        // Generate a unique filename
                        $newFileName = uniqid("ckfinder_", true) . "." . $fileExt;

                        while (file_exists($uploadDirectory . $newFileName)) {

                            $newFileName = uniqid("ckfinder_", true) . "." . $fileExt;
                        
                        }
                        
                        $newPath = $uploadDirectory . $newFileName;
    
                        $currentPath = ltrim($currentPath, '/');
                        rename($currentPath, $newPath);

                    }

                    $image->setAttribute('src', $newPath);
                    $currentImagePaths[] = $newPath;

                }else{

                    $currentImagePaths[] = $currentPath;

                }

            }

            $body = $dom->saveHTML();

            $body = str_replace('<div', '<figure', $body);
            $body = str_replace('</div>', '</figure>', $body);
            $bodyStart = strpos($body, '<body>');
            $bodyEnd = strrpos($body, '</body>');
            $body = substr($body, $bodyStart + 6, $bodyEnd - $bodyStart - 6);

            // Get the image paths present in the new body
            $newImagePaths = array();
            preg_match_all('/<img[^>]+src="([^"]+)"/', $body, $matches);

            if (!empty($matches[1])) {

                $newImagePaths = $matches[1];

            }

            $combinedImagePaths = array_merge($currentImagePaths, $newImagePaths, $previousImagePaths);
            $combinedImagePaths = array_unique($combinedImagePaths);
            $previousImagePaths = $combinedImagePaths;

            return $body;
    
        } catch (Exception $e) {

            echo "Moving Uploaded User Images from CKFinder Failed: " . $e->getMessage();
            return;

        }

    }
    
    function cleanUpCkFinderImageDirectory($id, $type, $combinedImagePaths) {
        
        try {

            $config = json_decode(file_get_contents(getConfigFile()), true);
            $uploadDirectory = $config['FILE_DIRECTORY']['Images'][ucwords($type)] . $id . "/";
            $directoyImagePaths = glob($uploadDirectory . 'ckfinder_*.*');
            $deletedImagePaths = array_diff($directoyImagePaths, $combinedImagePaths);
            
            foreach ($deletedImagePaths as $deletedImagePath) {

                // Delete the image if it's no longer used in both user and job descriptions
                if (file_exists($deletedImagePath)) {

                    unlink($deletedImagePath);

                }

            }

            if (is_dir($uploadDirectory)) {

                $files = array_diff(scandir($uploadDirectory), array('.', '..'));
                
                if(empty($files)){

                    rmdir($uploadDirectory);

                }

            }

        } catch (Exception $e) {

            echo "Cleaning up Image Directory Failed: " . $e->getMessage();

        }

    }

    function getEmbedContent($embedUrl, $app) { //API Controller Folder if expansion for maintainability
        
        try {

            $config = json_decode(file_get_contents(getConfigFile()), true);
            $id = null;

            $app = ucwords($app);
            $apiKey = $config['API_KEYS'][$app]['Key']; 
            $url = $config['API_KEYS'][$app]['Url'];

            switch($app){

                case 'Youtube':{

                    $patterns = [
                        '/youtube\.com\/watch\?v=([^&]+)/i', // Full YouTube URL
                        '/youtu\.be\/([^&]+)/i', // Short YouTube URL
                    ];

                    foreach ($patterns as $pattern) {

                        if (preg_match($pattern, $embedUrl, $matches)) {
            
                            $id = $matches[1];
                            break;
            
                        }
            
                    }
            
                    $apiUrl = $url . "?part=player&id=" . $id . "&key=" . $apiKey;

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $apiUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    curl_close($ch);
                
                    $data = json_decode($response, true);
                
                    // Check if the API call was successful and the 'player' field exists
                    if (isset($data['items'][0]['player']['embedHtml'])) {
            
                        return $data['items'][0]['player']['embedHtml'];
            
                    } else {
            
                        // Return null if there was an error or 'embedHtml' field is missing
                        return null;
            
                    }

                    break;
                }

                default:{

                    return null;
                    break;

                }

            }

        } catch (Exception $e) {

            echo "Getting Embed Content Failed: " . $e->getMessage();

        }
        
    }

    function deleteFolder($folderPath) {
        
        try{

            if (is_dir($folderPath)) {
                
                $files = array_diff(scandir($folderPath), array('.', '..'));
        
                foreach ($files as $file) {
                    
                    $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
                    
                    if (is_dir($filePath)) {
                        
                        deleteFolder($filePath); // Recursively delete subdirectories
                    
                    } else {
                        
                        unlink($filePath); // Delete files

                    }
                }
        
                rmdir($folderPath);

            }
            
        } catch (Exception $e) {

            echo "Deleting Folder Failed: " . $e->getMessage();

        }
    
    }

?>