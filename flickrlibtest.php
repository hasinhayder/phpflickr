<?php
/**
 * FlickrLib by Hasin Hayder, hasin@leevio.com
 * Released under MIT License
 * @date: April 18, 2012
 */
session_start();
include_once("flickrlib.php");
include_once("flickrapi.php");
include_once("flickrauth.php");
include_once("objectbroker.php");
include_once("requestsigner.php");
include_once("httprequest.php");
include_once("datastore.php");

$fl = new FlickrLib("8cc0f91339ab3da808b215c72a3d564d", //key
    "54414ae0140a3903", //secret
    "http://grasshopper.me/f2/flickruserdata.php", //where it will be redirected after successful login
    "read" //permission
);
$fl->auth->authenticate();
?>