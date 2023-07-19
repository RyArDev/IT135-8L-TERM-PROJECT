<?php

    function getConfigFile(){

        $settings = json_decode(file_get_contents('settings.json'), true);
    
        if($settings['STAGE'] == "Production"){
            
            return 'utilities/settings/config-production.json';
    
        }

        return 'utilities/settings/config-development.json';

    }

?>