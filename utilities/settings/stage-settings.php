<?php

    function getConfigFile(){

        $settings = json_decode(file_get_contents(dirname(dirname(__DIR__)).'/settings.json'), true);
    
        if($settings['STAGE'] == "Production"){
            
            return dirname(dirname(__DIR__)).'/utilities/settings/config-production.json';
    
        }

        return dirname(dirname(__DIR__)).'/utilities/settings/config-development.json';

    }

?>