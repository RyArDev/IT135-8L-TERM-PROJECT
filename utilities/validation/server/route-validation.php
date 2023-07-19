<?php

    require_once('utilities/settings/stage-settings.php');

    function import_route($name){
        
        // Read the JSON file 
        $json = file_get_contents(getConfigFile());
        
        // Decode the JSON file
        $routes = json_decode($json, true);

        //Check if the name exists in the json list
        if (array_key_exists($name, $routes['ROUTES'])) {

           return $routes['ROUTES'][$name];

        }

        return;

    }
  
?>