
<?php

    require_once('utilities/settings/stage-settings.php');

    function import_js($name){
        
        // Read and Decode the JSON file 
        $config = json_decode(file_get_contents(getConfigFile()), true);

        //Check if the name exists in the json list
        if (array_key_exists($name, $config['JS_LIST'])) {

            echo "<script defer src='" . $config['JS_LIST'][$name] . "'></script>";

        }

    }

    function import_ckEditor($editors) {
        
        echo "<script src='https://cdn.ckeditor.com/ckeditor5/38.1.1/classic/ckeditor.js'></script>";

        // Loop through each editor ID and initialize CKEditor
        foreach ($editors as $editor) {

            $id = $editor[0];
            $characterLimit = $editor[1];
            $ckUploadPath = 'plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json';
            
            echo "<script>";
            echo "ClassicEditor.create(document.querySelector('#$id'), {";
            echo "  maxLength: {";
            echo "    maxLength: $characterLimit";
            echo "  },";
            echo "  ckfinder: {";
            echo "    uploadUrl: '$ckUploadPath'";
            echo "  },";
            echo "})";
            echo "</script>";
        }
    }
  
?>