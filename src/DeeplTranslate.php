<?php

namespace WaiThaw\DeeplTranslate;

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

    public function __construct($version = '2')
    {
        $this->authKey = config('deepltranslate.auth_key');
        $this->host = config('deepltranslate.api_url');
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

        return $translations['translations'][0]['text'];
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
        
        return $body;
    }
    protected function request($url, $body)
    {
        
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        $result = curl_exec($this->curl);

        if (curl_errno($this->curl)) {
            throw new DeeplException('There was a cURL Error : ' . curl_error($this->curl));
        }

        $httpCode      = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $response      = json_decode($result, true);

        if ($httpCode != 200 && is_array($response) && array_key_exists('message', $response)) {
            throw new DeeplException("ERROR : ".$httpCode." => ".$response['message']);
        }

        return $response;

    }

}