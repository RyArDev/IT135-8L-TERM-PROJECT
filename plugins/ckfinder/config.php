<?php

/*
 * CKFinder Configuration File
 *
 * For the official documentation visit https://ckeditor.com/docs/ckfinder/ckfinder3-php/
 */

/*============================ PHP Error Reporting ====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/debugging.html

// Production
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 0);

// Development
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

/*============================ General Settings =======================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html

$config = array();
session_start();

/*============================ Enable PHP Connector HERE ==============================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_authentication
 
$config['authentication'] = function() {
    return isset($_SESSION['user_id']) && $_SESSION['user_id'];
};

/*============================ License Key ============================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_licenseKey

$config['licenseName'] = '';
$config['licenseKey']  = '';

/*============================ CKFinder Internal Directory ============================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_privateDir

$config['privateDir'] = array(
    'backend' => 'default',
    'tags'   => '.ckfinder/tags',
    'logs'   => '.ckfinder/logs',
    'cache'  => '.ckfinder/cache',
    'thumbs' => '.ckfinder/cache/thumbs',
);

/*============================ Images and Thumbnails ==================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_images

$config['images'] = array(
    'maxWidth'  => 1600,
    'maxHeight' => 1200,
    'quality'   => 80,
    'sizes' => array(
        'small'  => array('width' => 480, 'height' => 320, 'quality' => 80),
        'medium' => array('width' => 600, 'height' => 480, 'quality' => 80),
        'large'  => array('width' => 800, 'height' => 600, 'quality' => 80)
    )
);

/*=================================== Backends ========================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_backends

switch($_SESSION['current_page']){

    case 'admin':{

        $config['backends'][] = array(
            'name'         => 'user',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/images/pages/admin-dashboard/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        break;
    }

    case 'announcement':{

        $config['backends'][] = array(
            'name'         => 'announcement',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/images/announcements/'.$_SESSION['announcement_id'].'/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );
        

        break;
    }

    case 'barangay':{

        $config['backends'][] = array(
            'name'         => 'default',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/images/pages/barangay-corner/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        break;
    }

    case 'comment':{

        $config['backends'][] = array(
            'name'         => 'user',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/images/comments/'.$_SESSION['comment_id'].'/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        break;
    }

    case 'contact':{

        $config['backends'][] = array(
            'name'         => 'default',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/images/pages/contact-us/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        break;
    }

    case 'error':{

        $config['backends'][] = array(
            'name'         => 'default',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/images/pages/error-page/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        break;
    }

    case 'forum':{

        $config['backends'][] = array(
            'name'         => 'user',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/images/forums/'.$_SESSION['forum_id'].'/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        break;
    }

    case 'history':{

        $config['backends'][] = array(
            'name'         => 'default',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/images/pages/history/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );
        
        break;
    }

    case 'homepage':{

        $config['backends'][] = array(
            'name'         => 'default',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/images/pages/homepage/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        break;
    }

    case 'login':{

        $config['backends'][] = array(
            'name'         => 'default',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/images/pages/login/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        break;
    }

    case 'register':{

        $config['backends'][] = array(
            'name'         => 'default',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/images/pages/register/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        break;
    }

    case 'services':{

        $config['backends'][] = array(
            'name'         => 'user',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/images/pages/services/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        break;
    }

    case 'user':{

        $config['backends'][] = array(
            'name'         => 'user',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/images/users/'.$_SESSION['user_id'].'/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        break;
    }

    default:{

        $config['backends'][] = array(
            'name'         => 'default',
            'adapter'      => 'local',
            'baseUrl'      => '/assets/ckfinder',
        //  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
            'chmodFiles'   => 0777,
            'chmodFolders' => 0755,
            'filesystemEncoding' => 'UTF-8',
        );

        break;
    }

}

/*================================ Resource Types =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_resourceTypes

$config['defaultResourceTypes'] = '';

$config['resourceTypes'][] = array(
    'name'              => 'Files', // Single quotes not allowed.
    'directory'         => 'files',
    'maxSize'           => 0,
    'allowedExtensions' => '7z,aiff,asf,avi,bmp,csv,doc,docx,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pptx,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xlsx,zip',
    'deniedExtensions'  => '',
    'backend'           => 'default'
);

$config['resourceTypes'][] = array(
    'name'              => 'Images',
    'directory'         => 'images',
    'maxSize'           => 0,
    'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
    'deniedExtensions'  => '',
    'backend'           => 'default'
);

/*================================ Access Control =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_roleSessionVar

$config['roleSessionVar'] = 'CKFinder_UserRole';

// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_accessControl
$config['accessControl'][] = Array(
    'role' => 'none',
    'resourceType' => '*',
    'folder' => '/',
 
    'FOLDER_VIEW'        => false,
    'FOLDER_CREATE'      => false,
    'FOLDER_RENAME'      => false,
    'FOLDER_DELETE'      => false,
 
    'FILE_VIEW'          => false,
    'FILE_CREATE'        => false,
    'FILE_RENAME'        => false,
    'FILE_DELETE'        => false,
 
    'IMAGE_RESIZE'        => false,
    'IMAGE_RESIZE_CUSTOM' => false
);

$config['accessControl'][] = Array(
    'role' => 'user',
    'resourceType' => '*',
    'folder' => '/',
 
    'FOLDER_VIEW'        => true,
    'FOLDER_CREATE'      => false,
    'FOLDER_RENAME'      => false,
    'FOLDER_DELETE'      => false,
 
    'FILE_VIEW'          => true,
    'FILE_CREATE'        => true,
    'FILE_RENAME'        => true,
    'FILE_DELETE'        => true,
 
    'IMAGE_RESIZE'        => true,
    'IMAGE_RESIZE_CUSTOM' => true
);

$config['accessControl'][] = Array(
    'role' => 'officer',
    'resourceType' => '*',
    'folder' => '/',
 
    'FOLDER_VIEW'        => true,
    'FOLDER_CREATE'      => false,
    'FOLDER_RENAME'      => true,
    'FOLDER_DELETE'      => false,
 
    'FILE_VIEW'          => true,
    'FILE_CREATE'        => true,
    'FILE_RENAME'        => true,
    'FILE_DELETE'        => true,
 
    'IMAGE_RESIZE'        => true,
    'IMAGE_RESIZE_CUSTOM' => true
);

$config['accessControl'][] = Array(
    'role' => 'admin',
    'resourceType' => '*',
    'folder' => '/',
 
    'FOLDER_VIEW'        => true,
    'FOLDER_CREATE'      => true,
    'FOLDER_RENAME'      => true,
    'FOLDER_DELETE'      => true,
 
    'FILE_VIEW'          => true,
    'FILE_CREATE'        => true,
    'FILE_RENAME'        => true,
    'FILE_DELETE'        => true,
 
    'IMAGE_RESIZE'        => true,
    'IMAGE_RESIZE_CUSTOM' => true
);


/*================================ Other Settings =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html

$config['overwriteOnUpload'] = false;
$config['checkDoubleExtension'] = true;
$config['disallowUnsafeCharacters'] = false;
$config['secureImageUploads'] = true;
$config['checkSizeAfterScaling'] = true;
$config['htmlExtensions'] = array('html', 'htm', 'xml', 'js');
$config['hideFolders'] = array('.*', 'CVS', '__thumbs');
$config['hideFiles'] = array('.*');
$config['forceAscii'] = false;
$config['xSendfile'] = false;

// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_debug
$config['debug'] = false;

/*==================================== Plugins ========================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_plugins

$config['pluginsDirectory'] = __DIR__ . '/plugins';
$config['plugins'] = array();

/*================================ Cache settings =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_cache

$config['cache'] = array(
    'imagePreview' => 24 * 3600,
    'thumbnails'   => 24 * 3600 * 365,
    'proxyCommand' => 0
);

/*============================ Temp Directory settings ================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_tempDirectory

$config['tempDirectory'] = sys_get_temp_dir();

/*============================ Session Cause Performance Issues =======================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_sessionWriteClose

$config['sessionWriteClose'] = true;

/*================================= CSRF protection ===================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_csrfProtection

$config['csrfProtection'] = true;

/*===================================== Headers =======================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_headers

$config['headers'] = array();

/*============================== End of Configuration =================================*/

// Config must be returned - do not change it.
return $config;
