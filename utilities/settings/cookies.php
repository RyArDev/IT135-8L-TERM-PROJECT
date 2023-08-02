<?php 

    // Function to set cookies with SameSite and Secure attributes
    function setCookieWithAttributes($name, $value, $domain, $path, $expires, $samesite, $secure, $httponly) {
        setcookie($name, $value, [
            'domain' => $domain,
            'path' => $path,
            'expires' => $expires,
            'samesite' => $samesite,
            'secure' => $secure,
            'httponly' => $httponly
        ]);
    }

    /*// Cookies that should be sent in cross-site requests (SameSite=None, Secure=true)
    $crossSiteCookies = [
        'LAST_RESULT_ENTRY_KEY' => ['value_here', '.youtube.com'],
        'mp_52e5e0805583e8a410f1ed50d8e0c049_mixpanel' => ['value_here', 'www.youtube.com'],
    ];

    // Cookies that should not be sent in cross-site requests (SameSite=Strict or SameSite=Lax)
    $nonCrossSiteCookies = [
        'remote_sid' => ['value_here', '.youtube.com'],
        '__mp_opt_in_out_52e5e0805583e8a410f1ed50d8e0c049' => ['value_here', '.youtube.com'],
        'HSID' => ['value_here', '.youtube.com'],
        'SSID' => ['value_here', '.youtube.com'],
        'APISID' => ['value_here', '.youtube.com'],
        'SAPISID' => ['value_here', '.youtube.com'],
        '__Secure-1PAPISID' => ['value_here', '.youtube.com'],
        'SID' => ['value_here', '.youtube.com'],
        '__Secure-1PSID' => ['value_here', '.youtube.com'],
        'PREF' => ['value_here', '.youtube.com'],
        '__Secure-1PSIDTS' => ['value_here', '.youtube.com'],
        'SIDCC' => ['value_here', '.youtube.com'],
        '__Secure-1PSIDCC' => ['value_here', '.youtube.com'],
        'OTZ' => ['value_here', 'play.google.com'],
        'OSID' => ['value_here', 'play.google.com'],
    ];

    // Set SameSite=None and Secure=true for cookies sent in cross-site requests
    foreach ($crossSiteCookies as $name => $cookieData) {
        setCookieWithAttributes($name, $cookieData[0], $cookieData[1], '/', time() + 3600, 'None', true, true);
    }

    // Set SameSite=Strict for cookies not sent in cross-site requests
    foreach ($nonCrossSiteCookies as $name => $cookieData) {
        setCookieWithAttributes($name, $cookieData[0], $cookieData[1], '/', time() + 3600, 'Strict', false, true);
    }*/


    function setRefreshTokenCookies($refreshToken){

        setCookieWithAttributes(
            'refresh_token', 
            $refreshToken, 
            $_SERVER['HTTP_HOST'], 
            '/', 
            time() + (7 * 24 * 60 * 60), 
            'Strict', 
            false, 
            true
        );

    }

    function removeCookie($name){

        setCookieWithAttributes(
            $name, 
            '', 
            $_SERVER['HTTP_HOST'], 
            '/', 
            time() - 1, 
            'None', 
            true, 
            true
        );

    }


?>