
<?php

    require_once('utilities/settings/stage-settings.php');

    function import_css($name){
        
        // Read and Decode the JSON file 
        $config = json_decode(file_get_contents(getConfigFile()), true);

        //Check if the name exists in the json list
        if (array_key_exists($name, $config['CSS_LIST'])) {

            echo "<link rel='stylesheet' type='text/css' href='" . $config['CSS_LIST'][$name] . "' media='screen' />";

        }else{

            echo `<link rel="stylesheet" type="text/css" href="global.css" media="screen" />`;

        }

    }
  
?>