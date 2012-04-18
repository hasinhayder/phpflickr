<?php
/**
 * FlickrLib by Hasin Hayder, hasin@leevio.com
 * Released under MIT License
 * @date: April 18, 2012
 */
class RequestSigner
{
    public function getSignature($dataStore)
    {
        $baseString = $dataStore->getBaseString();
        //echo "\n\n".$baseString."\n\n";
        $key = $dataStore->getSecret() . "&" . $dataStore->getToken();
        //echo "<br/><br/>".$key."<br/><br/>";
        $signature = hash_hmac("SHA1", $baseString, $key, true);
        return base64_encode($signature);
    }
}

?>