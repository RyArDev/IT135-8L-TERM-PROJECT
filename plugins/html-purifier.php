<?php 

    require_once('vendor/autoload.php');

    function cleanHtml($inputHTML){

        // Create a new HTML Purifier configuration
        $config = HTMLPurifier_Config::createDefault();

        $config->set('HTML.Allowed', 
            'p,
            a[href|title|media],
            b,
            strong,
            i,
            em,
            img[src|alt],
            ul,
            li,
            ol[type],
            table,
            tr,
            th,
            td,
            tbody,
            thead,
            br,
            blockquote,
            figure[class],
            h1,
            h2,
            h3,
            h4
        ');

        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');

        // Create the HTML Purifier instance
        $purifier = new HTMLPurifier($config);

        // Purify and sanitize the HTML input
        $cleanHTML = $purifier->purify($inputHTML);

        return $cleanHTML;
    }

?>