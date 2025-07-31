<?php
    includeLibrary('google/vendor/autoload.php');

    class GoogleSignin{
        
        public function __construct() {
        }

        public static function getClient(){
            includeDefault(['configuration']);

            $CONFIGURATION = Configuration::general();

            $data = Array();

            // Call Google API
            $client = new Google_Client;
            $client->setApplicationName($CONFIGURATION['GOOGLE_SIGNIN_APPLICATION_NAME']);
            $client->setClientId($CONFIGURATION['GOOGLE_SIGNIN_CLIENT_ID']);
            $client->setClientSecret($CONFIGURATION['GOOGLE_SIGNIN_CLIENT_SECRET']);
            $client->setRedirectUri($CONFIGURATION['GOOGLE_SIGNIN_REDIRECT_URI']);
            $client->addScope('profile');
            $client->addScope('email');

            return $client;
        }

        public static function callback(){
            $client = self::getClient();

            // $callBackUrl = $client->createAuthUrl();
            $parseUrl = self::parseAuthRedirectUrl($_SERVER['QUERY_STRING']);
            $token = $client->fetchAccessTokenWithAuthCode($parseUrl['code']);

            // $_SESSION['sessionGSAccessToken'] = $token;
            return $client;
        }

        public static function parseAuthRedirectUrl($url){
            parse_str($url,$qsArray);
            return array(
                'code'    => $qsArray['code']
            );
        }

        public static function getProfile( $client ){
            $data = Array();

            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();

            $data['email']   =  $google_account_info->email;
            $data['name']    =  $google_account_info->name;
            $data['picture'] =  $google_account_info->picture;

            return $data;
        }


    }

?>