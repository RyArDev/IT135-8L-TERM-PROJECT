<?php

    require_once('utilities/settings/stage-settings.php');

    function getEmbedContent($embedUrl, $app) { 
            
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

?>