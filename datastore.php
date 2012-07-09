<?php
/**
 * FlickrLib by Hasin Hayder, hasin@leevio.com
 * Released under MIT License
 * @date: April 18, 2012
 */
class DataStore
{
    private $key;
    private $secret;
    private $token;
    private $params;
    private $method;
    private $endpoint;
    private $permission;
    private $redirectto;

    public function setKey($data)
    {
        $this->key = $data;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setSecret($data)
    {
        $this->secret = $data;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function setToken($data)
    {
        $this->token = $data;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setPrimaryCallback($data)
    {
        $this->params['oauth_callback'] = urlencode($data);
    }

    public function getPrimaryCallback()
    {
        return $this->params['oauth_callback'];
    }

    public function setSecondaryCallback($data)
    {
        $this->redirectto = urlencode($data);
    }

    public function getSecondaryCallback()
    {
        return $this->redirectto;
    }


    public function setParams($data)
    {
        $this->params = $data;
    }

    public function unsetParams($data)
    {
        foreach ($data as $key => $val) {
            unset($this->params[$key]);
        }
    }

    public function getParams()
    {
        $this->params['oauth_nonce'] = mt_rand(100000, 99999999);
        $this->params['oauth_timestamp'] = time() + 300;
        $this->params['oauth_consumer_key'] = $this->getKey();
        $this->params['oauth_signature_method'] = "HMAC-SHA1";
        $this->params['oauth_version'] = "1.0";
        ksort($this->params);
        return $this->params;
    }

    public function setMethod($data)
    {
        $this->method = $data;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setEndpoint($data)
    {
        $this->endpoint = $data;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function setPermission($data)
    {
        $this->permission = $data;
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function getBaseString()
    {
        $baseString = "";
        $params = $this->getParams();
        foreach ($params as $key => $value) {
            $baseString .= $key . "=" . $value . "&";
        }
        $baseString = substr($baseString, 0, -1);
        $encodedBaseString = "GET&" . urlencode($this->getEndpoint()) . "&" . urlencode($baseString);
        return $encodedBaseString;
    }

    public function getQueryString()
    {
        $baseString = "";
        $params = $this->params;
        foreach ($params as $key => $value) {
            $baseString .= $key . "=" . $value . "&";
        }
        $baseString = substr($baseString, 0, -1);
        $queryString = $baseString . "&oauth_signature=";
        return $queryString;
    }
}

?>