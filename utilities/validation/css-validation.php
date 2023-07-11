
<?php
  
function import_css($name){
    
    // Read the JSON file 
    $json = file_get_contents('utilities/settings/css-list.json');
    
    // Decode the JSON file
    $cssLinks = json_decode($json, true);

    //Check if the name exists in the json list
    if (array_key_exists($name, $cssLinks)) {

        echo "<link rel='stylesheet' type='text/css' href='" . $cssLinks[$name] . "' media='screen' />";

    }else{

        echo `<link rel="stylesheet" type="text/css" href="global.css" media="screen" />`;

    }

}
  
?>