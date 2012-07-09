<?php
/**
 * FlickrLib by Hasin Hayder, hasin@leevio.com
 * Released under MIT License
 * @date: April 18, 2012
 */
session_start();
class FlickrLib{
    public $auth;
    public $api;
    private $dataStore;

    function __construct($key,
                         $secret,
                         $callback,
                         $permissions,
                         $requestTokenUrl="http://www.flickr.com/services/oauth/request_token",
                         $userAuthUrl="http://www.flickr.com/services/oauth/authorize",
                         $accessTokenUrl="http://www.flickr.com/services/oauth/access_token"){
        $this->dataStore = ObjectBroker::getDataStore();
        $this->dataStore->setPrimaryCallback($callback);
        $this->auth = new FlickrAuth($key,
                    $secret,
                    $callback,$permissions,
                    $requestTokenUrl,
                    $userAuthUrl,
                    $accessTokenUrl);
        $this->api = new FlickrApi();
    }
}
?>