
<?php

    require_once('utilities/settings/stage-settings.php');

    function import_js($name){
        
        // Read the JSON file 
        $json = file_get_contents(getConfigFile());
        
        // Decode the JSON file
        $jsLinks = json_decode($json, true);

        //Check if the name exists in the json list
        if (array_key_exists($name, $jsLinks['JS_LIST'])) {

            echo "<script defer src='" . $jsLinks['JS_LIST'][$name] . "'></script>";

        }

    }
  
?>