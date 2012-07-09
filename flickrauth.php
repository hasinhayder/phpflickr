<?php
/**
 * FlickrLib by Hasin Hayder, hasin@leevio.com
 * Released under MIT License
 * @date: April 18, 2012
 */

class FlickrAuth
{
    private $dataStore;
    private $requestSigner;
    private $token;
    private $oAuthToken;
    private $oAuthTokenSecret;
    private $oAuthVerifier;
    private $perms = "read";
    private $redirectUrl;
    private $requestTokenUrl;
    private $accessTokenUrl;
    private $userAuthUrl;

    public function __construct($key,
                                $secret,
                                $callback,
                                $perms,
                                $requestTokenUrl="http://www.flickr.com/services/oauth/request_token",
                                $userAuthUrl="http://www.flickr.com/services/oauth/authorize",
                                $accessTokenUrl="http://www.flickr.com/services/oauth/access_token")
    {
        $this->dataStore = ObjectBroker::getDataStore();
        $this->dataStore->setKey($key);
        $this->dataStore->setSecret($secret);
        $this->dataStore->setPrimaryCallback('http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
        $this->dataStore->setSecondaryCallback($callback);
        $this->dataStore->setPermission($perms);
        $this->requestSigner = ObjectBroker::getRequestSigner();

        $this->userAuthUrl = $userAuthUrl;
        $this->requestTokenUrl=$requestTokenUrl;
        $this->accessTokenUrl = $accessTokenUrl;
    }

    function authenticate($forced = false)
    {
        /*if($this->isLoggedIn() && !$forced){
            $cb = urldecode($this->dataStore->getSecondaryCallback());
            header("location: {$cb}");
        }*/
        if ($_SESSION['oauth_token_secret'] && $_SESSION['oauth_token']) {
            $this->oAuthToken=$_SESSION['oauth_token'];
            $this->oAuthTokenSecret = $_SESSION['oauth_token_secret'];
            $this->dataStore->setToken($this->oAuthTokenSecret);
            unset($_SESSION['token_secret']);
        } else {
            if (!$_REQUEST['oauth_verifier']) {
                if (empty($this->token) || $forced) {
                    $requestToken = $this->getRequestToken();
                    if ($requestToken['oauth_callback_confirmed'] == 'true') {
                        $this->oAuthToken = $requestToken['oauth_token'];
                        $this->oAuthTokenSecret = $requestToken['oauth_token_secret'];
                        $_SESSION['token_secret'] = $this->oAuthTokenSecret;
                        $this->requestUserAuthorization();
                    }
                }
            } else if ($_REQUEST['oauth_verifier']) {
                $this->oAuthVerifier = $_REQUEST['oauth_verifier'];
                $this->oAuthToken = $_REQUEST['oauth_token'];
                $this->dataStore->setToken($_SESSION['token_secret']);
                $token = $this->getAccessToken();
                /*Now the call is authorized */
                $_SESSION['oauth_token'] = $token['oauth_token'];
                $_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];
                $this->dataStore->setToken($token['oauth_token_secret']);
                $cb = urldecode($this->dataStore->getSecondaryCallback());
                header("location: {$cb}");
            }
        }
    }

    private function testcall()
    {
        echo "Test Call<br/>";
        $this->dataStore->setParams(array("oauth_token" => $this->oAuthToken,
            "format" => "json",
            "method"=>"flickr.test.login",
            "nojsoncallback"=>"1"
        ));
        $endpoint = "http://api.flickr.com/services/rest";
        $this->dataStore->setEndpoint($endpoint);

        $signature = $this->requestSigner->getSignature($this->dataStore);
        $requestUrl = $endpoint . "?" . $this->dataStore->getQueryString() . $signature;
        $data = HTTPRequest::process($requestUrl);
        echo $data;
    }

    private function getAccessToken()
    {
        $this->dataStore->setParams(array("oauth_token" => $this->oAuthToken,
            "oauth_verifier" => $this->oAuthVerifier));
        $endpoint = $this->accessTokenUrl;
        $this->dataStore->setEndpoint($endpoint);

        $signature = $this->requestSigner->getSignature($this->dataStore);
        $requestUrl = $endpoint . "?" . $this->dataStore->getQueryString() . $signature;
        $data = HTTPRequest::process($requestUrl);
        $pdata = array();
        parse_str($data, $pdata);
        return array("fullname" => $pdata['fullname'],
            "oauth_token" => $pdata['oauth_token'],
            "oauth_token_secret" => $pdata['oauth_token_secret'],
            "user_nsid" => $pdata['user_nsid'],
            "username" => $pdata['username']
        );
    }

    private function getRequestToken()
    {
        $endpoint = $this->requestTokenUrl;
        $this->dataStore->setEndpoint($endpoint);

        $signature = $this->requestSigner->getSignature($this->dataStore);
        $requestUrl = $endpoint . "?" . $this->dataStore->getQueryString() . $signature;
        $data = HTTPRequest::process($requestUrl);
        $pdata = array();
        parse_str($data, $pdata);
        return array("oauth_callback_confirmed" => $pdata['oauth_callback_confirmed'],
            "oauth_token" => $pdata['oauth_token'],
            "oauth_token_secret" => $pdata['oauth_token_secret']
        );
        return $dataArray;
    }

    private function requestUserAuthorization()
    {
        $endpoint = $this->userAuthUrl."?oauth_token=";
        $this->dataStore->setEndpoint($endpoint);
        $redirectUrl = $endpoint . "" . $this->oAuthToken . "&perms=" . $this->dataStore->getPermission();
        header("location: {$redirectUrl}");
    }

    public function isLoggedIn(){
        if($_SESSION['oauth_token_secret'] && $_SESSION['oauth_token'])
            return true;
        return false;
    }
};
?>