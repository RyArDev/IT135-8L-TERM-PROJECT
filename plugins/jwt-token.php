<?php
    
    require_once(dirname(__DIR__).'/vendor/autoload.php');
    require_once(dirname(__DIR__).'/utilities/settings/stage-settings.php');
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    function generateJWTToken($userId, $username, $email, $roleId){

        $config = json_decode(file_get_contents(getConfigFile()), true);
        $secretKey = $config['SECRET_KEYS']['Jwt'];

        $userData = [
            'user_id' => $userId,
            'username' => $username,
            'email' => $email,
            'role_id' => $roleId
        ];


        $token = JWT::encode($userData, $secretKey, 'HS256');

        return $token;

    }

    function validateJWTToken($type){

        try {

            $jwtToken = getAuthorizationBearerToken();
            $config = json_decode(file_get_contents(getConfigFile()), true);
            $secretKey = $config['SECRET_KEYS']['Jwt'];
            $decodedToken = JWT::decode($jwtToken, new Key($secretKey, 'HS256'));

            switch(strtolower($type)){

                case 'user':{

                    if($decodedToken->role_id != 2) return false;
                    return true;

                    break;
                }

                case 'officer':{

                    if($decodedToken->role_id != 3) return false;
                    return true;

                    break;
                }

                case 'admin':{

                    if($decodedToken->role_id != 4) return false;
                    return true;

                    break;
                }

                default:{

                    return false;
                    break;

                }

            }

            return false;

        } catch (Exception $e) {
            
            return false;

        }

    }

    function getAuthorizationBearerToken() {
        
        $headers = apache_request_headers();
        //$headers = getallheaders();

        if (isset($headers['Authorization'])) {
            
            $authorizationHeader = $headers['Authorization'];

            if (preg_match('/Bearer\s+(.*)/', $authorizationHeader, $matches)) {

                return $matches[1];

            }
        }
    
        return null;
    }

?>