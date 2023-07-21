
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

    function import_ckEditor($editors) {
        echo "<script src='https://cdn.ckeditor.com/ckeditor5/38.1.1/classic/ckeditor.js'></script>";
    
        // Loop through each editor ID and initialize CKEditor
        foreach ($editors as $editor) {

            $id = $editor[0];
            $characterLimit = $editor[1];

            echo "<script>";
            echo "ClassicEditor.create(document.querySelector('#$id'))";
            echo ".then(editor => {";
            echo "editor.ui.view.editable.element.setAttribute('maxlength', '$characterLimit');"; // Set max length to 5000 characters
            echo "})";
            echo ".catch(error => {";
            echo "console.error(error);";
            echo "})";
            echo "</script>";
        }
    }
  
?>