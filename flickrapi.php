<?php
/**
 * FlickrLib by Hasin Hayder, hasin@leevio.com
 * Released under MIT License
 * @date: April 18, 2012
 */
class FlickrApi
{
    private $datastore;
    private $requestSigner;
    private $oAuthToken;
    private $oAuthTokenSecret;

    function __construct()
    {
        $this->dataStore = ObjectBroker::getDataStore();
        $this->requestSigner = ObjectBroker::getRequestSigner();
        $this->oAuthToken=$_SESSION['oauth_token'];
        $this->oAuthTokenSecret = $_SESSION['oauth_token_secret'];
        $this->dataStore->setToken($this->oAuthTokenSecret);
    }

    public function testlogin()
    {
        $params = array("oauth_token" => $this->oAuthToken,
                    "format" => "json",
                    "method" => "flickr.test.login",
                    "nojsoncallback" => "1"
        );
        $this->dataStore->setParams($params);
        $endpoint = "http://api.flickr.com/services/rest";
        $this->dataStore->setEndpoint($endpoint);

        $signature = $this->requestSigner->getSignature($this->dataStore);
        $requestUrl = $endpoint . "?" . $this->dataStore->getQueryString() . $signature;
        $data = HTTPRequest::process($requestUrl);

        $this->datastore->unsetParams($params);
        return $data;
    }
}

?>