<?php
/**
 * FlickrLib by Hasin Hayder, hasin@leevio.com
 * Released under MIT License
 * @date: April 18, 2012
 * @modification: July 9, 2012
 */
session_start();
include_once("flickrlib.php");
include_once("flickrapi.php");
include_once("flickrauth.php");
include_once("objectbroker.php");
include_once("requestsigner.php");
include_once("httprequest.php");
include_once("datastore.php");
if($fl->auth->isLoggedIn()){
    echo "Logged in<br/>";
    echo "Signed API Response: ". $fl->api->testlogin();
}
?>