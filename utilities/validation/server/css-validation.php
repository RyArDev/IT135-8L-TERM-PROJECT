
<?php

    require_once('utilities/settings/stage-settings.php');

    function import_css($name){
        
        // Read the JSON file 
        $json = file_get_contents(getConfigFile());
        
        // Decode the JSON file
        $cssLinks = json_decode($json, true);

        //Check if the name exists in the json list
        if (array_key_exists($name, $cssLinks['CSS_LIST'])) {

            echo "<link rel='stylesheet' type='text/css' href='" . $cssLinks['CSS_LIST'][$name] . "' media='screen' />";

        }else{

            echo `<link rel="stylesheet" type="text/css" href="global.css" media="screen" />`;

        }

    }
  
?>