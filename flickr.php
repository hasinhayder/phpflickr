<?php
/**
 * flickr authentication script based on
 * pecl oauth extension
 */
session_start();
include_once("config.php");
/*
unset($_SESSION['frequest_token_secret']);
unset($_SESSION['faccess_oauth_token']);
unset($_SESSION['faccess_oauth_token_secret']);
 */
$oauthc = new OAuth($oauth['flickr']['consumerkey'],
        $oauth['flickr']['consumersecret'],
        OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_URI); //initiate
if(empty($_SESSION['frequest_token_secret'])) {
    //get the request token and store it
    $request_token_info = $oauthc->getRequestToken($oauth['flickr']['requesttokenurl']); //get request token
    $_SESSION['frequest_token_secret'] = $request_token_info['oauth_token_secret'];
    header("Location: {$oauth['flickr']['authurl']}?oauth_token=".$request_token_info['oauth_token']."&perms=read");//forward user to authorize url
}
else if(empty($_SESSION['faccess_oauth_token'])) {
    //get the access token - dont forget to save it 
    $request_token_secret = $_SESSION['frequest_token_secret'];
    $oauthc->setToken($_REQUEST['oauth_token'],$request_token_secret);//user allowed the app, so u
    //$oauthc->addRequiredParameter(array("perms"=>"read"));
    $access_token_info = $oauthc->getAccessToken($oauth['flickr']['accesstokenurl']);
    $_SESSION['faccess_oauth_token']= $access_token_info['oauth_token'];
    $_SESSION['faccess_oauth_token_secret']= $access_token_info['oauth_token_secret'];
}
if(isset($_SESSION['faccess_oauth_token'])) {
    //now fetch current users profile
    $access_token = $_SESSION['faccess_oauth_token'];
    $access_token_secret =$_SESSION['faccess_oauth_token_secret'];
    $oauthc->setToken($access_token,$access_token_secret);
    $data = $oauthc->fetch('http://api.flickr.com/services/rest/?method=flickr.test.login&api_key=ae29ce34e831937ac26483498e93f3e9&format=json');
    $response_info = $oauthc->getLastResponse();
    echo "<pre>";
    print_r(json_decode($response_info));
    echo "</pre>";
}
?>