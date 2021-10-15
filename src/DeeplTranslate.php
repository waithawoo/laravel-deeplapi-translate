<?php

namespace Wto\DeeplTranslate;

class DeeplTranslate
{
    protected $authKey;
    protected $host;
    protected $curl;
    protected $version;


    const API_PROTOCOL = 'https';

    const API_URL_STRUCTURE = '%s://%s/v%s/%s?auth_key=%s';

    const API_LANGUAGE_RESOURCE = 'languages';
    const API_TRANSLATE_RESOURCE = 'translate';

    public function __construct($authKey, $version = '2', $host = 'api-free.deepl.com')
    {
        $this->authKey = $authKey;
        $this->host = $host;
        $this->version = $version;
        $this->curl = curl_init();

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    }

    public function __destruct()
    {
        if ($this->curl && is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

    public function languages($type = null)
    {
        $url       = $this->buildURL(self::API_LANGUAGE_RESOURCE);
        $body      = $this->buildRequestData(array('type' => $type));
        $languages = $this->request($url, $body);

        return $languages;
    }

    public function translate($text, $source_lang, $target_lang)
    {
        $url           = $this->buildURL(self::API_TRANSLATE_RESOURCE);
        $body          = $this->buildRequestData(array('text' => $text, 'source_lang' => $source_lang, 'target_lang' => $target_lang,'split_sentences'=>0));
        $translations  = $this->request($url, $body);

        return $translations['translations'][0];
    }
    protected function buildURL($resource)
    {
        $url = sprintf(
            self::API_URL_STRUCTURE,
            self::API_PROTOCOL,
            $this->host,
            $this->version,
            $resource,
            $this->authKey
        );
        return $url;
    }

    protected function buildRequestData($parameters)
    {
        // if(array_key_exists('text', $parameters)){
        //     $text = $parameters['text'];
        //     $texts = '';
        //     foreach($text as $eachtext){
        //         $texts .= '&text='.rawurlencode($eachtext);
        //     }
        // }

        $body = http_build_query($parameters);
        //dd($body);
        return $body;
    }
    protected function request($url, $body)
    {
        //dd($body);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        $response = curl_exec($this->curl);

        if (curl_errno($this->curl)) {
            // throw new DeepLException('There was a cURL Request Error : ' . curl_error($this->curl));
            dd('There was a cURL Request Error : ' . curl_error($this->curl));

        }

        $httpCode      = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $responseArray = json_decode($response, true);

        if ($httpCode != 200 && is_array($responseArray) && array_key_exists('message', $responseArray)) {
            // throw new DeepLException($responseArray['message'], $httpCode);
            dd($responseArray['message'], $httpCode);

        }
        //dd($responseArray);

        return $responseArray;

    }

}