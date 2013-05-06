<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
namespace alchemy\vendor;

class DropboxException extends \Exception {}

/**
 * Dropbox PHP Core API
 */
class Dropbox
{
    public function __construct($key = '', $secret = '', $namespace = '')
    {

        $this->key = $key;
        $this->secret = $secret;
        $this->requestTokenSecret = &$_SESSION[$namespace . '_oauth_token_secret'];
        $this->requestToken = &$_SESSION[$namespace . '_oauth_token'];
        $this->token = &$_SESSION[$namespace . '_token'];
    }

    public function getAuthorizationURL($callback = 'http://lotos/lotos/projects/edit/1')
    {
        if (!$this->requestToken || !$this->requestTokenSecret) {
            $token = $this->doRequest('/request_token');
            print_r($token);
            $this->requestTokenSecret = $token['oauth_token_secret'];
            $this->requestToken = $token['oauth_token'];
        }
        return self::DB_GATEWAY . '/authorize?oauth_token=' . $this->oauthToken . '&oauth_callback=' . $callback;
    }

    public function getAccessToken()
    {

    }


    protected function doRequest($url, $data = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_GATEWAY . $url);
        $headers = $this->getHeaders();

        print_r($headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($ch, CURLOPT_CON)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        $httpResponse = curl_exec($ch);
        curl_close($ch);

        parse_str($httpResponse, $response);

        return $response;
    }

    private function getHeaders()
    {
        return array(
            'Authorization: OAuth oauth_version="1.0", oauth_signature_method="PLAINTEXT",' .
                ' oauth_consumer_key="' . $this->key . '"' .
                ($this->requestToken ? ', oauth_token="' . $this->requestToken . '"' : '') .
                ', oauth_signature="' . $this->secret . '&"',
            'Content-Type: multipart/form-data;'
        );
    }

    const API_GATEWAY = 'https://api.dropbox.com/1/oauth';
    const DB_GATEWAY  = 'https://www.dropbox.com/1/oauth';

    protected $key = '';
    protected $secret = '';
    protected $requestTokenSecret;
    protected $requestToken;
    protected $oauthToken;
    protected $oauthTokenSecret;
}
