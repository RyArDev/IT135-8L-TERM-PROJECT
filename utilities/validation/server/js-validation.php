
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
        echo '<script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/classic/ckeditor.js"></script>';
        echo <<<EOT
                <script>
                    const ckEditorInstances = {};
                </script>
            EOT;
    
        // Loop through each editor ID and initialize CKEditor
        foreach ($editors as $editor) {
            
            $id = $editor[0];
            $characterLimit = $editor[1];
            $ckUploadPath = 'plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json';
    
            $script = <<<EOT
                    <script>
                        ClassicEditor.create(document.querySelector('#$id'), {
                            maxLength: {
                                maxLength: $characterLimit
                            },
                            ckfinder: {
                                uploadUrl: '$ckUploadPath'
                            },
                            htmlEmbed: {
                                showPreviews: true
                            },
                            htmlSupport: {
                                allow: [
                                    {
                                        name: /^.*$/,
                                        styles: true,
                                        attributes: true,
                                        classes: true
                                    }
                                ]
                            },
                            image: {
                                styles: [
                                    'alignCenter',
                                    'alignLeft',
                                    'alignRight'
                                ],
                                resizeOptions: [
                                    {
                                        name: 'resizeImage:original',
                                        label: 'Original',
                                        value: null
                                    },
                                    {
                                        name: 'resizeImage:50',
                                        label: '50%',
                                        value: '50'
                                    },
                                    {
                                        name: 'resizeImage:75',
                                        label: '75%',
                                        value: '75'
                                    }
                                ],
                                toolbar: [
                                    'imageTextAlternative', 'toggleImageCaption', '|',
                                    'imageStyle:inline', 'imageStyle:wrapText', 'imageStyle:breakText', 'imageStyle:side', '|',
                                    'resizeImage'
                                ],
                                insert: {
                                    integrations: [
                                        'insertImageViaUrl'
                                    ]
                                }
                            }
                        })
                        .then(editor => {
                            ckEditorInstances['$id'] = editor;
                        });
                    </script>
                    EOT;
    
            echo $script;

        }
        
    }
  
?>