<?php
/**
 * FlickrLib by Hasin Hayder, hasin@leevio.com
 * Released under MIT License
 * @date: April 18, 2012
 */
class ObjectBroker{
    private static $stack;

    public static function getDataStore(){
        ObjectBroker::$stack['ds'] = !empty(ObjectBroker::$stack['ds'])?ObjectBroker::$stack['ds']:new DataStore();
        return ObjectBroker::$stack['ds'];
    }

    public static function getRequestSigner(){
        ObjectBroker::$stack['signer'] = !empty(ObjectBroker::$stack['signer'])?ObjectBroker::$stack['signer']:new RequestSigner();
        return ObjectBroker::$stack['signer'];
    }


}
?>