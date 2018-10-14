<?php

namespace YurcrmClient;

/**
 * Класс для работы с API YurCRM
 * Class YurcrmClient
 * @package YurcrmClient
 */
class YurcrmClient
{

    const API_SERVER_URL = 'https://www.yurcrm.ru/api/';

    protected $route;
    protected $method;
    protected $token;
    protected $data;
    protected $serverUrl;
    protected $curlLink;

    public function __construct($route, $method, $token, $serverUrl = self::API_SERVER_URL)
    {
        $this->route = $route;
        $this->method = $method;
        $this->token = $token;
        $this->serverUrl = $serverUrl;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, $this->method === 'POST' ? 1 : 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        $this->curlLink = $ch;
    }

    protected function getRequestUrl()
    {
        $route = $this->serverUrl . $this->route;

        if ($this->method == 'GET') {
            $route .= '?' . http_build_query($this->data + ['token' => $this->token]);
        } else {
            $route .= '?token=' . $this->token;
        }

        return $route;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    public function send()
    {
        $url = $this->getRequestUrl();
        curl_setopt($this->curlLink, CURLOPT_URL, $url);
        if($this->method === 'POST') {
            curl_setopt($this->curlLink, CURLOPT_POSTFIELDS, $this->data);
        }
        $jsonResponse = curl_exec($this->curlLink);
        $curlInfo = curl_getinfo($this->curlLink);
        curl_close($this->curlLink);

        return ['curlInfo' => $curlInfo, 'response' => $jsonResponse];
    }
}