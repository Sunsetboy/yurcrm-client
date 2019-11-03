<?php

namespace YurcrmClient;

class YurcrmResponse
{
    /**
     * @var array|bool $info Ассоц. массив или false, возвращаемый функцией curl_getinfo
     */
    protected $info;

    /**
     * @var string $response JSON ответ от API
     */
    protected $response;

    /** @var integer $httpCode */
    protected $httpCode;

    /**
     * @param mixed $curlResource Ресурс CURL
     */
    public function __construct($curlResource)
    {
        $this->response = curl_exec($curlResource);
        $this->info = curl_getinfo($curlResource);
        if (is_array($this->info)) {
            $this->httpCode = $this->info['http_code'];
        }
    }

    /**
     * @return array|bool
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }
}
